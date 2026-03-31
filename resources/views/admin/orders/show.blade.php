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
                                        <span class="text-red-500 font-bold">Data layanan kosong</span>
                                    @endforelse
                                </div>
                            </div>
                            <div>
                                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest">Total Harga</p>
                                <p class="font-black text-2xl text-[#D92323]">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- CARD MAP LBS (GEOLOCATION) --}}
                    <div class="bg-white rounded-2xl border-4 border-[#1A1A1A] shadow-[8px_8px_0px_#1A1A1A] overflow-hidden">
                        <div class="p-4 bg-[#1A1A1A] text-white flex justify-between items-center">
                            <h3 class="font-black uppercase text-xs italic">📍 Titik Geolocation (LBS)</h3>
                            <a href="https://www.google.com/maps/search/?api=1&query=3{{ $order->latitude }},{{ $order->longitude }}" target="_blank" class="bg-[#FFD700] text-[#1A1A1A] px-4 py-1.5 rounded-lg text-[10px] font-black hover:scale-105 transition uppercase">
                                Google Maps ↗
                            </a>
                        </div>
                        
                        {{-- Assets Leaflet dipanggil langsung agar anti-error --}}
                        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                        
                        <div id="map" class="w-full h-96 z-0 border-b-4 border-[#1A1A1A]"></div>
                        
                        <div class="p-6">
                            <p class="text-gray-400 text-[10px] font-black uppercase mb-1">Detail Alamat:</p>
                            <p class="font-bold text-gray-800 italic leading-tight">{{ $order->address_detail }}</p>
                        </div>
                    </div>
                </div>

                {{-- SEKSI KANAN: STATUS PENUGASAN (DINAMIS) --}}
                <div class="md:col-span-1">
                    
                    {{-- 1. JIKA ORDER SUDAH SELESAI (COMPLETED) --}}
                    @if($order->status == 'completed')
                        <div class="bg-white rounded-3xl border-4 border-[#1A1A1A] shadow-[8px_8px_0px_#22C55E] p-8 sticky top-10 text-center">
                            <div class="w-20 h-20 bg-[#DCFCE7] text-[#22C55E] rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-[#1A1A1A]">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <h3 class="font-black text-2xl uppercase mb-2">Tugas Selesai</h3>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-6 leading-tight">
                                Pesanan ini telah diselesaikan oleh teknisi:
                            </p>
                            <div class="bg-gray-50 border-2 border-[#1A1A1A] p-4 rounded-2xl mb-6">
                                <p class="font-black text-[#1A1A1A] text-lg">{{ $order->technician->name ?? 'Teknisi' }}</p>
                                <p class="text-[9px] font-bold text-green-600 uppercase">Status: Tersedia Kembali ✅</p>
                            </div>
                            <span class="inline-block bg-[#1A1A1A] text-white px-6 py-2 rounded-full font-black text-[10px] uppercase tracking-tighter">
                                Arsip Terkunci 🔒
                            </span>
                        </div>

                    {{-- 2. JIKA ORDER MASIH PENDING / PROSES (PENUGASAN AKTIF) --}}
                    @else
                        <div class="bg-white rounded-3xl border-4 border-[#1A1A1A] shadow-[8px_8px_0px_#FFD700] p-8 sticky top-10">
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
                                                <option value="{{ $tech->id }}" 
                                                    {{ $tech->is_busy ? 'disabled' : '' }} 
                                                    class="{{ $tech->is_busy ? 'text-red-400' : 'text-green-600 font-black' }}">
                                                    {{ $tech->name }} 
                                                    {{ $tech->is_busy ? ' (SEDANG BERTUGAS 🛠️)' : ' (READY ✅)' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="bg-[#F9FAFB] p-4 rounded-2xl border-2 border-dashed border-gray-300">
                                        <p class="text-[9px] font-black text-gray-400 uppercase text-center mb-1">Jadwal Kedatangan</p>
                                        <p class="font-black text-center text-[#1A1A1A] uppercase">
                                            {{ date('d M Y', strtotime($order->booking_date)) }} <br>
                                            <span class="text-[#D92323]">JAM {{ $order->booking_time }}</span>
                                        </p>
                                    </div>

                                    <button type="submit" class="w-full bg-[#1A1A1A] text-white font-black py-5 rounded-2xl shadow-[4px_4px_0px_#D92323] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all uppercase tracking-widest text-xs">
                                        🚀 Konfirmasi Penugasan
                                    </button>
                                </div>
                            </form>
                            
                            <p class="mt-6 text-[9px] text-center font-bold text-gray-400 uppercase italic leading-none">
                                *Hanya teknisi dengan status (Ready) yang dapat dipilih oleh sistem LBS.
                            </p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    {{-- SCRIPT PETA LBS LEAFLET --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil koordinat dari PHP Laravel
            var lat = {{ $order->latitude ?? -7.2504 }};
            var lng = {{ $order->longitude ?? 112.7688 }};
            
            // Inisialisasi Map (Gunakan window.onload agar Leaflet siap)
            if (typeof L !== 'undefined') {
                var map = L.map('map', {
                    scrollWheelZoom: false // Mencegah zoom tidak sengaja saat scroll
                }).setView([lat, lng], 16);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© CV. WIDI ELEKTRICAL'
                }).addTo(map);

                // Tambah Marker LBS
                var marker = L.marker([lat, lng]).addTo(map)
                    .bindPopup('<div class="font-black uppercase text-[10px]">Lokasi Target:<br><span class="text-[#D92323] font-black">{{ $order->user->name }}</span></div>')
                    .openPopup();

                // Paksa render ulang kontainer map agar tidak glitch/abu-abu
                setTimeout(function(){ 
                    map.invalidateSize(); 
                }, 500);
            }
        });
    </script>
</x-app-layout>