<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl text-gray-800 uppercase tracking-tight">
            Detail Penyelesaian Tugas #{{ $order->id }} 🛠️
        </h2>
    </x-slot>

    <div class="py-10 bg-[#F3F4F6] min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- 1. DETAIL PESANAN --}}
            <div class="bg-white rounded-2xl border-2 border-[#1A1A1A] p-6 mb-6 shadow-[4px_4px_0px_#1A1A1A]">
                <h3 class="font-black uppercase mb-4 border-b-2 pb-2">Informasi Pekerjaan</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-400 uppercase text-[10px] font-black">Pelanggan:</p>
                        <p class="font-black text-gray-800">{{ $order->user->name }} ({{ $order->user->phone }})</p>
                    </div>
                    <div>
                        <p class="text-gray-400 uppercase text-[10px] font-black">Daftar Layanan:</p>
                        <div class="flex flex-wrap gap-1 mt-1">
                            {{-- Menggunakan forelse agar ada pesan jika data layanan kosong --}}
                            @forelse($order->services as $svc)
                                <span class="bg-red-50 text-[#D92323] px-2 py-0.5 rounded text-[10px] font-black border border-red-100 uppercase">
                                    {{ $svc->name }}
                                </span>
                            @empty
                                <span class="text-red-500 italic text-[10px] font-bold">⚠️ Layanan tidak terdaftar</span>
                            @endforelse
                        </div>
                    </div>
                    <div>
                        <p class="text-gray-400 uppercase text-[10px] font-black">Alamat Lokasi:</p>
                        {{-- Pastikan ini sesuai kolom di database Anda (address_detail) --}}
                        <p class="font-bold text-gray-700">{{ $order->address_detail }}</p>
                    </div>
                </div>
            </div>

            {{-- 2. NAVIGASI LBS BERBASIS GEOLOCATION (NO API KEY NEEDED) --}}
            <div class="bg-white rounded-2xl border-2 border-[#1A1A1A] shadow-[4px_4px_0px_#1A1A1A] overflow-hidden mb-6">
                <div class="p-4 bg-gray-50 border-b-2 border-gray-100 flex justify-between items-center">
                    <h3 class="text-sm font-black uppercase text-gray-800 flex items-center gap-2">
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-[#D92323]"></span>
                        </span>
                        Navigasi LBS Berbasis Geolocation
                    </h3>
                </div>
                
                <div class="h-80 w-full bg-gray-200 relative">
                    {{-- Menggunakan URL Viewport yang stabil tanpa API Key --}}
                    <iframe 
                        width="100%" 
                        height="100%" 
                        frameborder="0" 
                        style="border:0"
                        src="https://maps.google.com/maps?q={{ $order->latitude }},{{ $order->longitude }}&hl=id&z=15&output=embed" 
                        allowfullscreen>
                    </iframe>
                </div>
                
                <div class="p-4 bg-gray-50 border-t-2 border-gray-100">
                    {{-- TOMBOL LBS UTAMA: Mengaktifkan Geolocation di HP Teknisi --}}
                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $order->latitude }},{{ $order->longitude }}&travelmode=driving" 
                    target="_blank" 
                    class="flex justify-center items-center bg-[#D92323] text-white font-black text-xs py-4 rounded-xl uppercase tracking-widest shadow-[4px_4px_0px_#1A1A1A] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all">
                        🚗 AKTIFKAN RUTE DARI LOKASI SAYA
                    </a>
                    
                    <div class="mt-4 flex items-center gap-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                        <p class="text-[9px] font-bold text-blue-700 uppercase leading-tight">
                            Sistem LBS akan membuka App Google Maps dan mendeteksi posisi GPS teknisi secara otomatis.
                        </p>
                    </div>
                </div>
            </div>

            {{-- 3. FORM PENYELESAIAN --}}
            <div class="bg-white rounded-2xl border-2 border-[#1A1A1A] shadow-[8px_8px_0px_#22C55E] p-8">
                <h3 class="text-xl font-black uppercase mb-6 italic">Laporan Selesai Kerja</h3>
                
                <form action="{{ route('technician.orders.updateFinish', $order->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- INPUT TERSEMBUNYI UNTUK GALERI (Agar Validasi Controller Lolos) --}}
                    <input type="hidden" name="title" value="Pengerjaan Order #{{ $order->id }}">
                    <input type="hidden" name="category" value="Service AC">

                    <div class="mb-6">
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 italic">Informasi: Foto ini akan otomatis masuk ke Galeri Portofolio</label>
                    </div>

                    <div class="mb-6">
                        <label class="block text-[10px] font-black uppercase text-gray-500 mb-2">Upload Bukti Foto (AC Terpasang/Selesai)</label>
                        <input type="file" name="image" required class="w-full border-2 border-dashed border-gray-300 p-4 rounded-xl focus:border-[#D92323] outline-none">
                        @error('image') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-[10px] font-black uppercase text-gray-500 mb-2">Catatan Teknisi (Opsional)</label>
                        <textarea name="description" rows="3" class="w-full border-2 border-gray-200 rounded-xl p-4 focus:border-[#D92323] outline-none font-bold text-sm" placeholder="Contoh: AC sudah dingin kembali, freon ditambah..."></textarea>
                    </div>

                    <button type="submit" class="w-full bg-[#22C55E] text-white font-black py-4 rounded-xl border-2 border-[#1A1A1A] shadow-[4px_4px_0px_#1A1A1A] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all uppercase tracking-widest">
                        Konfirmasi Selesai ✅
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>