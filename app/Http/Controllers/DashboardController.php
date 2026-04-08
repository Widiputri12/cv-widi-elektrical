<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Service;

class DashboardController extends Controller
{
    public function index()
        {
            // 👇 Tambahkan baris komentar "ajaib" ini persis di atas variabel $user
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // 1. DASHBOARD ADMIN
            if ($user->role === 'admin') {
            // ✅ UBAH get() MENJADI paginate(10)
            $orders = Order::with(['user', 'services', 'technicians'])->latest()->paginate(10);
            return view('admin.dashboard', compact('orders')); 
        }

            // 2. DASHBOARD TEKNISI
            if ($user->role === 'technician') {
                // ✅ Garis merahnya pasti akan hilang setelah ditambah komentar di atas!
                $assignedOrders = $user->assignedOrders()->with(['user', 'services'])->latest()->get();
                return view('technician.dashboard', compact('assignedOrders')); 
            }

            // 3. DASHBOARD PELANGGAN (Member)
            $services = Service::all(); 
            $myOrders = Order::with('services')
                        ->where('user_id', $user->id)
                        ->latest()
                        ->get();
            return view('dashboard', compact('services', 'myOrders'));
        }
}