<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TechnicianController extends Controller
{
    // Menampilkan Daftar Teknisi
    public function index()
    {
        $technicians = User::where('role', 'technician')->latest()->get();
        return view('technicians.index', compact('technicians'));    }

    // Proses Simpan Teknisi Baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'technician', // Set otomatis sebagai teknisi
            'is_busy' => 0,        // Default siap sedia
        ]);

        return back()->with('success', 'Teknisi baru berhasil ditambahkan!');
    }

    // Hapus Teknisi
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return back()->with('success', 'Data teknisi berhasil dihapus.');
    }
}