<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\HomeController; 
use App\Http\Controllers\OrderController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\PaymentCallbackController;
use App\Http\Controllers\TechnicianController;


// --- 1. PUBLIC & GUEST ACCESS ---
// Memberikan akses informasi umum sebelum user menggunakan layanan LBS
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/galeri', [GalleryController::class, 'index'])->name('gallery.index');

Route::post('/payment/callback', [PaymentCallbackController::class, 'callback']);

// --- 2. AUTHENTICATED ACCESS (LBS ECOSYSTEM) ---
Route::middleware(['auth'])->group(function () {

    // --- DASHBOARD (Sistem Deteksi Role & Status is_busy) ---
    // Gerbang utama LBS untuk melihat ketersediaan teknisi dan pesanan
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['verified'])
        ->name('dashboard');

    // --- PROFILE MANAGEMENT ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- CUSTOMER SERVICE (LBS: PENENTUAN LOKASI) ---
    // Di sinilah titik awal Geolocation dicatat saat pelanggan memesan servis
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    // --- UPLOAD GALERI KERJA ---
    Route::get('/galeri/upload', [GalleryController::class, 'create'])->name('gallery.create');
    Route::post('/galeri', [GalleryController::class, 'store'])->name('gallery.store');

    // --- TEKNISI: LBS NAVIGASI & PENYELESAIAN ---
    // finishForm: Menampilkan peta rute dari Geolocation teknisi ke pelanggan
    Route::get('/orders/{id}/finish', [OrderController::class, 'finishForm'])->name('technician.orders.finish');
    
    // updateFinish: Mengupdate status is_busy menjadi 0 (Tersedia Kembali)
    Route::put('/orders/{id}/finish', [OrderController::class, 'updateFinish'])->name('technician.orders.updateFinish');

    // Rute untuk Princess meng-cancel pesanannya sendiri
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancelByCustomer'])->name('orders.cancel');

    // Rute untuk Admin meng-cancel (dengan catatan)
    Route::put('/admin/orders/{id}/cancel', [OrderController::class, 'cancelByAdmin'])->name('admin.orders.cancel');
});

// --- 3. ADMIN MANAGEMENT (LBS MONITORING) ---
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('technicians', TechnicianController::class);

    // KELOLA ORDER: Admin memantau detail koordinat pesanan
    Route::get('/order/{id}', [OrderController::class, 'show'])->name('orders.show');
    
    // ASSIGN TECHNICIAN: LBS logic memicu status is_busy menjadi 1
    Route::put('/order/{id}/assign', [OrderController::class, 'assignTechnician'])->name('orders.assign');

    // KELOLA GALERI (VERIFIKASI HASIL KERJA)
    Route::get('/galleries', [GalleryController::class, 'adminIndex'])->name('galleries.index');
    Route::put('/galleries/{id}/approve', [GalleryController::class, 'approve'])->name('galleries.approve');
    Route::delete('/galleries/{id}', [GalleryController::class, 'destroy'])->name('galleries.destroy');

    // --- KELOLA LAPORAN ---
    Route::get('/laporan', [\App\Http\Controllers\OrderController::class, 'laporan'])->name('laporan.index');
});



// --- 4. SYSTEM TOOLS ---
Route::get('/reset-pending', function() {
    \App\Models\Gallery::query()->update(['status' => 'pending']);
    return "<h1>Sip! Semua foto sudah di-reset jadi PENDING.</h1>";
});

require __DIR__.'/auth.php';