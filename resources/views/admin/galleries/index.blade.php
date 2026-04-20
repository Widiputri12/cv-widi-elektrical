<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl uppercase tracking-tighter text-[#1A1A1A]">
                Verifikasi <span class="text-[#D92323]">Galeri</span>
            </h2>
            <span class="font-black text-[10px] px-4 py-2 rounded-lg text-white bg-[#1A1A1A] border-2 border-[#1A1A1A] shadow-[2px_2px_0px_#FFD700] uppercase tracking-widest">
                {{ $galleries->count() }} Foto Masuk
            </span>
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
                    <h3 class="font-black text-white text-xs uppercase tracking-widest italic">📑 Antrean Verifikasi Dokumentasi</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b-4 border-[#1A1A1A]">
                                <th class="p-4 text-[10px] font-black uppercase text-[#1A1A1A]">Pratinjau Foto</th>
                                <th class="p-4 text-[10px] font-black uppercase text-[#1A1A1A]">Detail Informasi</th>
                                <th class="p-4 text-[10px] font-black uppercase text-[#1A1A1A] text-center">Status Moderasi</th>
                                <th class="p-4 text-[10px] font-black uppercase text-[#1A1A1A] text-center">Tindakan Akhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-4 divide-[#F3F4F6]">
                            @forelse($galleries as $gallery)
                            <tr class="hover:bg-red-50/30 transition-colors">
                                <td class="p-4">
                                    <div class="relative inline-block">
                                        <img class="h-24 w-24 rounded-xl object-cover border-4 border-[#1A1A1A] shadow-[4px_4px_0px_#1A1A1A]" 
                                             src="{{ asset('storage/' . $gallery->image_path) }}" alt="Foto">
                                    </div>
                                </td>
                                
                                <td class="p-4">
                                    <div class="max-w-xs">
                                        <p class="font-black text-sm uppercase text-[#1A1A1A] mb-1">{{ $gallery->title }}</p>
                                        <p class="text-[10px] font-bold text-gray-500 leading-tight mb-2 italic">
                                            "{{ Str::limit($gallery->description, 60) }}"
                                        </p>
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-tighter">
                                            📅 {{ $gallery->created_at->format('d/m/Y • H:i') }}
                                        </p>
                                    </div>
                                </td>

                                <td class="p-4 text-center">
                                    @if($gallery->status == 'pending')
                                        <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase bg-yellow-100 text-[#1A1A1A] border-2 border-[#FFD700] shadow-[2px_2px_0px_#FFD700]">
                                            ⏳ Menunggu
                                        </span>
                                    @else
                                        <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase bg-green-100 text-green-700 border-2 border-green-600 shadow-[2px_2px_0px_#16a34a]">
                                            ✓ Tayang
                                        </span>
                                    @endif
                                </td>

                                <td class="p-4 text-center">
                                    <div class="flex flex-col gap-2 items-center">
                                        @if($gallery->status == 'pending')
                                            <form action="{{ route('admin.galleries.approve', $gallery->id) }}" method="POST" class="w-full">
                                                @csrf @method('PUT')
                                                <button type="submit" class="w-full bg-[#FFD700] text-[#1A1A1A] border-2 border-[#1A1A1A] px-4 py-2 rounded-lg font-black text-[9px] uppercase hover:shadow-none transition-all shadow-[2px_2px_0px_#1A1A1A]">
                                                    Setujui ✅
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('admin.galleries.destroy', $gallery->id) }}" method="POST" onsubmit="return confirm('Hapus foto ini selamanya?');" class="w-full">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-full bg-white text-red-600 border-2 border-red-600 px-4 py-2 rounded-lg font-black text-[9px] uppercase hover:bg-red-600 hover:text-white transition-all shadow-[2px_2px_0px_#EF4444] hover:shadow-none">
                                                Hapus 🗑️
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="p-12 text-center text-gray-400 font-black uppercase text-xs italic tracking-widest">
                                    📭 Tidak ada foto dalam antrean verifikasi
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