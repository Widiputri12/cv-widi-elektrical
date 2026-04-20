<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl uppercase tracking-tighter text-[#1A1A1A]">
                Upload <span class="text-[#D92323]">Dokumentasi</span>
            </h2>
            <a href="{{ route('gallery.index') }}" class="font-black text-[10px] px-4 py-2 rounded-lg text-[#1A1A1A] bg-white border-2 border-[#1A1A1A] shadow-[2px_2px_0px_#1A1A1A] hover:shadow-none transition-all uppercase">
                ⬅️ Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-[#F3F4F6] min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white border-4 border-[#1A1A1A] shadow-[8px_8px_0px_#1A1A1A] rounded-2xl overflow-hidden">
                <div class="p-4 bg-[#1A1A1A] border-b-4 border-[#1A1A1A]">
                    <h3 class="font-black text-white text-xs uppercase tracking-widest italic">📸 Tambah Dokumentasi Kegiatan Baru</h3>
                </div>

                <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                    @csrf
                    
                    <div>
                        <label class="block font-black text-xs uppercase tracking-widest text-[#1A1A1A] mb-2">Judul Kegiatan</label>
                        <input type="text" name="title" placeholder="Misal: Pemasangan Panel Listrik Gudang A" 
                            class="w-full border-4 border-[#1A1A1A] rounded-xl p-3 font-bold text-sm focus:ring-0 focus:border-[#D92323] placeholder-gray-300 shadow-[4px_4px_0px_#F3F4F6]" required>
                    </div>

                    <div>
                        <label class="block font-black text-xs uppercase tracking-widest text-[#1A1A1A] mb-2">Pilih File Foto</label>
                        <div class="relative border-4 border-dashed border-[#1A1A1A] rounded-xl p-4 bg-gray-50 hover:bg-red-50 transition-colors">
                            <input type="file" name="image" 
                                class="w-full text-xs font-black uppercase text-[#1A1A1A] file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-2 file:border-[#1A1A1A] file:text-[10px] file:font-black file:bg-[#FFD700] file:text-[#1A1A1A] hover:file:bg-yellow-400" required>
                            <p class="text-[9px] text-red-600 font-black mt-2 uppercase italic">* Format: JPG, PNG | Maksimal: 2MB</p>
                        </div>
                    </div>

                    <div>
                        <label class="block font-black text-xs uppercase tracking-widest text-[#1A1A1A] mb-2">Keterangan / Deskripsi</label>
                        <textarea name="description" rows="4" placeholder="Ceritakan singkat mengenai pengerjaan ini..."
                            class="w-full border-4 border-[#1A1A1A] rounded-xl p-3 font-bold text-sm focus:ring-0 focus:border-[#D92323] shadow-[4px_4px_0px_#F3F4F6]"></textarea>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full font-black text-sm py-4 rounded-xl text-white bg-[#D92323] border-4 border-[#1A1A1A] shadow-[6px_6px_0px_#1A1A1A] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all uppercase tracking-widest">
                            🔥 Upload Dokumentasi Sekarang
                        </button>
                    </div>
                </form>
            </div>

            {{-- Info Box --}}
            <div class="mt-8 p-4 bg-[#FFD700] border-4 border-[#1A1A1A] rounded-xl shadow-[4px_4px_0px_#1A1A1A]">
                <p class="font-black text-[10px] uppercase text-[#1A1A1A] leading-tight text-center">
                    ⚠️ Pastikan foto yang diunggah memiliki kualitas yang jelas sebagai bukti profesionalitas CV. WIDI ELEKTRICAL.
                </p>
            </div>

        </div>
    </div>
</x-app-layout>