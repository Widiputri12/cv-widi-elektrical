<?php

namespace App\Http\Controllers;

use Carbon\Carbon; // <-- TAMBAHKAN INI
use App\Models\Schedule; // <-- TAMBAHKAN BARIS INI
use Illuminate\Validation\ValidationException; // <-- TAMBAHKAN JUGA INI AGAR PESAN ERRORNYA MUNCUL
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
            'service_qty' => 'required|array', // Validasi input quantity per layanan
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

        // --- LOGIKA MESIN WAKTU ---
        $bookingDate = \Carbon\Carbon::parse($request->booking_date);
        $bookingTime = $request->booking_time; // format jam (HH:mm)
        $currentTime = \Carbon\Carbon::now('Asia/Jakarta');
        $minTimeForToday = \Carbon\Carbon::now('Asia/Jakarta')->addHours(6);

        // 1. Cek Hari Minggu (Tutup)
        if ($bookingDate->isSunday()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'booking_date' => 'Mohon maaf, kami tutup pada hari Minggu. Silakan pilih jadwal Senin - Sabtu.'
            ]);
        }

        // 2. Cek Jam Operasional (08:00 - 17:00)
        if ($bookingTime < '08:00' || $bookingTime > '17:00') {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'booking_time' => 'Jam operasional kami adalah pukul 08:00 - 17:00 WIB.'
            ]);
        }

        // 3. Cek Jeda 6 Jam (Jika pesan untuk hari ini)
        if ($bookingDate->isToday()) {
            $bookingDateTime = \Carbon\Carbon::parse($request->booking_date . ' ' . $request->booking_time, 'Asia/Jakarta');
            
            if ($bookingDateTime->lt($minTimeForToday)) {
                // Jika batas 6 jam sudah melewati jam operasional (17:00)
                if ($minTimeForToday->format('H:i') > '17:00') {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'booking_date' => 'Jadwal untuk hari ini sudah penuh/melewati batas jeda 6 jam operasional. Silakan pilih jadwal besok.'
                    ]);
                }

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'booking_time' => 'Minimal pemesanan adalah 6 jam dari sekarang. Paling cepat pukul ' . $minTimeForToday->format('H:i') . ' WIB.'
                ]);
            }
        }

        // --- KALKULASI HARGA DINAMIS BERDASARKAN QTY TIAP LAYANAN ---
        $services = Service::whereIn('id', $request->service_ids)->get();
        $totalPrice = 0;
        $totalAllItems = 0;
        $pivotData = [];
        $serviceDetailsForWa = [];

        foreach ($services as $service) {
            $qty = $request->service_qty[$service->id] ?? 1;
            $totalPrice += ($service->price * $qty);
            $totalAllItems += $qty;
            $pivotData[$service->id] = ['quantity' => $qty];
            $serviceDetailsForWa[] = "{$service->name} ({$qty}x)";
        }
        
        $dpAmount = round($totalPrice * 0.5);
        $remainingBalance = $totalPrice - $dpAmount;

        $order = Order::create([
            'user_id' => $user->id,
           // 'quantity' => $totalAllItems, 
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'address_detail' => $request->address_detail,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'notes' => $request->notes ?? '', 
            'total_price' => $totalPrice,
            'dp_amount' => $dpAmount, 
            'remaining_balance' => $remainingBalance,
            'status' => 'pending',        
            'payment_status' => 'unpaid', 
            'payment_step' => 'dp', 
        ]);

        $order->services()->sync($pivotData);

        // GENERATE TOKEN DP
        try {
            $snapToken = $midtrans->getSnapToken($order); 
            $order->update(['snap_token' => $snapToken]); 
        } catch (\Exception $e) {
            Log::error('Gagal buat token DP: ' . $e->getMessage()); 
        }

        // NOTIF WA KE ADMIN
        $serviceNamesWa = implode(', ', $serviceDetailsForWa);
        $pesanAdmin = "🚨 *ORDER BARU (MENUNGGU DP)!* 🚨\n\n";
        $pesanAdmin .= "👤 Nama: {$user->name}\n";
        $pesanAdmin .= "🛠️ Layanan: {$serviceNamesWa}\n";
        $pesanAdmin .= "💰 Total: Rp " . number_format($totalPrice, 0, ',', '.') . "\n";
        $pesanAdmin .= "💵 DP 50%: Rp " . number_format($dpAmount, 0, ',', '.') . "\n";
        $pesanAdmin .= "📅 Jadwal: " . date('d M Y', strtotime($order->booking_date)) . " jam " . $order->booking_time . "\n\n";
        $pesanAdmin .= "Pesanan masuk sistem, menunggu pembayaran DP.";

        $this->sendToAdmins($fonnte, $pesanAdmin);

        return redirect()->route('dashboard')->with('success', 'Order berhasil! Silakan bayar DP 50% di Riwayat Servis.'); 
    }

    /**
     * Admin melihat detail pesanan & plotting teknisi
     */
    public function show($id)
    {
        $order = Order::with(['user', 'services', 'technicians'])->findOrFail($id);
        
        // Ambil teknisi, sekalian intip apakah mereka punya jadwal di "Tanggal Order" ini
        $technicians = User::where('role', 'technician')
            ->with(['schedules' => function ($query) use ($order) {
                // Hanya ambil jadwal yang tanggalnya SAMA dengan tanggal pesanan ini
                $query->whereDate('work_date', $order->booking_date);
            }])->get();
        
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

        // --- REVISI: Simpan data ke tabel Schedules ---
        // Hapus jadwal lama jika ada plotting ulang
        Schedule::where('order_id', $id)->delete();

        foreach ($request->technician_ids as $techId) {
            Schedule::create([
                'order_id' => $order->id,
                'technician_id' => $techId,
                'work_date' => $order->booking_date,
                'start_time' => $order->booking_time,
                'status' => 'Scheduled'
            ]);
        }

        // Kirim Notifikasi WA (Logika sama seperti sebelumnya)
        $assignedTechs = User::whereIn('id', $request->technician_ids)->get();
        $techNames = [];

        foreach ($assignedTechs as $tech) {
            $techNames[] = $tech->name;
            if (!empty($tech->phone)) {
                $pesanTeknisi = "📢 *TUGAS BARU!* 📢\n\nHalo *{$tech->name}*,\n\nAnda ditugaskan untuk Order #{$order->id}.\nPelanggan: *{$order->user->name}*\nJadwal: " . date('d M Y', strtotime($order->booking_date)) . " Jam {$order->booking_time}\nLokasi: {$order->address_detail}";
                $fonnte->sendMessage($tech->phone, $pesanTeknisi);
            }
        }

        return back()->with('success', 'Tim Teknisi berhasil ditugaskan!');
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
        $allServices = Service::all();
        $query = Order::with(['user', 'services', 'technicians']);

        // Filter Tanggal Mulai & Selesai (berdasarkan Tanggal Pengerjaan / booking_date)
        if ($request->start_date) {
            $query->whereDate('booking_date', '>=', $request->start_date);
        }
        
        if ($request->end_date) {
            $query->whereDate('booking_date', '<=', $request->end_date);
        }

        // Filter berdasarkan jenis layanan
        if ($request->service_id) {
            $query->whereHas('services', function($q) use ($request) {
                $q->where('services.id', $request->service_id);
            });
        }

        $orders = $query->latest()->get();

        // --- REVISI PERHITUNGAN OMSET LAPORAN (AKURAT) ---
        // 1. Hitung total DP dari pesanan yang baru masuk DP-nya
        $dpSum = $orders->where('payment_status', 'paid')
                        ->where('payment_step', 'dp')
                        ->where('status', '!=', 'cancelled')
                        ->sum('dp_amount');
                        
        // 2. Hitung total harga FULL dari pesanan yang sudah lunas
        $fullSum = $orders->where('payment_status', 'paid')
                          ->where('payment_step', 'full')
                          ->where('status', '!=', 'cancelled')
                          ->sum('total_price');

        // 3. Gabungkan Omset
        $totalPendapatan = $dpSum + $fullSum;

        return view('admin.laporan.index', compact('orders', 'totalPendapatan', 'allServices'));
    }

/**
     * Webhook/Callback dari Midtrans untuk Update Status Otomatis
     */
    public function midtransCallback(Request $request, FonnteService $fonnte)
    {
        // 1. Tangkap Payload dari Midtrans
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        
        // Verifikasi keamanan (Pastikan ini benar-benar dari Midtrans)
        if ($hashed == $request->signature_key) {
            
            // 2. Pecah ID Transaksi (Contoh: "ORDER-2-1778745783" -> Ambil angka "2")
            $parts = explode('-', $request->order_id);
            
            if (isset($parts[1])) {
                $realOrderId = $parts[1];
                $order = Order::with('user')->find($realOrderId);

                if ($order) {
                    // 3. Cek Status Transaksinya
                    if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                        // UPDATE DATABASE JADI LUNAS
                        $order->update(['payment_status' => 'paid']);

                        // Kirim WA Notifikasi Sukses Bayar ke Pelanggan
                        if ($order->user && !empty($order->user->phone)) {
                            $jenisBayar = $order->payment_step == 'dp' ? 'DP 50%' : 'PELUNASAN SISA TAGIHAN';
                            $pesanPelanggan = "🎉 *PEMBAYARAN BERHASIL* 🎉\n\nHalo *{$order->user->name}*,\n\nPembayaran *{$jenisBayar}* untuk Order #{$order->id} telah berhasil kami terima.\n\nTerima kasih, tim CV Widi akan segera memproses pesanan Anda.";
                            $fonnte->sendMessage($order->user->phone, $pesanPelanggan);
                        }

                        // Kirim WA ke Admin
                        $pesanAdmin = "💰 *PEMBAYARAN MASUK!* 💰\n\nOrder #{$order->id} atas nama *{$order->user->name}* telah melakukan pembayaran.\nStatus: PAID.\n\nSilakan cek dashboard untuk plotting teknisi.";
                        $this->sendToAdmins($fonnte, $pesanAdmin);
                    } 
                    elseif ($request->transaction_status == 'cancel' || $request->transaction_status == 'expire' || $request->transaction_status == 'deny') {
                        // Jika pembayaran gagal/kadaluwarsa
                        // Kita biarkan status unpaid, atau ubah status order jadi cancelled (opsional)
                    }
                }
            }
        }

        return response()->json(['status' => 'success']); // Beri tahu Midtrans kalau sistem kita sudah merespon
    }

    /**
     * Menampilkan Histori Pekerjaan Teknisi dengan Filter Tanggal
     */
    public function technicianHistory(Request $request)
    {
        $user = Auth::user();
        
        // Cari order yang pernah dikerjakan oleh teknisi yang sedang login
        $query = Order::whereHas('technicians', function($q) use ($user) {
            $q->where('users.id', $user->id);
        })->with(['user', 'services']);

        // Logika Filter Tanggal Pengerjaan
        if ($request->start_date && $request->end_date) {
            $query->whereDate('booking_date', '>=', $request->start_date)
                  ->whereDate('booking_date', '<=', $request->end_date);
        }

        // Urutkan berdasarkan tanggal pengerjaan terbaru
        $histories = $query->latest('booking_date')->get();

        return view('technician.history', compact('histories'));
    }


    // Tambahkan helper function ini di bawah class (agar kode rapi)
    private function sendToAdmins($fonnte, $message) {
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            if (!empty($admin->phone)) {
                $fonnte->sendMessage($admin->phone, $message);
            }
        }
    }
}