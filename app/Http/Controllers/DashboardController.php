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
        $user = Auth::user();

        // 1. DASHBOARD ADMIN
        if ($user->role === 'admin') {
            $orders = Order::with(['user', 'services'])->latest()->get();
            return view('admin.dashboard', compact('orders')); 
        }

        // 2. DASHBOARD TEKNISI
        if ($user->role === 'technician') {
            $assignedOrders = Order::with(['user', 'services'])
                                ->where('technician_id', $user->id)
                                ->latest()
                                ->get();
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