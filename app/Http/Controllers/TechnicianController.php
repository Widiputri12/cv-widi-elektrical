<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TechnicianController extends Controller
{
    // 1. READ: Menampilkan daftar teknisi
    public function index()
    {
        $technicians = User::where('role', 'technician')->latest()->get();
        return view('admin.technicians.index', compact('technicians'));
    }

    // 2. CREATE: Menampilkan form tambah teknisi
    public function create()
    {
        return view('admin.technicians.create');
    }

    // 3. STORE: Menyimpan data teknisi baru ke database
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
            'role' => 'technician',
            'is_busy' => 0, 
        ]);

        return redirect()->route('admin.technicians.index')->with('success', 'Personel baru berhasil ditambahkan!');
    }

    // 4. EDIT: Menampilkan form ubah data
    public function edit($id)
    {
        $technician = User::where('role', 'technician')->findOrFail($id);
        return view('admin.technicians.edit', compact('technician'));
    }

    // 5. UPDATE: Menyimpan perubahan data
    public function update(Request $request, $id)
    {
        $technician = User::where('role', 'technician')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|string|max:15',
            'password' => 'nullable|string|min:8',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $technician->update($data);

        return redirect()->route('admin.technicians.index')->with('success', 'Data personel berhasil diperbarui!');
    }

    // 6. DESTROY: Menghapus teknisi
    public function destroy($id)
    {
        $technician = User::where('role', 'technician')->findOrFail($id);
        $technician->delete();
        
        return redirect()->route('admin.technicians.index')->with('success', 'Data personel berhasil dihapus!');
    }
}