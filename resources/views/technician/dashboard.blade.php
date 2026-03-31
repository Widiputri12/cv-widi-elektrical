<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl text-gray-800 uppercase tracking-tight">
            Dashboard Teknisi 🛠️
        </h2>
    </x-slot>

    <div class="py-10 bg-[#F3F4F6] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <h3 class="text-2xl font-black text-gray-800 mb-6 uppercase italic">Tugas Perbaikan AC</h3>

            <div class="bg-white rounded-xl shadow-md overflow-hidden border-2 border-[#1A1A1A]">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-[#1A1A1A] text-white text-[11px] font-black uppercase tracking-widest">
                                <th class="px-6 py-4">ID</th>
                                <th class="px-6 py-4">Pelanggan</th>
                                <th class="px-6 py-4">Layanan</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($assignedOrders as $order)
                            <tr class="text-sm font-bold text-gray-800">
                                <td class="px-6 py-4">#{{ $order->id }}</td>
                                <td class="px-6 py-4">
                                    <div class="uppercase">{{ $order->user->name }}</div>
                                    <div class="text-[10px] text-gray-400">{{ $order->user->phone }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        {{-- REVISI: Menampilkan banyak layanan --}}
                                        @foreach($order->services as $svc)
                                            <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded text-[10px] border border-blue-100 uppercase">
                                                {{ $svc->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 uppercase text-[10px] tracking-widest">
                                    {{ $order->status }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($order->status !== 'completed')
                                        <a href="{{ route('technician.orders.finish', $order->id) }}" class="bg-[#D92323] text-white text-[10px] font-black px-4 py-2 rounded-lg shadow-[2px_2px_0px_#1A1A1A] uppercase tracking-widest">
                                            Selesaikan
                                        </a>
                                    @else
                                        <span class="text-green-600 font-black text-[10px] uppercase">Selesai ✅</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-gray-400 font-black uppercase">
                                    Belum ada tugas untukmu hari ini.
                                </td>
                            </tr>
                            @endforelse {{-- PENUTUP YANG SERING HILANG --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>