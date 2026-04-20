<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl uppercase tracking-tighter text-[#1A1A1A]">
                Galeri <span class="text-[#D92323]">Kegiatan</span>
            </h2>
            @auth
            <a href="{{ route('gallery.create') }}" class="font-black text-xs px-6 py-3 rounded-xl text-white bg-[#D92323] border-4 border-[#1A1A1A] shadow-[4px_4px_0px_#1A1A1A] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all uppercase">
                ➕ Upload Dokumentasi
            </a>
            @endauth
        </div>
    </x-slot>

    <div class="py-10 bg-[#F3F4F6] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center mb-12">
                <h3 class="font-black text-4xl uppercase tracking-tighter text-[#1A1A1A] mb-2">
                    Bukti Nyata <span class="bg-[#FFD700] px-2">Kualitas Kami</span>
                </h3>
                <p class="font-bold text-gray-500 italic uppercase text-[10px] tracking-[0.2em]">Dokumentasi pengerjaan teknisi profesional CV. Widi Elektrical</p>
            </div>

            @if(session('success'))
                <div class="mb-8 p-4 bg-green-100 border-4 border-green-600 text-green-700 font-black uppercase text-xs rounded-xl shadow-[4px_4px_0px_#16a34a] text-center">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @forelse($galleries as $gallery)
                <div class="group relative bg-white border-4 border-[#1A1A1A] shadow-[10px_10px_0px_#FFD700] rounded-2xl overflow-hidden hover:translate-x-1 hover:translate-y-1 hover:shadow-none transition-all duration-300">
                    
                    {{-- Container Foto --}}
                    <div class="h-64 overflow-hidden border-b-4 border-[#1A1A1A]">
                        <img src="{{ asset('storage/' . $gallery->image_path) }}" 
                             alt="{{ $gallery->title }}" 
                             class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500 scale-100 group-hover:scale-110">
                    </div>

                    {{-- Konten Teks --}}
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-black text-lg uppercase leading-none text-[#1A1A1A]">{{ $gallery->title }}</h4>
                            <span class="text-[9px] font-black bg-[#1A1A1A] text-white px-2 py-1 rounded uppercase tracking-tighter">
                                {{ $gallery->created_at->format('Y') }}
                            </span>
                        </div>
                        <p class="text-[10px] font-bold text-gray-600 leading-relaxed mb-4">
                            {{ $gallery->description }}
                        </p>
                        
                        <div class="flex justify-between items-center pt-4 border-t-2 border-gray-100">
                            <span class="text-[9px] font-black text-[#D92323] italic uppercase">Verified Work ✓</span>
                            <span class="text-[8px] font-bold text-gray-400 uppercase">{{ $gallery->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-20 text-center">
                    <div class="inline-block p-10 bg-white border-4 border-dashed border-gray-300 rounded-3xl">
                        <p class="font-black text-gray-300 uppercase tracking-widest italic">Belum ada foto dokumentasi tersedia</p>
                    </div>
                </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>