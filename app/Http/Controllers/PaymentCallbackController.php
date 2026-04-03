<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order; 
use App\Models\User; // Tambahkan ini agar bisa cari Admin
use App\Services\FonnteService; // Tambahkan ini agar bisa kirim WA
use Illuminate\Support\Facades\Log; 

class PaymentCallbackController extends Controller
{
    // Tambahkan FonnteService $fonnte di sini agar bisa digunakan
    public function callback(Request $request, FonnteService $fonnte) 
    {
        try {
            $callback = $request->all();
            Log::info("Callback Midtrans Masuk: ", $callback);

            $orderIdFull = $callback['order_id']; 
            $orderParts = explode('-', $orderIdFull); 
            $orderId = $orderParts[0]; 

            // Ambil data order beserta usernya
            $order = Order::with('user')->findOrFail($orderId);
            $status = $callback['transaction_status'];

            if ($status == 'settlement' || $status == 'capture') {
                
                // 1. LOGIKA PEMBAYARAN DP
                if ($order->payment_status == 'unpaid' && $order->payment_step == 'dp') {
                    $order->update([
                        'payment_status' => 'paid',
                        'payment_step' => 'full',
                        'snap_token' => null        
                    ]);

                    // --- NOTIFIKASI KE ADMIN VIA WA (OTOMATIS) ---
                    $admins = User::where('role', 'admin')->get();
                    $pesanAdmin = "✅ *DP LUNAS! (ORDER #{$order->id})* ✅\n\nPelanggan *{$order->user->name}* baru saja melunasi DP.\n\nAdmin, silakan segera lakukan *PLOTTING TEKNISI* agar pesanan bisa diproses!";
                    
                    foreach ($admins as $admin) {
                        if (!empty($admin->phone)) {
                            $fonnte->sendMessage($admin->phone, $pesanAdmin);
                        }
                    }
                    Log::info("Order #{$orderId} Lunas DP & Notif Admin Terkirim! ✅");
                } 
                
                // 2. LOGIKA PEMBAYARAN PELUNASAN
                elseif ($order->status == 'completed' && $order->payment_status == 'unpaid') {
                    $order->update([
                        'payment_status' => 'paid',
                        'snap_token' => null
                    ]);
                    Log::info("Order #{$orderId} Lunas TOTAL! 💰");
                }
            }

            return response()->json(['message' => 'Success'], 200);

        } catch (\Exception $e) {
            Log::error("Error Callback: " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}