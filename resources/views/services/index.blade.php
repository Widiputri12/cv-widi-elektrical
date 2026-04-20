<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl uppercase tracking-tighter text-[#1A1A1A]">
                Kelola <span class="text-[#D92323]">Layanan</span>
            </h2>
            <a href="{{ route('services.create') }}" class="font-black text-xs px-6 py-3 rounded-xl text-white bg-[#D92323] border-4 border-[#1A1A1A] shadow-[4px_4px_0px_#1A1A1A] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all uppercase">
                ➕ Tambah Layanan
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-[#F3F4F6] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border-4 border-green-600 text-green-700 font-black uppercase text-xs rounded-xl shadow-[4px_4px_0px_#16a34a]">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <div class="bg-white border-4 border-[#1A1A1A] shadow-[8px_8px_0px_#FFD700] rounded-2xl overflow-hidden">
                <div class="p-4 bg-[#1A1A1A] border-b-4 border-[#1A1A1A]">
                    <h3 class="font-black text-white text-xs uppercase tracking-widest italic">📋 Katalog Jasa Servis AC</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b-4 border-[#1A1A1A]">
                                <th class="p-4 text-[10px] font-black uppercase text-[#1A1A1A]">Nama Layanan</th>
                                <th class="p-4 text-[10px] font-black uppercase text-[#1A1A1A]">Harga Jasa</th>
                                <th class="p-4 text-[10px] font-black uppercase text-[#1A1A1A]">Deskripsi Singkat</th>
                                <th class="p-4 text-[10px] font-black uppercase text-[#1A1A1A] text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-4 divide-[#F3F4F6]">
                            @forelse($services as $service)
                            <tr class="hover:bg-red-50/30 transition-colors">
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-[#D92323] border-2 border-[#1A1A1A] rounded-lg flex items-center justify-center font-black text-white shadow-[2px_2px_0px_#1A1A1A]">
                                            🛠️
                                        </div>
                                        <div>
                                            <p class="font-black text-sm uppercase text-[#1A1A1A]">{{ $service->name }}</p>
                                            <p class="text-[9px] text-gray-400 font-bold tracking-tight">ID: #SRV-{{ $service->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div class="inline-flex items-center px-3 py-1 bg-yellow-100 border-2 border-[#FFD700] rounded-lg">
                                        <span class="text-[#1A1A1A] font-black text-xs italic">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <p class="text-[10px] text-gray-600 font-bold leading-tight max-w-xs">
                                        {{ Str::limit($service->description, 60) }}
                                    </p>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('services.edit', $service->id) }}" class="bg-[#FFD700] text-[#1A1A1A] border-2 border-[#1A1A1A] px-3 py-1 rounded-lg font-black text-[9px] uppercase hover:shadow-none transition-all shadow-[2px_2px_0px_#1A1A1A]">
                                            Edit ✏️
                                        </a>
                                        <form action="{{ route('services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('Hapus layanan ini?')">
                                            @csrf @method('DELETE')
                                            <button class="bg-white text-red-600 border-2 border-red-600 px-3 py-1 rounded-lg font-black text-[9px] uppercase hover:bg-red-600 hover:text-white transition-all shadow-[2px_2px_0px_#EF4444] hover:shadow-none">
                                                Hapus 🗑️
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-gray-400 font-black uppercase text-xs">Belum ada data layanan tersedia.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>