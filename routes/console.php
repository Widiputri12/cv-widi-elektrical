<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule; // Tambahkan pemanggilan Schedule ini

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// --- DAFTARKAN JADWAL OTOMATIS CV WIDI DI SINI ---
// Jalankan pengingat teknisi setiap jam
Schedule::command('remind:technicians')->hourly();