<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl uppercase tracking-tighter text-[#1A1A1A]">
                Detail Pesanan <span class="text-[#D92323]">#{{ $order->id }}</span>
            </h2>
            <a href="{{ route('dashboard') }}" class="font-black text-xs px-6 py-3 rounded-xl text-white bg-[#1A1A1A] border-2 border-[#1A1A1A] shadow-[4px_4px_0px_#D92323] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all uppercase">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-10 min-h-screen bg-[#F3F4F6]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                {{-- SEKSI KIRI: INFORMASI PELANGGAN & PETA LBS --}}
                <div class="md:col-span-2 space-y-6">
                    
                    {{-- CARD DATA PELANGGAN --}}
                    <div class="bg-white rounded-2xl border-4 border-[#1A1A1A] shadow-[8px_8px_0px_#1A1A1A] p-6">
                        <h3 class="font-black text-lg uppercase mb-4 border-b-4 border-[#F3F4F6] pb-2 flex items-center">
                            <span class="bg-[#D92323] text-white p-1 rounded mr-2 text-sm">👤</span> Data Pelanggan
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest">Nama Pelanggan</p>
                                <p class="font-black text-xl text-[#1A1A1A]">{{ $order->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest">Nomor WhatsApp</p>
                                <p class="font-black text-xl text-green-600">{{ $order->user->phone }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest">Layanan</p>
                                <div class="flex flex-wrap gap-2 mt-1">
                                    @forelse($order->services as $svc)
                                        <span class="px-3 py-1 bg-[#1A1A1A] text-white text-[9px] font-black rounded-lg uppercase">
                                            {{ $svc->name }}
                                        </span>
                                    @empty
                                        <span class="text-red-500 font-bold text-xs">Data layanan kosong</span>
                                    @endforelse
                                </div>
                            </div>
                            <div>
                                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest">Total Harga (Tagihan)</p>
                                <p class="font-black text-2xl text-[#D92323]">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                <p class="text-[9px] font-bold text-gray-500 italic uppercase">Wajib DP: Rp {{ number_format($order->total_price * 0.5, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- CARD MAP LBS (GEOLOCATION) --}}
                    <div class="bg-white rounded-2xl border-4 border-[#1A1A1A] shadow-[8px_8px_0px_#1A1A1A] overflow-hidden">
                        <div class="p-4 bg-[#1A1A1A] text-white flex justify-between items-center">
                            <h3 class="font-black uppercase text-xs italic">📍 Titik Geolocation (LBS)</h3>
                            <a href="https://www.google.com/maps?q={{ $order->latitude }},{{ $order->longitude }}" target="_blank" class="bg-[#FFD700] text-[#1A1A1A] px-4 py-1.5 rounded-lg text-[10px] font-black hover:scale-105 transition uppercase">
                                Google Maps ↗
                            </a>
                        </div>
                        
                        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                        
                        <div id="map" class="w-full h-96 z-0 border-b-4 border-[#1A1A1A]"></div>
                        
                        <div class="p-6">
                            <p class="text-gray-400 text-[10px] font-black uppercase mb-1">Detail Alamat:</p>
                            <p class="font-bold text-gray-800 italic leading-tight">{{ $order->address_detail }}</p>
                        </div>
                    </div>
                </div>

                {{-- SEKSI KANAN: STATUS PENUGASAN & PEMBATALAN --}}
                <div class="md:col-span-1">
                    
                    {{-- 1. JIKA ORDER SUDAH SELESAI --}}
                    @if($order->status == 'completed')
                        <div class="bg-white rounded-3xl border-4 border-[#1A1A1A] shadow-[8px_8px_0px_#22C55E] p-8 sticky top-10 text-center">
                            <div class="w-20 h-20 bg-[#DCFCE7] text-[#22C55E] rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-[#1A1A1A]">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <h3 class="font-black text-2xl uppercase mb-2">Tugas Selesai</h3>
                            <div class="bg-gray-50 border-2 border-[#1A1A1A] p-4 rounded-2xl">
                                <p class="font-black text-[#1A1A1A] text-lg">{{ $order->technician->name ?? 'Teknisi' }}</p>
                                <p class="text-[9px] font-bold text-green-600 uppercase">Status: Lunas & Terverifikasi ✅</p>
                            </div>
                        </div>

                    {{-- 2. JIKA ORDER DIBATALKAN --}}
                    @elseif($order->status == 'cancelled')
                        <div class="bg-white rounded-3xl border-4 border-[#1A1A1A] shadow-[8px_8px_0px_#EF4444] p-8 sticky top-10 text-center">
                            <div class="w-20 h-20 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-[#1A1A1A]">
                                <span class="text-3xl font-black">X</span>
                            </div>
                            <h3 class="font-black text-xl uppercase mb-2">Dibatalkan</h3>
                            <div class="p-4 bg-gray-50 border-2 border-dashed border-red-300 rounded-2xl italic">
                                <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Catatan Pembatalan:</p>
                                <p class="text-xs font-bold text-red-600">"{{ $order->cancel_notes ?? 'Dibatalkan oleh sistem.' }}"</p>
                            </div>
                        </div>

                    {{-- 3. JIKA ORDER PENDING/PROSES (FORM PLOTTING ATAU STATUS JALAN) --}}
                    @else
                        <div class="bg-white rounded-3xl border-4 border-[#1A1A1A] shadow-[8px_8px_0px_#FFD700] p-8 sticky top-10">
                            
                            {{-- JIKA STATUS MASIH PENDING (BELUM ADA TEKNISI) --}}
                            @if($order->status == 'pending')
                                
                                {{-- PROTEKSI: HANYA BISA PLOTTING JIKA DP SUDAH PAID --}}
                                @if($order->payment_status === 'paid')
                                    <h3 class="font-black text-xl uppercase mb-6 text-center italic underline decoration-[#D92323] decoration-4 underline-offset-4">Plotting Teknisi</h3>
                                    
                                    <form action="{{ route('admin.orders.assign', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="space-y-6">
                                            <div>
                                                <label class="block text-[10px] font-black uppercase text-gray-500 mb-2 italic tracking-widest">Pilih Personel Ready:</label>
                                                <select name="technician_id" required class="w-full border-4 border-[#1A1A1A] rounded-2xl p-4 font-black text-sm focus:ring-0 focus:border-[#D92323] appearance-none cursor-pointer bg-white">
                                                    <option value="" disabled selected>-- PILIH TEKNISI --</option>
                                                    @foreach($technicians as $tech)
                                                        <option value="{{ $tech->id }}" {{ $tech->is_busy ? 'disabled' : '' }} class="{{ $tech->is_busy ? 'text-red-400' : 'text-green-600 font-black' }}">
                                                            {{ $tech->name }} {{ $tech->is_busy ? ' (BUSY 🛠️)' : ' (READY ✅)' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <button type="submit" class="w-full bg-[#1A1A1A] text-white font-black py-5 rounded-2xl shadow-[4px_4px_0px_#D92323] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all uppercase tracking-widest text-xs">
                                                🚀 Konfirmasi Penugasan
                                            </button>
                                        </div>
                                    </form>
                                @else
                                    {{-- Tampilan jika DP belum dibayar --}}
                                    <div class="text-center py-6">
                                        <div class="text-4xl mb-4 animate-pulse">💳</div>
                                        <h3 class="font-black text-lg uppercase text-red-600">Menunggu DP</h3>
                                        <p class="text-[10px] font-bold text-gray-500 uppercase px-4 mt-2">
                                            Tombol plotting terkunci. Tunggu pelanggan bayar DP 50%.
                                        </p>
                                    </div>
                                @endif

                            {{-- JIKA STATUS SUDAH CONFIRMED/WORKING (TEKNISI SUDAH DIPILIH) --}}
                            @elseif($order->status == 'confirmed' || $order->status == 'working')
                                <div class="text-center py-4">
                                    <div class="w-20 h-20 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-[#1A1A1A] animate-bounce">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </div>
                                    <h3 class="font-black text-xl uppercase mb-2">Teknisi Meluncur</h3>
                                    <div class="bg-blue-50 border-2 border-blue-200 p-4 rounded-2xl mb-4">
                                        <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Personel Bertugas:</p>
                                        <p class="font-black text-[#1A1A1A] text-lg uppercase">{{ $order->technician->name }}</p>
                                        <p class="text-[9px] font-bold text-blue-600 mt-1 uppercase tracking-tighter italic">Sedang Menuju Lokasi Pelanggan...</p>
                                    </div>
                                </div>
                            @endif

                            {{-- FITUR PEMBATALAN ADMIN (TETAP ADA DI BAWAH) --}}
                            <div class="mt-8 pt-6 border-t-2 border-dashed border-gray-200">
                                <h4 class="text-[10px] font-black uppercase text-red-600 mb-3 italic">⚠️ Opsi Pembatalan Admin</h4>
                                <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                    @csrf
                                    @method('PUT')
                                    <label class="block text-[9px] font-black uppercase text-gray-400 mb-1 tracking-widest">Alasan Pembatalan (Wajib):</label>
                                    <textarea name="cancel_notes" required class="w-full border-2 border-gray-200 rounded-xl p-3 text-[11px] font-bold focus:border-red-500 focus:ring-0 placeholder:text-gray-300 transition-all" placeholder="Tulis alasan di sini..."></textarea>
                                    
                                    <button type="submit" class="w-full mt-3 bg-white text-red-600 border-2 border-red-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase hover:bg-red-50 transition-all">
                                        🚫 Batalkan Pesanan
                                    </button>
                                </form>
                            </div>

                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    {{-- SCRIPT PETA LBS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var lat = {{ $order->latitude ?? -7.818838 }};
            var lng = {{ $order->longitude ?? 112.012563 }};
            
            if (typeof L !== 'undefined') {
                var map = L.map('map', { scrollWheelZoom: false }).setView([lat, lng], 16);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© CV. WIDI ELEKTRICAL'
                }).addTo(map);

                L.marker([lat, lng]).addTo(map)
                    .bindPopup('<div class="font-black uppercase text-[10px]">Lokasi Pelanggan:<br><span class="text-[#D92323]">{{ $order->user->name }}</span></div>')
                    .openPopup();

                setTimeout(function(){ map.invalidateSize(); }, 500);
            }
        });
    </script>
</x-app-layout>