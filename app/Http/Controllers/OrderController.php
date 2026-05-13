<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Order;
use App\Models\Service;
use App\Models\Gallery;
use App\Services\FonnteService;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Log; 

class OrderController extends Controller
{
    /**
     * Customer membuat pesanan baru & Langsung Generate Tagihan DP
     */
    public function store(Request $request, FonnteService $fonnte, MidtransService $midtrans) 
    {
        $user = Auth::user();

        $request->validate([
            'phone' => ['required', 'numeric', 'in:' . $user->phone],
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'exists:services,id',
            // Validasi input service_qty array
            'service_qty' => 'required|array',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required',
            'address_detail' => 'required|string|min:10',
            'latitude' => 'required',
            'longitude' => 'required',
        ], [
            'phone.in' => 'Nomor WhatsApp harus sesuai dengan nomor profile Anda! ('.$user->phone.')',
            'booking_date.after_or_equal' => 'Tanggal booking tidak valid atau sudah kadaluwarsa!',
            'address_detail.min' => 'Alamat harus lebih detail (minimal 10 karakter).',
            'service_ids.required' => 'Silakan pilih minimal satu layanan!',
        ]);

        // --- TAMBAHAN: LOGIKA MESIN WAKTU ---
        // (Pastikan kamu sudah menambahkan: use Carbon\Carbon; 
        // dan use Illuminate\Validation\ValidationException; di bagian paling atas file ini)
        $bookingDate = \Carbon\Carbon::parse($request->booking_date);
        
        if ($bookingDate->isToday()) {
            $bookingDateTime = \Carbon\Carbon::parse($request->booking_date . ' ' . $request->booking_time, 'Asia/Jakarta');
            
            if ($bookingDateTime->isPast()) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'booking_time' => 'Waktu pesanan untuk hari ini tidak boleh kurang dari jam saat ini.'
                ]);
            }
        }
        // -------------------------------------

        $services = Service::whereIn('id', $request->service_ids)->get();
            
            $totalPrice = 0;
            $totalAllItems = 0; // Untuk mencatat total unit keseluruhan
            $pivotData = []; // Untuk simpan ke tabel relasi
            $serviceDetailsForWa = []; // Untuk teks di WA

            foreach ($services as $service) {
                // Ambil jumlah berdasarkan ID layanannya (default 1 jika kosong)
                $qty = $request->service_qty[$service->id] ?? 1;
                
                // Harga per layanan = Harga asli x Jumlah
                $totalPrice += ($service->price * $qty);
                $totalAllItems += $qty;
                
                // Siapkan data untuk tabel pivot order_service
                $pivotData[$service->id] = ['quantity' => $qty];
                
                // Siapkan teks untuk WA Admin (Contoh: Cuci AC (3x), Tambah Freon (1x))
                $serviceDetailsForWa[] = "{$service->name} ({$qty}x)";
            }
            
            $dpAmount = round($totalPrice * 0.5);
            $remainingBalance = $totalPrice - $dpAmount;

            $order = Order::create([
                'user_id' => $user->id,
                'quantity' => $totalAllItems, // Total seluruh unit disatukan
                'booking_date' => $request->booking_date,
                'booking_time' => $request->booking_time,
                'address_detail' => $request->address_detail,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'notes' => $request->notes ?? '', // Tambahkan fallback string kosong
                'total_price' => $totalPrice,
                'dp_amount' => $dpAmount, 
                'remaining_balance' => $remainingBalance,
                'status' => 'pending',        
                'payment_status' => 'unpaid', 
                'payment_step' => 'dp', 
            ]);

        $order->services()->sync($pivotData);

        // GENERATE TOKEN DP SEKARANG (Agar tombol bayar langsung muncul)
        try {
            $snapToken = $midtrans->getSnapToken($order); 
            $order->update(['snap_token' => $snapToken]); 
            \Log::info("Snap Token DP Berhasil: " . $snapToken); 
        } catch (\Exception $e) {
            \Log::error('Gagal buat token DP: ' . $e->getMessage()); 
        }

        $serviceNames = $services->pluck('name')->implode(', ');
        $pesanAdmin = "🚨 *ORDER BARU (MENUNGGU DP)!* 🚨\n\n";
        $pesanAdmin .= "👤 Nama: {$user->name}\n";
        // --- UBAHAN: Menampilkan jumlah AC di notif WA ---
        $pesanAdmin .= "🛠️ Layanan: {$serviceNames} ({$totalAllItems} Unit)\n";
        $pesanAdmin .= "💰 Total: Rp " . number_format($totalPrice, 0, ',', '.') . "\n";
        $pesanAdmin .= "💵 DP 50%: Rp " . number_format($dpAmount, 0, ',', '.') . "\n";
        $pesanAdmin .= "📅 Jadwal: " . date('d M Y', strtotime($order->booking_date)) . " jam " . date('H:i', strtotime($order->booking_time)) . "\n\n";
        $pesanAdmin .= "Pesanan masuk sistem, menunggu pembayaran DP dari pelanggan.";

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            if (!empty($admin->phone)) { 
                $fonnte->sendMessage($admin->phone, $pesanAdmin);
            }
        }

        return redirect()->route('dashboard')->with('success', 'Order berhasil! Silakan bayar DP 50% di Riwayat Servis agar teknisi kami proses.'); 
    }

    /**
     * Admin melihat detail pesanan & plotting teknisi
     */
    public function show($id)
    {
        $order = Order::with(['user', 'services', 'technicians'])->findOrFail($id);
        $technicians = User::where('role', 'technician')->get();
        
        return view('admin.orders.show', compact('order', 'technicians'));
    }

    /**
     * Admin menugaskan teknisi 
     */
    public function assignTechnician(Request $request, $id, FonnteService $fonnte)
    {
        $request->validate([
            'technician_ids' => 'required|array|min:1',
            'technician_ids.*' => 'exists:users,id'
        ]);

        $order = Order::with('user')->findOrFail($id);
                $order->technicians()->sync($request->technician_ids);
        $order->update(['status' => 'confirmed']);
        $assignedTechs = User::whereIn('id', $request->technician_ids)->get();

        $techNames = [];

        foreach ($assignedTechs as $tech) {
            $tech->update(['is_busy' => 1]); 
            
            $techNames[] = $tech->name;

            if (!empty($tech->phone)) {
                $pesanTeknisi = "📢 *TUGAS BARU!* 📢\n\nHalo *{$tech->name}*,\n\nAnda ditugaskan untuk Order #{$order->id}.\nPelanggan: *{$order->user->name}*\nLokasi: {$order->address_detail}\n\nSegera cek dashboard teknisi untuk melihat detail pengerjaan!";
                $fonnte->sendMessage($tech->phone, $pesanTeknisi);
            }
        }

        if ($order->user && !empty($order->user->phone)) {
            $daftarTeknisi = implode(', ', $techNames);
            $pesanPelanggan = "✅ *PESANAN DIKONFIRMASI* ✅\n\nHalo *{$order->user->name}*,\n\nOrder #{$order->id} Anda telah dikonfirmasi oleh Admin.\n\n*Tim Teknisi Bertugas:* \n- {$daftarTeknisi}\n\nTeknisi kami segera meluncur ke lokasi Anda. Terima kasih!";
            $fonnte->sendMessage($order->user->phone, $pesanPelanggan);
        }

        return back()->with('success', 'Tim Teknisi berhasil ditugaskan dan notifikasi WA telah terkirim!');
    }

    /**
     * Teknisi melihat form penyelesaian tugas
     */
    public function finishForm($id)
    {
        $order = Order::with(['user', 'services'])->findOrFail($id);
        return view('technician.finish', compact('order'));
    }

    /**
     * Teknisi menyelesaikan tugas 
     */
    public function updateFinish(Request $request, $id, MidtransService $midtrans, FonnteService $fonnte) 
    {
        $request->validate([
            'image' => 'required|image|max:10240',
            'category' => 'required',
            'title' => 'required',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('proofs', 'public');
            \App\Models\Gallery::create([
                'title' => $request->title,
                'category' => $request->category,
                'description' => $request->description ?? 'Pekerjaan selesai oleh ' . \Illuminate\Support\Facades\Auth::user()->name,
                'image_path' => $imagePath,
                'status' => 'pending',
            ]);
        }

        $order = Order::with(['user', 'services'])->findOrFail($id);

        $order->update([
            'status' => 'completed',      
            'payment_status' => 'unpaid', 
            'payment_step' => 'full'       
        ]);

        foreach ($order->technicians as $tech) {
            $tech->update(['is_busy' => 0]);
        }

        if ($order->user && !empty($order->user->phone)) {
            $serviceNames = $order->services->pluck('name')->implode(', ');
            $pesanBayar = "❄️ *KERJA SELESAI!* ❄️\n\nHalo *{$order->user->name}*,\n\nServis *{$serviceNames}* telah diselesaikan oleh teknisi kami.\n\nSilakan lakukan *PELUNASAN SISA 50%* di dashboard agar pesanan dapat ditutup secara resmi.\n\nTerima kasih atas kepercayaannya!";
            $fonnte->sendMessage($order->user->phone, $pesanBayar);
        }

        try {
            sleep(1); 
            
            $snapToken = $midtrans->getSnapToken($order);
            
            $order->update(['snap_token' => $snapToken]); 
            
            \Illuminate\Support\Facades\Log::info("Snap Token Pelunasan Berhasil dibuat untuk Order #{$id}");
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal Generate Pelunasan: ' . $e->getMessage());
        }

        return redirect()->route('dashboard')->with('success', 'Laporan terkirim! Pelanggan telah dinotifikasi untuk pelunasan.');
    }

    public function cancelByCustomer($id, FonnteService $fonnte)
    {
        $order = Order::with('user')->findOrFail($id);

        if ($order->status !== 'pending') {
            return back()->with('error', 'Pesanan sudah diproses, tidak bisa dibatalkan.');
        }

        $order->update([
            'status' => 'cancelled',
            'cancel_notes' => 'Dibatalkan oleh Pelanggan'
        ]);

        $pesanAdmin = "⚠️ *PESANAN DIBATALKAN PELANGGAN* ⚠️\n\nHalo Admin, Pelanggan *{$order->user->name}* telah membatalkan pesanan #{$order->id} secara mandiri.";
        $this->sendToAdmins($fonnte, $pesanAdmin); 

        return back()->with('success', 'Pesanan berhasil Anda batalkan.');
    }

    public function cancelByAdmin(Request $request, $id, FonnteService $fonnte)
    {
        $request->validate(['cancel_notes' => 'required|string|min:5']);
        $order = Order::with('user')->findOrFail($id);

        if ($order->payment_status === 'paid') {
            return back()->with('error', 'Gagal! Pesanan yang sudah dibayar DP-nya tidak dapat dibatalkan.');
        }

        $order->update([
            'status' => 'cancelled',
            'cancel_notes' => $request->cancel_notes
        ]);

        if ($order->user && !empty($order->user->phone)) {
            $pesan = "Halo *{$order->user->name}*,\n\nMohon maaf, pesanan #{$order->id} Anda *DIBATALKAN* oleh Admin CV Widi.\n\n*Catatan Admin:* \"{$request->cancel_notes}\"\n\nSilakan hubungi kami jika ada pertanyaan.";
            $fonnte->sendMessage($order->user->phone, $pesan);
        }

        return redirect()->route('dashboard')->with('success', 'Pesanan berhasil dibatalkan dan notifikasi WA terkirim.');
    }

    /**
     * Menampilkan Halaman Laporan untuk Admin
     */
    public function laporan(Request $request)
    {
        // 1. Mulai Query dengan relasi
        $query = Order::with(['user', 'services']);

        // 2. Filter berdasarkan Tanggal Mulai
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        // 3. Filter berdasarkan Tanggal Selesai
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // 4. Filter berdasarkan Status (Completed/Cancelled)
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // 5. Eksekusi data terbaru
        $orders = $query->latest()->get();

        // 6. Hitung Total Pendapatan dari hasil filter (Hanya yang lunas/paid)
        // Jika statusnya 'cancelled', biasanya tidak dihitung ke pendapatan
        $totalPendapatan = $orders->where('payment_status', 'paid')
                                ->where('status', '!=', 'cancelled')
                                .sum('total_price');

        return view('admin.laporan.index', compact('orders', 'totalPendapatan'));
    }

    // Tambahkan helper function ini di bawah class (agar kode rapi)
    private function sendToAdmins($fonnte, $message) {
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            if (!empty($admin->phone)) {
                $fonnte->sendMessage($admin->phone, $message);
            }
        }
    }
}