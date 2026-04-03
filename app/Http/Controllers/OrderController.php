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
            'phone' => [
                'required',
                'numeric',
                'in:' . $user->phone, 
            ],
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'exists:services,id',
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

        $services = Service::whereIn('id', $request->service_ids)->get();
        $totalPrice = $services->sum('price');
        
        // LOGIKA REVISI DOSEN: DP 50%
        $dpAmount = $totalPrice * 0.5;
        $remainingBalance = $totalPrice - $dpAmount;

        $order = Order::create([
            'user_id' => $user->id,
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'address_detail' => $request->address_detail,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'notes' => $request->notes,
            'total_price' => $totalPrice,
            'dp_amount' => $dpAmount, 
            'remaining_balance' => $remainingBalance,
            'status' => 'pending',        
            'payment_status' => 'unpaid', 
            'payment_step' => 'dp', // Tahap pertama: DP
        ]);

        $order->services()->attach($request->service_ids);

        // GENERATE TOKEN DP SEKARANG (Agar tombol bayar langsung muncul)
        try {
            $snapToken = $midtrans->getSnapToken($order); //
            // PASTIKAN BARIS INI ADA:
            $order->update(['snap_token' => $snapToken]); //
            Log::info("Snap Token DP Berhasil: " . $snapToken); //
        } catch (\Exception $e) {
            Log::error('Gagal buat token DP: ' . $e->getMessage()); //
        }

        // Catatan: Notifikasi ke Admin via Fonnte sebaiknya di Callback 
        // setelah DP Lunas, tapi jika Princess ingin tetap ada notifikasi awal:
        $serviceNames = $services->pluck('name')->implode(', ');
        $pesanAdmin = "🚨 *ORDER BARU (MENUNGGU DP)!* 🚨\n\n";
        $pesanAdmin .= "👤 Nama: {$user->name}\n";
        $pesanAdmin .= "🛠️ Layanan: {$serviceNames}\n";
        $pesanAdmin .= "💰 Total: Rp " . number_format($totalPrice, 0, ',', '.') . "\n";
        $pesanAdmin .= "💵 DP 50%: Rp " . number_format($dpAmount, 0, ',', '.') . "\n";
        $pesanAdmin .= "📅 Jadwal: " . date('d M Y', strtotime($order->booking_date)) . "\n\n";
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
        $order = Order::with(['user', 'services', 'technician'])->findOrFail($id);
        $technicians = User::where('role', 'technician')->get();
        
        return view('admin.orders.show', compact('order', 'technicians'));
    }

    /**
     * Admin menugaskan teknisi (LBS Logic)
     */
    public function assignTechnician(Request $request, $id, FonnteService $fonnte)
    {
        $request->validate(['technician_id' => 'required|exists:users,id']);

        $order = Order::findOrFail($id);
        
        $order->update([
            'technician_id' => $request->technician_id,
            'status' => 'confirmed', 
        ]);

        // Set teknisi menjadi Sibuk
        User::where('id', $request->technician_id)->update(['is_busy' => 1]); 

        $order = Order::with(['user', 'technician', 'services'])->find($id);
        $technician = $order->technician;

        if ($technician && !empty($technician->phone)) {
            $pesanTeknisi = "🚨 *TUGAS BARU!* 🚨\n\nHalo *{$technician->name}*, cek dashboard untuk tugas baru #{$order->id}!";
            $fonnte->sendMessage($technician->phone, $pesanTeknisi);
        }

        if ($order->user && !empty($order->user->phone)) {
            $pesanPelanggan = "Halo *{$order->user->name}*, Order #{$order->id} telah Dikonfirmasi! Teknisi *{$technician->name}* segera meluncur.";
            $fonnte->sendMessage($order->user->phone, $pesanPelanggan);
        }

        return redirect()->route('dashboard')->with('success', 'Teknisi berhasil ditugaskan!');
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
     * Teknisi menyelesaikan tugas (LBS Reset & Generate Token Pelunasan)
     */
    public function updateFinish(Request $request, $id, MidtransService $midtrans, FonnteService $fonnte) 
    {
        // 1. Validasi Input Teknisi
        $request->validate([
            'image' => 'required|image|max:10240',
            'category' => 'required',
            'title' => 'required',
        ]);

        // 2. Upload Bukti Pekerjaan ke Galeri
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

        // 3. Ambil Data Order
        $order = Order::with(['user', 'services'])->findOrFail($id);

        // 4. UPDATE LOGIKA REVISI: Kerja Selesai, Tapi Bayar Belum (Tahap Pelunasan)
        $order->update([
            'status' => 'completed',       // Kerja teknisi selesai
            'payment_status' => 'unpaid',  // Set ke unpaid lagi supaya tombol PELUNASAN muncul
            'payment_step' => 'full'       // Tandai sekarang masuk ke tahap Pelunasan
        ]);

        // 5. Reset Status Teknisi (Biar bisa ambil order lain)
        if ($order->technician_id) {
            \App\Models\User::where('id', $order->technician_id)->update(['is_busy' => 0]);
        }

        // 6. Kirim Notifikasi WA ke Pelanggan
        if ($order->user && !empty($order->user->phone)) {
            $serviceNames = $order->services->pluck('name')->implode(', ');
            $pesanBayar = "❄️ *KERJA SELESAI!* ❄️\n\nHalo *{$order->user->name}*,\n\nServis *{$serviceNames}* telah diselesaikan oleh teknisi kami.\n\nSilakan lakukan *PELUNASAN SISA 50%* di dashboard agar pesanan dapat ditutup secara resmi.\n\nTerima kasih atas kepercayaannya!";
            $fonnte->sendMessage($order->user->phone, $pesanBayar);
        }

        // 7. GENERATE SNAP TOKEN PELUNASAN (Sisa 50%)
        try {
            // Berikan jeda 1 detik agar DB tenang
            sleep(1); 
            
            // MidtransService otomatis mengambil sisa saldo karena status sudah 'completed'
            $snapToken = $midtrans->getSnapToken($order);
            
            // Simpan token baru untuk tombol pelunasan di dashboard pelanggan
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

        // NOTIF KE ADMIN: Pelanggan membatalkan pesanan
        $pesanAdmin = "⚠️ *PESANAN DIBATALKAN PELANGGAN* ⚠️\n\nHalo Admin, Pelanggan *{$order->user->name}* telah membatalkan pesanan #{$order->id} secara mandiri.";
        $this->sendToAdmins($fonnte, $pesanAdmin); // Gunakan helper sendToAdmins tadi

        return back()->with('success', 'Pesanan berhasil Anda batalkan.');
    }

    public function cancelByAdmin(Request $request, $id, FonnteService $fonnte)
    {
        $request->validate(['cancel_notes' => 'required|string|min:5']);
        $order = Order::with('user')->findOrFail($id);

        // PROTEKSI: Jika sudah DP (payment_status == paid), Admin dilarang cancel!
        if ($order->payment_status === 'paid') {
            return back()->with('error', 'Gagal! Pesanan yang sudah dibayar DP-nya tidak dapat dibatalkan.');
        }

        $order->update([
            'status' => 'cancelled',
            'cancel_notes' => $request->cancel_notes
        ]);

        // NOTIF KE PELANGGAN: Disertai catatan admin
        if ($order->user && !empty($order->user->phone)) {
            $pesan = "Halo *{$order->user->name}*,\n\nMohon maaf, pesanan #{$order->id} Anda *DIBATALKAN* oleh Admin CV Widi.\n\n*Catatan Admin:* \"{$request->cancel_notes}\"\n\nSilakan hubungi kami jika ada pertanyaan.";
            $fonnte->sendMessage($order->user->phone, $pesan);
        }

        return redirect()->route('dashboard')->with('success', 'Pesanan berhasil dibatalkan dan notifikasi WA terkirim.');
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