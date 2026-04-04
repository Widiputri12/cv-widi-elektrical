<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl uppercase tracking-tighter text-[#1A1A1A]">
                Kelola <span class="text-[#D92323]">Teknisi</span>
            </h2>
            <p class="text-[10px] bg-[#1A1A1A] text-white px-3 py-1 rounded-full font-bold uppercase tracking-widest">
                Admin Panel
            </p>
        </div>
    </x-slot>

    <div class="py-10 bg-[#F3F4F6] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            {{-- FORM TAMBAH TEKNISI --}}
            <div class="bg-white border-4 border-[#1A1A1A] shadow-[8px_8px_0px_#1A1A1A] rounded-2xl p-6">
                <div class="flex items-center gap-2 mb-6">
                    <span class="text-2xl">➕</span>
                    <h3 class="font-black text-sm uppercase italic text-[#1A1A1A]">Tambah Personel Baru</h3>
                </div>

                <form action="{{ route('admin.technicians.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-5 items-end">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-1 tracking-widest">Nama Lengkap</label>
                        <input type="text" name="name" placeholder="Nama Teknisi" required 
                            class="w-full border-4 border-[#1A1A1A] rounded-xl p-3 text-sm font-bold focus:ring-0 focus:border-[#D92323] transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-1 tracking-widest">Email Login</label>
                        <input type="email" name="email" placeholder="email@widi.com" required 
                            class="w-full border-4 border-[#1A1A1A] rounded-xl p-3 text-sm font-bold focus:ring-0 focus:border-[#D92323] transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-1 tracking-widest">No. WhatsApp</label>
                        <input type="text" name="phone" placeholder="08xxx" required 
                            class="w-full border-4 border-[#1A1A1A] rounded-xl p-3 text-sm font-bold focus:ring-0 focus:border-[#D92323] transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-1 tracking-widest">Password</label>
                        <input type="password" name="password" placeholder="********" required 
                            class="w-full border-4 border-[#1A1A1A] rounded-xl p-3 text-sm font-bold focus:ring-0 focus:border-[#D92323] transition-all">
                    </div>
                    <div class="md:col-span-4 flex justify-end">
                        <button type="submit" class="w-full md:w-auto bg-[#D92323] text-white font-black px-10 py-4 rounded-xl border-4 border-[#1A1A1A] shadow-[4px_4px_0px_#1A1A1A] uppercase text-xs hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all">
                            Simpan Personel 🚀
                        </button>
                    </div>
                </form>
            </div>

            {{-- TABEL DAFTAR TEKNISI --}}
            <div class="bg-white border-4 border-[#1A1A1A] shadow-[8px_8px_0px_#FFD700] rounded-2xl overflow-hidden">
                <div class="p-4 bg-[#1A1A1A] border-b-4 border-[#1A1A1A]">
                    <h3 class="font-black text-white text-xs uppercase tracking-widest italic">📋 Daftar Personel Teknisi Aktif</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b-4 border-[#1A1A1A]">
                                <th class="p-4 text-[10px] font-black uppercase text-[#1A1A1A]">Data Teknisi</th>
                                <th class="p-4 text-[10px] font-black uppercase text-[#1A1A1A]">Kontak WhatsApp</th>
                                <th class="p-4 text-[10px] font-black uppercase text-[#1A1A1A] text-center">Status Kerja</th>
                                <th class="p-4 text-[10px] font-black uppercase text-[#1A1A1A] text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-4 divide-[#F3F4F6]">
                            @foreach($technicians as $tech)
                            <tr class="hover:bg-red-50/30 transition-colors">
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-[#FFD700] border-2 border-[#1A1A1A] rounded-full flex items-center justify-center font-black text-[#1A1A1A]">
                                            {{ substr($tech->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-black text-sm uppercase text-[#1A1A1A]">{{ $tech->name }}</p>
                                            <p class="text-[9px] text-gray-400 font-bold tracking-tight">{{ $tech->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div class="inline-flex items-center px-3 py-1 bg-green-100 border-2 border-green-600 rounded-lg">
                                        <span class="text-green-700 font-black text-xs italic">{{ $tech->phone }}</span>
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    @if($tech->is_busy)
                                        <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase bg-red-100 text-red-600 border-2 border-red-600 animate-pulse">
                                            Busy 🛠️
                                        </span>
                                    @else
                                        <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase bg-green-100 text-green-600 border-2 border-green-600">
                                            Ready ✅
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 text-center">
                                    <form action="{{ route('admin.technicians.destroy', $tech->id) }}" method="POST" onsubmit="return confirm('Apakah Princess yakin ingin menghapus teknisi ini?')">
                                        @csrf 
                                        @method('DELETE')
                                        <button class="bg-white text-red-600 border-2 border-red-600 px-3 py-1 rounded-lg font-black text-[9px] uppercase hover:bg-red-600 hover:text-white transition-all shadow-[2px_2px_0px_#EF4444] hover:shadow-none">
                                            Hapus 🗑️
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>