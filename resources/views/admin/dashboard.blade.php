<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-4">
                {{-- Gaya Orbit Logo Khas Widi --}}
                <div class="mini-orbit-container">
                    <img src="{{ asset('logo.png') }}" class="h-10 w-auto relative z-10" alt="Logo Widi">
                </div>
                <div>
                    <h2 class="font-black text-2xl text-[#1A1A1A] leading-none tracking-tight uppercase">
                        CV. WIDI <span class="text-[#D92323]">ELEKTRICAL</span>
                    </h2>
                    <p class="text-[10px] text-[#D92323] font-bold uppercase tracking-widest mt-1">
                        Admin Dashboard Monitoring & Order
                    </p>
                </div>
            </div>
            
            <div class="hidden md:flex items-center">
                <div class="px-4 py-2 bg-green-50 text-green-700 rounded-xl text-[10px] font-black border-2 border-green-200 flex items-center shadow-sm uppercase">
                    <span class="relative flex h-2 w-2 mr-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-600"></span>
                    </span>
                    System Online
                </div>
            </div>
        </div>

        <style>
            .mini-orbit-container { position: relative; width: 45px; height: 45px; display: flex; justify-content: center; align-items: center; }
            .mini-orbit-container::before { content: ''; position: absolute; width: 100%; height: 100%; border-radius: 50%; border: 2px solid transparent; border-top-color: #FFD700; border-right-color: #D92323; animation: spin-lightning 3s linear infinite; }
            @keyframes spin-lightning { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
            
            .custom-scrollbar::-webkit-scrollbar { width: 4px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: #D92323; border-radius: 4px; }
        </style>
    </x-slot>

    <div class="py-10 bg-[#F9FAFB] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- 3 KOTAK STATISTIK - TEMA MERAH KUNING --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-6 rounded-2xl border-2 border-[#1A1A1A] shadow-[4px_4px_0px_#1A1A1A] flex justify-between items-center h-32">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Pesanan</p>
                        <h3 class="text-5xl font-black text-[#1A1A1A] mt-2">{{ $orders->count() }}</h3>
                    </div>
                    <div class="text-[#1A1A1A]">
                        <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border-2 border-[#1A1A1A] shadow-[4px_4px_0px_#FFD700] flex justify-between items-center h-32">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Perlu Tindakan</p>
                        <h3 class="text-5xl font-black text-yellow-500 mt-2">{{ $orders->where('status', 'pending')->count() }}</h3>
                    </div>
                    <div class="text-yellow-500">
                        <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border-2 border-[#1A1A1A] shadow-[4px_4px_0px_#22C55E] flex justify-between items-center h-32">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Pesanan Selesai</p>
                        <h3 class="text-5xl font-black text-green-600 mt-2">{{ $orders->where('status', 'completed')->count() }}</h3>
                    </div>
                    <div class="text-green-600">
                        <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>

            {{-- ESTIMASI TOTAL OMZET - BANNER HITAM KUNING (Sesuai Layout Foto) --}}
            <div class="bg-[#1A1A1A] p-8 rounded-2xl border-2 border-[#1A1A1A] shadow-[6px_6px_0px_#D92323] mb-10">
                <p class="text-xs font-black text-[#FFD700] uppercase tracking-[0.2em]">Estimasi Total Omzet (Auto Sum)</p>
                <h3 class="text-5xl font-black text-white mt-2 tracking-tight">
                    Rp {{ number_format($orders->where('payment_status', 'paid')->sum('total_price'), 0, ',', '.') }}
                </h3>
            </div>

            <div class="mb-10">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1.5 h-6 bg-gray-800 rounded-full"></div>
                    <h2 class="text-2xl font-black text-gray-800 tracking-tight uppercase">Dokumen & Profil Perusahaan</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="https://drive.google.com/drive/folders/1HZyGr3kw-kxZeuCQOecHlpGCq5x9TkOK?usp=drive_link" target="_blank" class="group bg-[#D1D5DB] p-6 rounded-xl shadow-sm border-2 border-transparent hover:border-gray-400 transition-all flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-white/50 rounded-lg group-hover:bg-white transition-colors">
                                <svg class="w-8 h-8 text-gray-700" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M7.71 3.5L1.15 15l3.43 6 6.55-11.5H1.15M9.73 15L6.3 21h13.12l3.43-6H9.73M11.59 3.5L15 9.5h6.85L18.41 3.5h-6.82z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-black text-gray-800 uppercase leading-none">Company Profile</h4>
                                <p class="text-[10px] text-gray-500 font-bold uppercase mt-1 tracking-widest">Akses Google Drive</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-800 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>

                    <a href="https://drive.google.com/your-link-here" target="_blank" class="group bg-[#D1D5DB] p-6 rounded-xl shadow-sm border-2 border-transparent hover:border-gray-400 transition-all flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-white/50 rounded-lg group-hover:bg-white transition-colors">
                                <svg class="w-8 h-8 text-gray-700" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zM13 3.5L18.5 9H13V3.5zM6 20V4h6v6h6v10H6z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-black text-gray-800 uppercase leading-none">SOP & Dokumen Internal</h4>
                                <p class="text-[10px] text-gray-500 font-bold uppercase mt-1 tracking-widest">Arsip Digital</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-800 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>

            {{-- HEADER TABEL --}}
            <div class="flex flex-col md:flex-row justify-between items-end mb-6 gap-4">
                <div>
                    <h2 class="text-4xl font-black text-[#1A1A1A] tracking-tight uppercase italic">Data Order Masuk</h2>
                    <div class="w-24 h-2 bg-[#D92323] rounded-full mt-2"></div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('gallery.index') }}" class="bg-white text-[#1A1A1A] text-[10px] font-black px-5 py-3 rounded-xl border-2 border-[#1A1A1A] shadow-[4px_4px_0px_#1A1A1A] transition-all hover:shadow-none hover:translate-x-1 hover:translate-y-1 uppercase tracking-widest">
                        Kelola Galeri
                    </a>
                    <button onclick="location.reload()" class="bg-[#D92323] text-white text-[10px] font-black px-5 py-3 rounded-xl border-2 border-[#1A1A1A] shadow-[4px_4px_0px_#1A1A1A] transition-all hover:shadow-none hover:translate-x-1 hover:translate-y-1 uppercase tracking-widest">
                        REFRESH
                    </button>
                </div>
            </div>

            {{-- TABEL DATA - GAYA CV WIDI --}}
            <div class="bg-white rounded-3xl border-2 border-[#1A1A1A] shadow-[8px_8px_0px_#1A1A1A] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-[#1A1A1A] text-white text-[11px] font-black uppercase tracking-widest border-b-4 border-[#D92323]">
                                <th class="px-6 py-5">ID</th>
                                <th class="px-6 py-5">Pelanggan</th>
                                <th class="px-6 py-5">Layanan</th>
                                <th class="px-6 py-5 text-center">Jadwal</th>
                                <th class="px-6 py-5 text-center">Teknisi</th>
                                <th class="px-6 py-5 text-center">Status</th>
                                <th class="px-6 py-5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-gray-100">
                            @forelse($orders as $order)
                            <tr class="text-sm font-bold text-gray-800 hover:bg-red-50/40 transition-colors">
                                <td class="px-6 py-6 font-black text-[#D92323]">#{{ $order->id }}</td>
                                <td class="px-6 py-6">
                                    <div class="font-black uppercase text-gray-900 leading-tight">{{ $order->user->name }}</div>
                                    <div class="text-[10px] font-bold text-gray-400 mt-1">{{ $order->user->phone }}</div>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex flex-wrap gap-1">
                                        {{-- FUNGSI MULTI-SERVICE TETAP JALAN --}}
                                        @foreach($order->services as $svc)
                                            <span class="bg-gray-100 border border-gray-200 px-2 py-0.5 rounded text-[9px] font-black uppercase text-gray-600">{{ $svc->name }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-6 text-center">
                                    <span class="text-xs font-black text-gray-500">{{ date('d M Y', strtotime($order->booking_date)) }}</span>
                                </td>
                                <td class="px-6 py-6 text-center">
                                    <span class="text-[10px] font-black uppercase {{ $order->technicians->count() > 0 ? 'text-gray-800' : 'text-gray-300 italic' }}">
                                        {{ $order->technicians->count() > 0 ? $order->technicians->pluck('name')->implode(', ') : 'Belum Ditugaskan' }}
                                    </span>
                                </td>
                                <td class="px-6 py-6 text-center">
                                    @php
                                        $statusStyle = match($order->status) {
                                            'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                            'completed' => 'bg-green-100 text-green-700 border-green-200',
                                            default => 'bg-blue-100 text-blue-700 border-blue-200'
                                        };
                                    @endphp
                                    <span class="inline-block px-3 py-1 rounded-full border text-[9px] font-black uppercase tracking-tighter {{ $statusStyle }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-6 text-center">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-block bg-white text-[#1A1A1A] text-[10px] font-black px-4 py-2 rounded-lg border-2 border-[#1A1A1A] shadow-[3px_3px_0px_#1A1A1A] hover:shadow-none hover:translate-x-0.5 hover:translate-y-0.5 transition-all uppercase tracking-widest italic">Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-20 text-center text-gray-400 font-black uppercase tracking-[0.2em] text-xs">Belum ada pesanan masuk.</td>
                            </tr>
                            @endforelse
                            <div class="p-6 bg-white border-t-4 border-[#1A1A1A]">
                                {{ $orders->links() }}
                            </div>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>