<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order; 
use App\Models\User; 
use App\Services\FonnteService; 
use Illuminate\Support\Facades\Log; 

class PaymentCallbackController extends Controller
{
    public function callback(Request $request, FonnteService $fonnte) 
    {
        try {
            $callback = $request->all();
            Log::info("Callback Midtrans Masuk: ", $callback);

            $orderIdFull = $callback['order_id']; // Contoh: "ORDER-2-1778745783"
            $orderParts = explode('-', $orderIdFull); 
            
            // PASTIKAN ID TIDAK CACAT SEBELUM DIPROSES
            if (!isset($orderParts[1])) {
                Log::error("Format Order ID salah: " . $orderIdFull);
                return response()->json(['message' => 'Format ID Salah'], 400);
            }

            // PERBAIKAN: Ambil index ke-1 untuk mendapatkan ANGKA ID aslinya
            $orderId = $orderParts[1]; 

            // Ambil data order beserta usernya
            $order = Order::with('user')->findOrFail($orderId);
            $status = $callback['transaction_status'];

            // KEAMANAN TAMBAHAN: Verifikasi Signature Key Midtrans
            $serverKey = config('services.midtrans.server_key');
            $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

            if ($hashed == $request->signature_key) {
                if ($status == 'settlement' || $status == 'capture') {
                    
                    // --- KONDISI 1: PEMBAYARAN DP ---
                    if ($order->payment_status == 'unpaid' && $order->payment_step == 'dp') {
                        $order->update([
                            'payment_status' => 'paid',
                            // payment_step TETAP 'dp', jangan diubah 'full' dulu
                            'snap_token' => null        
                        ]);

                        $pesanAdmin = "✅ *DP TELAH DIBAYAR!* ✅\n\nOrder #{$order->id}\nPelanggan: *{$order->user->name}*\n\nAdmin, silakan cek dashboard untuk melakukan *PLOTTING TEKNISI*.";
                        $this->sendToAdmins($fonnte, $pesanAdmin);
                    } 
                    
                    // --- KONDISI 2: PEMBAYARAN PELUNASAN SISA 50% ---
                    elseif ($order->status == 'completed' && $order->payment_status == 'unpaid' && $order->payment_step == 'full') {
                        $order->update([
                            'payment_status' => 'paid',
                            'snap_token' => null
                        ]);

                        // Notif ke Admin
                        $pesanAdmin = "💰 *PELUNASAN BERHASIL!* 💰\n\nOrder #{$order->id} ({$order->user->name}) telah melunasi sisa pembayaran.\nStatus pesanan: *LUNAS TOTAL*.";
                        $this->sendToAdmins($fonnte, $pesanAdmin);

                        // Notif ke Pelanggan (Terima Kasih)
                        if ($order->user && !empty($order->user->phone)) {
                            $pesanCustomer = "🎉 *PEMBAYARAN BERHASIL!* 🎉\n\nHalo *{$order->user->name}*,\n\nTerima kasih, pelunasan untuk Order #{$order->id} telah kami terima.\n\n*Pesanan Anda kini telah LUNAS TOTAL.*\nTerima kasih telah menggunakan jasa CV. WIDI ELEKTRICAL. Semoga AC Anda tetap dingin! ❄️✨";
                            $fonnte->sendMessage($order->user->phone, $pesanCustomer);
                        }
                    }
                }
            } else {
                Log::error("Signature Key Midtrans Tidak Valid untuk Order: " . $orderIdFull);
                return response()->json(['message' => 'Invalid Signature'], 403);
            }

            return response()->json(['message' => 'Success'], 200);

        } catch (\Exception $e) {
            Log::error("Error Callback: " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // Helper function kirim WA ke Admin
    private function sendToAdmins($fonnte, $message) {
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            if (!empty($admin->phone)) {
                $fonnte->sendMessage($admin->phone, $message);
            }
        }
    }
}