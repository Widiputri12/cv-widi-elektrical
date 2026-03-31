<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        // Pastikan nama di config/services.php atau .env sudah pas
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function getSnapToken($order)
    {
        $params = [
            'transaction_details' => [
                // Kuncinya di sini: hapus string "WIDI-"
                'order_id' => $order->id . '-' . time(), 
                'gross_amount' => (int) $order->total_price,
            ],
            // ... sisa kode lainnya sama
        ];
        return Snap::getSnapToken($params);
    }
}