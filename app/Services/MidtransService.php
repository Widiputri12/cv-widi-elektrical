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
        // 1. Tentukan nominal berdasarkan tahapan (DP 50% atau Pelunasan 50%)
        // Kita gunakan (int) untuk memastikan tidak ada angka desimal yang bikin Midtrans Error
        $amountToPay = ($order->payment_step == 'dp') 
                        ? (int) $order->dp_amount 
                        : (int) $order->remaining_balance;

        // 2. Jika karena suatu alasan nominalnya 0 (error database), 
        // kita kasih pengaman agar tidak error saat panggil API Midtrans
        if ($amountToPay <= 0) {
            $amountToPay = (int) ($order->total_price * 0.5);
        }

        $params = [
            'transaction_details' => [
                // Order ID unik: ID-Step-Timestamp (Contoh: 12-dp-17123456)
                'order_id' => $order->id . '-' . $order->payment_step . '-' . time(), 
                'gross_amount' => $amountToPay,
            ],
            'customer_details' => [
                'first_name' => $order->user->name,
                'email' => $order->user->email,
                'phone' => $order->user->phone,
            ],
            'item_details' => [
                [
                    'id' => 'ITEM-' . $order->id,
                    'price' => $amountToPay,
                    'quantity' => 1,
                    'name' => ($order->payment_step == 'dp') 
                                ? "DP 50% - Order #{$order->id}" 
                                : "Pelunasan - Order #{$order->id}",
                ]
            ],
            // Opsional: Biar tampilan Midtrans Princess lebih cantik dan profesional
            'enabled_payments' => [
                'credit_card', 'gopay', 'shopeepay', 'permata_va', 
                'bca_va', 'bni_va', 'bri_va', 'other_va'
            ],
        ];

        return Snap::getSnapToken($params);
    }
}