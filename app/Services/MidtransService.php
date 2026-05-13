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
        // 1. Tentukan nominal berdasarkan tahapan (DP atau Pelunasan)
        $amountToPay = ($order->payment_step == 'dp') 
                        ? (int) $order->dp_amount 
                        : (int) $order->remaining_balance;

        // 2. Tentukan nama item agar struk Midtrans jelas
        $itemName = ($order->payment_step == 'dp')
                        ? 'DP 50% Layanan AC (' . $order->quantity . ' Unit)'
                        : 'Pelunasan Layanan AC (' . $order->quantity . ' Unit)';

        if ($amountToPay <= 0) {
            $amountToPay = (int) ($order->total_price * 0.5);
        }

        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . $order->id . '-' . time(), 
                'gross_amount' => (int) $amountToPay, // Pastikan di-cast ke (int)
            ], 
            
            'item_details' => [
                [
                    // ID item dinamis (DP-1 atau FULL-1)
                    'id'       => strtoupper($order->payment_step) . '-' . $order->id, 
                    // GANTI INI: Gunakan variabel $amountToPay
                    'price'    => $amountToPay, 
                    'quantity' => 1, 
                    // GANTI INI: Gunakan variabel $itemName
                    'name'     => $itemName 
                ]
            ],
            'customer_details' => [
                'first_name' => $order->user->name,
                'email'      => $order->user->email,
                'phone'      => $order->user->phone,
            ],
        ];

        return \Midtrans\Snap::getSnapToken($params);
    }
}