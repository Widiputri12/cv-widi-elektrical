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
            $snapToken = $midtrans->getSnapToken($order);
            $order->update(['snap_token' => $snapToken]);
            Log::info("Snap Token DP Berhasil untuk Order #{$order->id}");
        } catch (\Exception $e) {
            Log::error('Gagal buat token DP saat store: ' . $e->getMessage());
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
        $request->validate([
            'image' => 'required|image|max:10240',
            'category' => 'required',
            'title' => 'required',
        ]);

        $order = Order::with(['user', 'services'])->findOrFail($id);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('proofs', 'public');
            Gallery::create([
                'title' => $request->title,
                'category' => $request->category,
                'description' => $request->description ?? 'Pekerjaan selesai oleh ' . Auth::user()->name,
                'image_path' => $imagePath,
                'status' => 'pending',
            ]);
        }

        // 1. Update status pesanan & Reset Teknisi
        $order->update(['status' => 'completed']);

        if ($order->technician_id) {
            User::where('id', $order->technician_id)->update(['is_busy' => 0]);
        }

        // 2. Kirim Notifikasi WA Selesai
        if ($order->user && !empty($order->user->phone)) {
            $serviceNames = $order->services->pluck('name')->implode(', ');
            $pesanBayar = "Halo *{$order->user->name}*,\n\nPengerjaan servis *{$serviceNames}* telah SELESAI! ❄️✨\n\nSilakan bayar PELUNASAN di dashboard.\nTerima kasih!";
            $fonnte->sendMessage($order->user->phone, $pesanBayar);
        }

        // 3. GENERATE SNAP TOKEN UNTUK PELUNASAN (Sisa 50%)
        try {
            sleep(1); 
            // Karena status sudah completed, MidtransService otomatis ambil remaining_balance
            $snapToken = $midtrans->getSnapToken($order);
            $order->update(['snap_token' => $snapToken]);
            Log::info("Snap Token Pelunasan Berhasil untuk Order #{$id}");
        } catch (\Exception $e) {
            Log::error('Midtrans Error Pelunasan: ' . $e->getMessage());
        }

        return redirect()->route('dashboard')->with('success', 'Laporan terkirim & Tagihan pelunasan dibuat!');
    }

    public function cancelByCustomer($id, FonnteService $fonnte)
    {
        $order = Order::with('user')->findOrFail($id);

        if ($order->status !== 'pending') {
            return back()->with('error', 'Maaf Princess, pesanan sudah diproses dan tidak bisa dibatalkan.');
        }

        $order->update([
            'status' => 'cancelled',
            'cancel_notes' => 'Dibatalkan oleh Pelanggan'
        ]);

        if ($order->user && !empty($order->user->phone)) {
            $pesanCancel = "Halo *{$order->user->name}*,\n\nPesanan Anda #{$order->id} telah BERHASIL DIBATALKAN. 🙏";
            $fonnte->sendMessage($order->user->phone, $pesanCancel);
        }

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function cancelByAdmin(Request $request, $id, FonnteService $fonnte)
    {
        $request->validate([
            'cancel_notes' => 'required|string|min:5' 
        ]);

        $order = Order::with('user')->findOrFail($id);
        
        $order->update([
            'status' => 'cancelled',
            'cancel_notes' => $request->cancel_notes
        ]);

        if ($order->user && !empty($order->user->phone)) {
            $pesanCancelAdmin = "Halo *{$order->user->name}*,\n\nPesanan #{$order->id} DIBATALKAN oleh Admin.\nAlasan: *{$request->cancel_notes}*";
            $fonnte->sendMessage($order->user->phone, $pesanCancelAdmin);
        }

        return back()->with('success', 'Pesanan telah dibatalkan oleh Admin.');
    }
}