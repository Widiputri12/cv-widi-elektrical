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

            $orderIdFull = $callback['order_id']; // Contoh: "1-1774900440"
            $orderParts = explode('-', $orderIdFull);
            
            // Ambil ID asli (angka pertama sebelum tanda strip)
            $orderId = $orderParts[0]; 
            
            Log::info("Mencari Order ID: " . $orderId);
            $order = Order::find($orderId);

            if (!$order) {
                Log::error("Order #{$orderId} tidak ditemukan!");
                return response()->json(['message' => 'Not Found'], 404);
            }

            $status = $callback['transaction_status'];

            // Cek status dari Midtrans
            if ($status == 'settlement' || $status == 'capture') {
                $order->update(['payment_status' => 'paid']);
                Log::info("HASIL: Order #{$orderId} SUKSES (PAID) ✅");
            }

            return response()->json(['message' => 'Success']);

        } catch (\Exception $e) {
            Log::error('Kritis Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }
}