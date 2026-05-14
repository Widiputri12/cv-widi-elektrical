<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Service;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // ----------------------------------------------------
        // 1. DASHBOARD ADMIN (Tabel Data Order Masuk)
        // ----------------------------------------------------
        if ($user->role === 'admin') {
            $query = Order::with(['user', 'services', 'technicians']);

            // INILAH MESIN PENYARING TANGGALNYA
            if ($request->start_date && $request->end_date) {
                $query->whereDate('booking_date', '>=', $request->start_date)
                      ->whereDate('booking_date', '<=', $request->end_date);
            }

            $orders = $query->latest()->paginate(10);
            
            // Jaga agar saat pindah halaman (pagination), filternya tidak hilang
            $orders->appends($request->all()); 

            return view('admin.dashboard', compact('orders')); 
        }

        // ----------------------------------------------------
        // 2. DASHBOARD TEKNISI (Tabel Tugas Aktif)
        // ----------------------------------------------------
        if ($user->role === 'technician') {
            $query = $user->assignedOrders()->with(['user', 'services']);

            // INILAH MESIN PENYARING TANGGALNYA
            if ($request->start_date && $request->end_date) {
                $query->whereDate('booking_date', '>=', $request->start_date)
                      ->whereDate('booking_date', '<=', $request->end_date);
            }

            $assignedOrders = $query->latest()->get();
            return view('technician.dashboard', compact('assignedOrders')); 
        }

        // ----------------------------------------------------
        // 3. DASHBOARD PELANGGAN
        // ----------------------------------------------------
        $services = Service::all(); 
        $myOrders = Order::with('services')
                    ->where('user_id', $user->id)
                    ->latest()
                    ->get();
                    
        return view('dashboard', compact('services', 'myOrders'));
    }
}