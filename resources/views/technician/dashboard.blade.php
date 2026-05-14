<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl text-gray-800 uppercase tracking-tight">
            Dashboard Teknisi 🛠️
        </h2>
    </x-slot>

    <div class="py-10 bg-[#F3F4F6] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <h3 class="text-2xl font-black text-gray-800 mb-4 uppercase italic">Tugas Perbaikan AC</h3>

            {{-- KOTAK FILTER TANGGAL --}}
            <div class="mb-6 bg-white border-2 border-[#1A1A1A] p-5 rounded-2xl shadow-[4px_4px_0px_#1A1A1A]">
                <h4 class="text-[11px] font-black text-[#1A1A1A] uppercase mb-3 tracking-widest border-b-2 border-gray-100 pb-2">🔍 Filter Jadwal Tugas</h4>
                <form action="{{ url()->current() }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="flex-1 w-full">
                        <label class="block text-[10px] font-black uppercase text-gray-500 mb-1 tracking-wider">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full border-2 border-gray-200 rounded-xl text-sm font-bold focus:border-[#D92323] focus:ring-0">
                    </div>
                    <div class="flex-1 w-full">
                        <label class="block text-[10px] font-black uppercase text-gray-500 mb-1 tracking-wider">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full border-2 border-gray-200 rounded-xl text-sm font-bold focus:border-[#D92323] focus:ring-0">
                    </div>
                    <div class="flex gap-2 w-full md:w-auto">
                        <button type="submit" class="flex-1 md:flex-none bg-[#1A1A1A] text-white px-6 py-3 rounded-xl text-[10px] font-black uppercase shadow-[3px_3px_0px_#D92323] hover:translate-x-1 hover:translate-y-1 hover:shadow-none transition-all tracking-widest">Filter</button>
                        <a href="{{ url()->current() }}" class="flex-1 md:flex-none bg-gray-100 text-gray-600 border-2 border-gray-200 px-6 py-3 rounded-xl text-[10px] font-black uppercase text-center hover:bg-gray-200 transition-all tracking-widest">Reset</a>
                    </div>
                </form>
            </div>

            {{-- TABEL DATA TUGAS --}}
            <div class="bg-white rounded-xl shadow-md overflow-hidden border-2 border-[#1A1A1A]">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-[#1A1A1A] text-white text-[11px] font-black uppercase tracking-widest">
                                <th class="px-6 py-4">ID & Tgl</th>
                                <th class="px-6 py-4">Pelanggan</th>
                                <th class="px-6 py-4">Layanan</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($assignedOrders as $order)
                            <tr class="text-sm font-bold text-gray-800 hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-black text-[#D92323]">#{{ $order->id }}</div>
                                    <div class="text-[10px] text-gray-500">{{ \Carbon\Carbon::parse($order->booking_date)->format('d M Y') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="uppercase font-black text-gray-900">{{ $order->user->name }}</div>
                                    <div class="text-[10px] text-gray-500 font-bold mt-1">{{ $order->user->phone }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($order->services as $svc)
                                            <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-[9px] border border-blue-200 uppercase font-black">
                                                {{ $svc->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center uppercase text-[10px] font-black tracking-widest">
                                    <span class="px-3 py-1 rounded-full {{ $order->status == 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center items-center gap-2">
                                        {{-- TOMBOL DETAIL --}}
                                        <a href="{{ route('technician.orders.finish', $order->id) }}" class="bg-white text-[#1A1A1A] border-2 border-[#1A1A1A] text-[9px] font-black px-3 py-2 rounded-lg shadow-[2px_2px_0px_#1A1A1A] uppercase hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-none transition-all">
                                            Detail
                                        </a>

                                        {{-- TOMBOL SELESAIKAN (Hanya Muncul Jika Belum Selesai) --}}
                                        @if($order->status !== 'completed')
                                            <a href="{{ route('technician.orders.finish', $order->id) }}" class="bg-[#D92323] text-white border-2 border-[#D92323] text-[9px] font-black px-3 py-2 rounded-lg shadow-[2px_2px_0px_#1A1A1A] uppercase tracking-widest hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-none transition-all">
                                                Selesaikan
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-16 text-center text-gray-400 font-black uppercase tracking-widest">
                                    <div class="text-3xl mb-2">☕</div>
                                    Belum ada tugas di periode ini.
                                </td>
                            </tr>
                            @endforelse 
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>