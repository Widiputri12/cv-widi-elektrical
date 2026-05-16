<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Services\FonnteService;
use Carbon\Carbon;

class RemindTechnicians extends Command
{
    // Nama perintah yang akan dipanggil
    protected $signature = 'remind:technicians';
    protected $description = 'Kirim pengingat WA ke teknisi H-1 dan H-3 Jam';

    public function handle(FonnteService $fonnte)
    {
        $now = Carbon::now('Asia/Jakarta');
        $timezone = 'Asia/Jakarta';

        $countH1 = 0;
        // --- 1. LOGIKA H-1 HARI ---
        // Hanya eksekusi kirim pesan H-1 pada jam 16:00 (4 Sore) 
        // untuk mencegah spam jika cron berjalan setiap jam
        if ($now->format('H') == '16') {
            $tomorrow = Carbon::tomorrow($timezone)->toDateString();
            $ordersH1 = Order::where('booking_date', $tomorrow)
                            ->where('status', 'confirmed')
                            ->get();

            foreach ($ordersH1 as $order) {
                foreach ($order->technicians as $tech) {
                    $pesan = "⏰ *REMINDER H-1* ⏰\n\nHalo {$tech->name}, besok Anda ada jadwal servis:\n📍 Lokasi: {$order->address_detail}\n🕒 Jam: {$order->booking_time} WIB\n\nSiapkan peralatan Anda!";
                    $fonnte->sendMessage($tech->phone, $pesan);
                    $countH1++;
                }
            }
        }
        $countH3 = 0;
        // --- 2. LOGIKA H-3 JAM ---
        // Mencari order hari ini yang jam pengerjaannya antara 2 sampai 3 jam lagi dari sekarang
        // Ini untuk mencegah spam jika cron dijalankan setiap jam
        $twoHoursLater = Carbon::now($timezone)->addHours(2)->format('H:i');
        $threeHoursLater = Carbon::now($timezone)->addHours(3)->format('H:i');
        $today = Carbon::today($timezone)->toDateString();
        
        $ordersH3 = Order::where('booking_date', $today)
                        ->where('booking_time', '<=', $threeHoursLater)
                        ->where('booking_time', '>', $twoHoursLater)
                        ->where('status', 'confirmed')
                        ->get();

        foreach ($ordersH3 as $order) {
            foreach ($order->technicians as $tech) {
                $pesan = "⚡ *REMINDER 3 JAM LAGI* ⚡\n\nHalo {$tech->name}, 3 jam lagi Anda harus di lokasi:\n📍 Pelanggan: {$order->user->name}\n🏠 Alamat: {$order->address_detail}\n🕒 Waktu: {$order->booking_time} WIB\n\nSegera meluncur!";
                $fonnte->sendMessage($tech->phone, $pesan);
                $countH3++;
            }
        }

        $this->info("Reminders executed successfully!\nTotal H-1 sent: {$countH1}\nTotal H-3 sent: {$countH3}");
    }
}