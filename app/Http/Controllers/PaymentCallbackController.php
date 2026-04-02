<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order; 
use App\Models\User;
use App\Services\FonnteService;
use Illuminate\Support\Facades\Log; 

class PaymentCallbackController extends Controller
{
    public function callback(Request $request)
    {
        Log::info('--- [CALLBACK MIDTRANS MULAI] ---');
        try {
            $callback = $request->all();
            Log::info('Data Diterima:', $callback);

            $orderIdFull = $callback['order_id']; 
            $orderParts = explode('-', $orderIdFull);
            
            // Ambil ID asli (angka pertama sebelum tanda strip)
            $orderId = $orderParts[0]; 
            
            Log::info("Mencari Order ID: " . $orderId);
            $order = Order::with(['user', 'services'])->find($orderId);

            if (!$order) {
                Log::error("Order #{$orderId} tidak ditemukan!");
                return response()->json(['message' => 'Not Found'], 404);
            }

            $status = $callback['transaction_status'];

            // --- LOGIKA PEMBAYARAN BERHASIL (Settlement/Capture) ---
            if ($status == 'settlement' || $status == 'capture') {
                
                // 1. JIKA INI PEMBAYARAN TAHAP DP (50%)
                if ($order->payment_step == 'dp') {
                    $order->update([
                        'payment_status' => 'unpaid', // Masih unpaid karena baru DP
                        'payment_step' => 'full',   // Naik ke tahap pelunasan
                        'snap_token' => null        // Hapus token DP agar bisa generate token pelunasan nanti
                    ]);

                    Log::info("HASIL: DP Berhasil untuk Order #{$orderId}. Mengirim Notif ke Admin.");

                    // KIRIM NOTIFIKASI KE ADMIN VIA FONNTE
                    $fonnte = app(FonnteService::class);
                    $serviceNames = $order->services->pluck('name')->implode(', ');
                    
                    $pesanAdmin = "🚨 *DP TELAH LUNAS (50%)!* 🚨\n\n";
                    $pesanAdmin .= "Order ID: #{$order->id}\n";
                    $pesanAdmin .= "👤 Pelanggan: {$order->user->name}\n";
                    $pesanAdmin .= "🛠️ Layanan: {$serviceNames}\n";
                    $pesanAdmin .= "💵 Nominal DP: Rp " . number_format($order->dp_amount, 0, ',', '.') . "\n";
                    $pesanAdmin .= "📅 Jadwal: {$order->booking_date} ({$order->booking_time})\n\n";
                    $pesanAdmin .= "Pembayaran DP sudah diverifikasi sistem. Silakan tentukan teknisi di Dashboard Admin! 🚀";

                    $admins = User::where('role', 'admin')->get();
                    foreach ($admins as $admin) {
                        if (!empty($admin->phone)) {
                            $fonnte->sendMessage($admin->phone, $pesanAdmin);
                        }
                    }
                } 
                
                // 2. JIKA INI PEMBAYARAN TAHAP PELUNASAN (FULL)
                else {
                    $order->update([
                        'payment_status' => 'paid', // SEKARANG BARU STATUS LUNAS (TOTAL)
                        'snap_token' => null
                    ]);
                    Log::info("HASIL: Pelunasan Berhasil untuk Order #{$orderId}. STATUS: PAID ✅");
                }
            }

            // JIKA PEMBAYARAN EXPIRED/CANCEL
            elseif (in_array($status, ['expire', 'cancel', 'deny'])) {
                $order->update(['payment_status' => 'failed']);
                Log::error("HASIL: Pembayaran Order #{$orderId} Gagal/Expired ❌");
            }

            return response()->json(['message' => 'Success']);

        } catch (\Exception $e) {
            Log::error('Kritis Error pada Callback: ' . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }
}