<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    public function sendMessage($target, $message)
    {
        // 1. FORMAT NOMOR HP
        $target = preg_replace('/[^0-9]/', '', $target);
        
        if (substr($target, 0, 2) == '08') {
            $target = '62' . substr($target, 1);
        }

        // 2. AMBIL TOKEN
        $token = config('services.fonnte.token');

        if (!$token) {
            Log::error('Fonnte Token belum disetting di .env');
            return false;
        }

        // 3. KIRIM PESAN LEWAT API FONNTE
        try {
            // 👉 INI MANTRANYA: Memberi tahu VS Code bahwa ini adalah class Response Laravel
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62',
            ]);

            $result = $response->json(); 
            
            if (isset($result['status']) && !$result['status']) {
                Log::error('Fonnte Error: ' . ($result['reason'] ?? 'Unknown error'));
            }

            return $result;

        } catch (\Exception $e) {
            Log::error('Fonnte Exception: ' . $e->getMessage());
            return false;
        }
    }
}