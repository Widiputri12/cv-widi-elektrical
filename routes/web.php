<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\HomeController; 
use App\Http\Controllers\OrderController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\PaymentCallbackController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Artisan;

// --- 1. PUBLIC & GUEST ACCESS ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/galeri', [GalleryController::class, 'index'])->name('gallery.index');


Route::post('/api/payment/callback', [PaymentCallbackController::class, 'callback']);

// --- 2. AUTHENTICATED ACCESS ---
Route::middleware(['auth'])->group(function () {

    // Gerbang utama untuk melihat ketersediaan teknisi dan pesanan
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['verified'])
        ->name('dashboard');

    // --- PROFILE MANAGEMENT ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- CUSTOMER SERVICE  ---
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    // --- UPLOAD GALERI KERJA ---
    Route::get('/galeri/upload', [GalleryController::class, 'create'])->name('gallery.create');
    Route::post('/galeri', [GalleryController::class, 'store'])->name('gallery.store');

    // --- TEKNISI NAVIGASI & PENYELESAIAN ---
    // finishForm: Menampilkan peta rute dari Geolocation teknisi ke pelanggan
    Route::get('/orders/{id}/finish', [OrderController::class, 'finishForm'])->name('technician.orders.finish');
    Route::put('/orders/{id}/finish', [OrderController::class, 'updateFinish'])->name('technician.orders.updateFinish');
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancelByCustomer'])->name('orders.cancel');
    Route::put('/admin/orders/{id}/cancel', [OrderController::class, 'cancelByAdmin'])->name('admin.orders.cancel');

    Route::resource('services', ServiceController::class);


    });

// --- 3. ADMIN MANAGEMENT ---
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('technicians', TechnicianController::class);

    // KELOLA ORDER: Admin memantau detail koordinat pesanan
    Route::get('/order/{id}', [OrderController::class, 'show'])->name('orders.show');
    
    // ASSIGN TECHNICIAN: navigasi logic memicu status is_busy menjadi 1
    Route::put('/order/{id}/assign', [OrderController::class, 'assignTechnician'])->name('orders.assign');

    // KELOLA GALERI (VERIFIKASI HASIL KERJA)
    Route::get('/galleries', [GalleryController::class, 'adminIndex'])->name('galleries.index');
    Route::put('/galleries/{id}/approve', [GalleryController::class, 'approve'])->name('galleries.approve');
    Route::delete('/galleries/{id}', [GalleryController::class, 'destroy'])->name('galleries.destroy');

    // --- KELOLA LAPORAN ---
    Route::get('laporan', [OrderController::class, 'laporan'])->name('laporan.index'); 
 });

Route::get('/run-scheduler-widi-secret-123', function () {
    Artisan::call('schedule:run');
    return "Scheduler executed!";
});


// --- 4. SYSTEM TOOLS ---
Route::get('/reset-pending', function() {
    \App\Models\Gallery::query()->update(['status' => 'pending']);
    return "<h1>Sip! Semua foto sudah di-reset jadi PENDING.</h1>";
});

require __DIR__.'/auth.php';