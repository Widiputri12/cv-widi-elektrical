<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // <-- WAJIB ADA AGAR BISA MENERIMA FILTER
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Service;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request) // <-- Tambahkan parameter (Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // --- 1. DASHBOARD ADMIN ---
        if ($user->role === 'admin') {
            $query = Order::with(['user', 'services', 'technicians']);

            // Logika Filter Tanggal Pengerjaan (booking_date)
            if ($request->start_date && $request->end_date) {
                $query->whereDate('booking_date', '>=', $request->start_date)
                      ->whereDate('booking_date', '<=', $request->end_date);
            }

            $orders = $query->latest()->paginate(10);
            
            // Pertahankan filter saat klik halaman 2, 3, dst (Pagination)
            $orders->appends($request->all()); 

            return view('dashboard', compact('orders')); // Pastikan nama view-nya sesuai dengan punyamu
        }
        
        // --- 2. DASHBOARD TEKNISI ---
        if ($user->role === 'technician') {
            // Tarik relasinya dulu
            $query = $user->assignedOrders()->with(['user', 'services']);

            // Jika admin/teknisi mengisi filter tanggal
            if ($request->start_date && $request->end_date) {
                $query->whereDate('booking_date', '>=', $request->start_date)
                      ->whereDate('booking_date', '<=', $request->end_date);
            }

            // Ambil datanya
            $assignedOrders = $query->latest()->get();
            return view('technician.dashboard', compact('assignedOrders')); 
        }
}
}