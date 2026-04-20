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
            /** @var \App\Models\User $user */
            $user = Auth::user();
            if ($user->role === 'admin') {
            $orders = Order::with(['user', 'services', 'technicians'])->latest()->paginate(10);
            return view('admin.dashboard', compact('orders')); 
        }
            if ($user->role === 'technician') {
                $assignedOrders = $user->assignedOrders()->with(['user', 'services'])->latest()->get();
                return view('technician.dashboard', compact('assignedOrders')); 
            }
            $services = Service::all(); 
            $myOrders = Order::with('services')
                        ->where('user_id', $user->id)
                        ->latest()
                        ->get();
            return view('dashboard', compact('services', 'myOrders'));
        }
}