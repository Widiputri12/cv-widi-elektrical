<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function getSnapToken($order)
    {
        // LOGIKA REVISI DOSEN: Cek apakah bayar DP atau Pelunasan
        // Jika payment_step adalah 'dp', maka tagih nominal DP (50%)
        // Jika payment_step adalah 'full', maka tagih sisa pembayaran (50%)
        
        $amountToPay = ($order->payment_step == 'dp') 
                        ? $order->dp_amount 
                        : $order->remaining_balance;

        $params = [
            'transaction_details' => [
                // Format ID tetap angka-timestamp agar tidak bentrok di Midtrans
                'order_id' => $order->id . '-' . time(), 
                'gross_amount' => (int) $amountToPay,
            ],
            'customer_details' => [
                'first_name' => $order->user->name,
                'email' => $order->user->email,
                'phone' => $order->user->phone,
            ],
            // Tambahkan item_details agar di struk Midtrans terlihat jelas ini pembayaran apa
            'item_details' => [
                [
                    'id' => $order->id,
                    'price' => (int) $amountToPay,
                    'quantity' => 1,
                    'name' => ($order->payment_step == 'dp') ? "DP 50% Order #{$order->id}" : "Pelunasan Order #{$order->id}",
                ]
            ],
        ];

        return Snap::getSnapToken($params);
    }
}