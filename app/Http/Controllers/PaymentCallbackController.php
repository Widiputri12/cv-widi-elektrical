<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order; 
use Illuminate\Support\Facades\Log; // Sangat penting untuk cek error

class PaymentCallbackController extends Controller
{
    public function callback(Request $request)
    {
        try {
            $callback = $request->all();
            
            // Catat di log agar Princess bisa intip kalau ada masalah
            Log::info("Callback Midtrans Masuk: ", $callback);

            $orderIdFull = $callback['order_id']; // "1-dp-1775230196"
            $orderParts = explode('-', $orderIdFull); 
            $orderId = $orderParts[0]; 

            // Gunakan findOrFail agar kalau ID-nya ngaco, dia langsung stop
            $order = Order::findOrFail($orderId);

            $status = $callback['transaction_status'];

            if ($status == 'settlement' || $status == 'capture') {
                
                // CEK APAKAH INI PEMBAYARAN DP ATAU PELUNASAN
                // Jika pesanan masih 'pending' atau 'unpaid', berarti ini DP
                if ($order->payment_status == 'unpaid' && $order->payment_step == 'dp') {
                    $order->update([
                        'payment_status' => 'paid', // Status jadi Lunas DP
                        'payment_step' => 'full',   // Geser ke tahap Pelunasan
                        'snap_token' => null        
                    ]);
                    Log::info("Order #{$orderId} Berhasil Lunas DP! ✅");
                } 
                // Jika statusnya sudah 'completed', berarti ini PELUNASAN
                elseif ($order->status == 'completed') {
                    $order->update([
                        'payment_status' => 'paid', // Lunas Total
                        'snap_token' => null
                    ]);
                    Log::info("Order #{$orderId} Berhasil Lunas TOTAL! 💰");
                }
            }

            // WAJIB kasih jawaban 200 ke Midtrans agar email error berhenti
            return response()->json(['message' => 'Success'], 200);

        } catch (\Exception $e) {
            Log::error("Error Callback: " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}