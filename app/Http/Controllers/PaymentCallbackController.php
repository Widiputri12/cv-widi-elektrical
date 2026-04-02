<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order; 
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
            
            // Ambil ID asli dari database
            $orderId = $orderParts[0]; 
            
            Log::info("Mencari Order ID: " . $orderId);
            $order = Order::find($orderId);

            if (!$order) {
                Log::error("Order #{$orderId} tidak ditemukan!");
                return response()->json(['message' => 'Not Found'], 404);
            }

            $status = $callback['transaction_status'];

            // LOGIKA PEMBAYARAN BERHASIL (Settlement/Capture)
            if ($status == 'settlement' || $status == 'capture') {
                
                // 1. Jika ini adalah pembayaran Tahap DP
                if ($order->payment_step == 'dp') {
                    $order->update([
                        'payment_status' => 'unpaid', // Tetap unpaid karena baru bayar DP
                        'payment_step' => 'full',   // Ganti tahap ke Pelunasan (Full)
                        'snap_token' => null        // Hapus token DP agar nanti bisa generate token baru untuk pelunasan
                    ]);
                    Log::info("HASIL: DP 50% Order #{$orderId} BERHASIL. Menunggu Pelunasan. ✅");
                } 
                
                // 2. Jika ini adalah pembayaran Tahap Pelunasan (Full)
                else {
                    $order->update([
                        'payment_status' => 'paid', // Baru di sini status jadi LUNAS (PAID)
                    ]);
                    Log::info("HASIL: Pelunasan Order #{$orderId} BERHASIL. STATUS: LUNAS TOTAL ✅✅");
                }
            }

            // Tambahan: Jika pembayaran Expired atau Cancel
            elseif (in_array($status, ['expire', 'cancel', 'deny'])) {
                $order->update(['payment_status' => 'failed']);
                Log::error("HASIL: Pembayaran Order #{$orderId} Gagal/Expired ❌");
            }

            return response()->json(['message' => 'Success']);

        } catch (\Exception $e) {
            Log::error('Kritis Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }
}