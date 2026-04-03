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
                
                // --- KONDISI 1: PEMBAYARAN DP ---
                if ($order->payment_status == 'unpaid' && $order->payment_step == 'dp') {
                    $order->update([
                        'payment_status' => 'paid',
                        'payment_step' => 'full',
                        'snap_token' => null        
                    ]);

                    $pesanAdmin = "✅ *DP TELAH DIBAYAR!* ✅\n\nOrder #{$order->id}\nPelanggan: *{$order->user->name}*\n\nAdmin, silakan cek dashboard untuk melakukan *PLOTTING TEKNISI*.";
                    $this->sendToAdmins($fonnte, $pesanAdmin);
                } 
                
                // --- KONDISI 2: PEMBAYARAN PELUNASAN ---
                elseif ($order->status == 'completed' && $order->payment_status == 'unpaid') {
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

            return response()->json(['message' => 'Success'], 200);

        } catch (\Exception $e) {
            Log::error("Error Callback: " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // Tambahkan helper function ini di bawah class (agar kode rapi)
    private function sendToAdmins($fonnte, $message) {
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            if (!empty($admin->phone)) {
                $fonnte->sendMessage($admin->phone, $message);
            }
        }
    }
}

