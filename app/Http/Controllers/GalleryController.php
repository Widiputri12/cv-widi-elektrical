<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class GalleryController extends Controller
{
    // ==========================================
    // 1. HALAMAN PUBLIK (Hanya Foto Approved)
    // ==========================================
    public function index()
    {
        // Revisi Dosen: Filter hanya yang sudah disetujui admin
        $galleries = Gallery::where('status', 'approved')->latest()->get();
        return view('gallery.index', compact('galleries'));
    }

    // ==========================================
    // 2. HALAMAN ADMIN (POS SATPAM)
    // ==========================================
    public function adminIndex()
    {
        // Tampilkan semua (Pending di atas, baru Approved)
        $galleries = Gallery::orderByRaw("FIELD(status, 'pending') DESC")
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('admin.galleries.index', compact('galleries'));
    }

    // ==========================================
    // 3. FUNGSI APPROVE (TOMBOL CENTANG)
    // ==========================================
    public function approve($id)
    {
        $gallery = Gallery::findOrFail($id);
        
        $gallery->update([
            'status' => 'approved'
        ]);

        return redirect()->back()->with('success', 'Foto berhasil disetujui dan tayang di publik!');
    }

    // ==========================================
    // 4. FUNGSI HAPUS (TOMBOL SAMPAH)
    // ==========================================
    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);

        // Hapus file fisik di folder storage biar gak menuhin memori
        if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }

        $gallery->delete();

        return redirect()->back()->with('success', 'Foto dihapus.');
    }

    // ==========================================
    // 5. UPLOAD MANUAL (Admin/Teknisi via Web)
    // ==========================================
    public function create()
    {
        return view('gallery.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Maks 2MB
        ]);

        if ($request->file('image')) {
            $imagePath = $request->file('image')->store('gallery-images', 'public');
        }

        // Cek: Kalau yang upload Admin, langsung Approved. Kalau bukan, Pending.
        // Asumsi: Admin login punya role 'admin'
        $status = (Auth::check() && Auth::user()->role === 'admin') ? 'approved' : 'pending';

        Gallery::create([
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $imagePath,
            'status' => $status, // Set status otomatis
        ]);

        return redirect()->route('gallery.index')->with('success', 'Foto berhasil ditambahkan!');
    }
}