<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CV. Widi Elektrical - Solusi Service AC Terpercaya</title>
    <link rel="icon" href="{{ asset('logo.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        widi: {
                            red: '#D92323',    
                            darkred: '#9F1A1A',
                            yellow: '#FFD700', 
                            dark: '#1A1A1A',   
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .bg-hero-pattern {
            background: linear-gradient(135deg, #D92323 0%, #9F1A1A 100%);
        }
        html {
            scroll-behavior: smooth;
        }
        .btn-widi {
            border: 2px solid #1A1A1A;
            box-shadow: 3px 3px 0px #1A1A1A;
            transition: all 0.2s ease-in-out;
        }
        .btn-widi:hover {
            box-shadow: 0px 0px 0px #1A1A1A;
            transform: translate(3px, 3px);
        }
        .custom-scrollbar-h::-webkit-scrollbar { height: 6px; }
        .custom-scrollbar-h::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        .custom-scrollbar-h::-webkit-scrollbar-thumb { background: #D92323; border-radius: 10px; }
        
        @keyframes bounce-x {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(5px); }
        }
        .animate-bounce-x { animation: bounce-x 1s infinite; }
    </style>
</head>
<body class="bg-gray-50 font-sans" x-data="{ modalOpen: false, modalData: {} }">

    <nav x-data="{ open: false }" class="bg-white border-b-4 border-widi-red shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <a href="/" class="flex items-center gap-3 group">
                        <img src="{{ asset('logo.png') }}" alt="Logo CV Widi Elektrical" class="h-12 w-auto group-hover:scale-105 transition-transform">
                        <div>
                            <h1 class="text-xl md:text-2xl font-black text-widi-dark leading-none tracking-tight">CV. WIDI</h1>
                            <span class="text-[10px] font-black text-widi-yellow bg-widi-dark px-1.5 py-0.5 rounded uppercase tracking-widest mt-0.5 inline-block">
                                Elektrical
                            </span>
                        </div>
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-6">
                    <a href="#layanan" class="text-gray-600 hover:text-widi-red font-bold transition duration-300">Layanan</a>
                    <a href="#galeri" class="text-gray-600 hover:text-widi-red font-bold transition duration-300">Galeri</a>

                    @if (Route::has('login'))
                        <div class="flex items-center space-x-3 ml-4 border-l pl-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="btn-widi bg-widi-yellow text-widi-dark px-5 py-2 rounded-lg font-black text-sm uppercase tracking-wider">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-widi-dark font-bold hover:text-widi-red transition">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn-widi bg-widi-red text-white px-5 py-2 rounded-lg font-black text-sm uppercase tracking-wider hover:bg-widi-darkred">
                                        Daftar
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>

                <div class="-mr-2 flex items-center md:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-widi-red hover:bg-gray-100 focus:outline-none transition">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div :class="{'block': open, 'hidden': ! open}" class="md:hidden bg-white border-t border-gray-100">
            <div class="pt-2 pb-3 space-y-1 px-4">
                <a href="#layanan" class="block py-2 text-base font-bold text-gray-800 hover:text-widi-red">Layanan</a>
                <a href="#galeri" class="block py-2 text-base font-bold text-gray-800 hover:text-widi-red">Galeri</a>
                @if (Route::has('login'))
                    <div class="border-t pt-2 mt-2">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="block py-2 text-base font-bold text-widi-red">Ke Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="block py-2 text-base font-bold text-gray-800">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="block py-2 text-base font-bold text-widi-red">Daftar Sekarang</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </nav>

    <div class="bg-hero-pattern py-24 md:py-32 relative overflow-hidden">
        <div class="max-w-5xl mx-auto px-4 text-center relative z-10">
            <div class="flex justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-widi-yellow animate-bounce drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <h2 class="text-4xl md:text-6xl font-black text-white mb-6 tracking-tight leading-tight drop-shadow-lg">
                Solusi Pendingin Ruangan Anda <br/> dengan <span class="text-widi-yellow underline decoration-widi-yellow decoration-4 underline-offset-8">Cepat & Tepat</span>
            </h2>
            <p class="text-red-50 text-lg md:text-xl mb-10 max-w-3xl mx-auto font-medium">
                Kami menjamin layanan service AC yang profesional, jujur, and bergaransi. Teknisi berpengalaman siap datang ke lokasi Anda.
            </p>
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                <a href="#layanan" class="btn-widi w-full sm:w-auto bg-widi-yellow text-widi-dark px-8 py-4 rounded-xl font-black text-lg">
                    PESAN LAYANAN
                </a>
                <a href="#galeri" class="btn-widi w-full sm:w-auto bg-white text-widi-dark px-8 py-4 rounded-xl font-black text-lg">
                    LIHAT GALERI
                </a>
            </div>
        </div>
        <svg class="absolute -bottom-10 -left-10 w-64 h-64 text-white opacity-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zm0 7l-10 5 10 5 10-5-10-5zm0 7l-10 5 10 5 10-5-10-5z"></path></svg>
    </div>
    
    <div id="layanan" class="py-20 max-w-7xl mx-auto px-4 bg-gray-50">
        <div class="text-center mb-16">
            <h3 class="text-3xl md:text-4xl font-black text-widi-dark tracking-tight uppercase">Pilihan Layanan Terbaik</h3>
            <div class="w-24 h-1.5 bg-widi-red mx-auto mt-4 rounded-full"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($services as $service)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 border-t-4 border-t-widi-red overflow-hidden flex flex-col justify-between h-full group hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                         <h4 class="text-xl font-black text-widi-dark leading-tight">{{ $service->name }}</h4>
                         <div class="p-2 bg-red-50 text-widi-red rounded-lg group-hover:scale-110 transition">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                              </svg>
                         </div>
                    </div>
                    
                    <p class="text-gray-500 text-sm mb-6 h-12 overflow-hidden">{{ Str::limit($service->description, 80) }}</p>
                    
                    <div class="bg-gray-50 p-4 rounded-xl flex justify-between items-center border border-gray-200">
                        <div>
                            <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Harga</span>
                            <span class="text-lg font-black text-widi-red">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                        </div>
                        <div class="text-right border-l border-gray-200 pl-4">
                             <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Estimasi</span>
                             <span class="text-sm font-black text-widi-dark flex items-center justify-end">
                                {{ $service->duration }} Mnt
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 bg-white">
                    <a href="{{ route('dashboard') }}" class="btn-widi flex items-center justify-center w-full bg-widi-dark text-white py-3 rounded-xl font-black text-sm uppercase tracking-wider hover:bg-black transition-all">
                        Pesan Sekarang
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div id="galeri" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            
            <div class="text-center mb-12">
                <h3 class="text-3xl md:text-4xl font-black text-widi-dark tracking-tight uppercase">Galeri Dokumentasi</h3>
                <div class="w-24 h-1.5 bg-widi-red mx-auto mt-4 rounded-full mb-6"></div>
                <p class="text-gray-500 font-medium mb-8">Bukti nyata pengerjaan teknisi profesional CV. Widi Elektrical.</p>

                <div class="inline-block relative w-64 text-left">
                    <select id="galleryFilter" onchange="filterGallery()" class="block appearance-none w-full bg-white border-2 border-widi-dark text-widi-dark py-3.5 px-5 pr-8 rounded-xl font-black cursor-pointer shadow-[3px_3px_0px_#1A1A1A] focus:outline-none hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_#1A1A1A] transition-all">
                        <option value="all">📂 SEMUA KATEGORI</option>
                        <option value="Service AC">❄️ SERVICE AC</option>
                        <option value="Perbaikan">🔧 PERBAIKAN</option>
                        <option value="Bongkar Pasang">🏗️ BONGKAR PASANG</option>
                        <option value="Instalasi">✨ INSTALASI BARU</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-widi-dark">
                        <svg class="fill-current h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="galleryGrid">
                @forelse($galleries as $gallery)
                    <div class="gallery-item group rounded-2xl overflow-hidden border-2 border-gray-100 relative bg-white transition-all hover:border-widi-dark hover:shadow-[5px_5px_0px_#1A1A1A] cursor-pointer" 
                        data-category="{{ $gallery->category }}"
                        @click="modalOpen = true; modalData = { 
                            title: '{{ str_replace(["'", "\r", "\n"], ["", " ", " "], $gallery->title) }}', 
                            desc: '{{ str_replace(["'", "\r", "\n"], ["", " ", " "], $gallery->description) }}', 
                            category: '{{ $gallery->category }}', 
                            date: '{{ $gallery->created_at->format('d M Y') }}',
                            img: '{{ asset('storage/' . $gallery->image_path) }}'
                        }">
                        
                        <div class="h-64 overflow-hidden relative border-b-2 border-gray-100 group-hover:border-widi-dark">
                            <img src="{{ asset('storage/' . $gallery->image_path) }}" 
                                alt="{{ $gallery->title }}" 
                                class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
                            
                            <div class="absolute inset-0 bg-widi-dark bg-opacity-60 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                <span class="bg-widi-yellow text-widi-dark font-black px-5 py-2 rounded-lg border-2 border-widi-dark shadow-[2px_2px_0px_#1A1A1A] transform translate-y-4 group-hover:translate-y-0 transition duration-300 uppercase tracking-widest text-xs">
                                    LIHAT DETAIL
                                </span>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="flex justify-between items-start mb-3">
                                <h4 class="font-black text-lg text-widi-dark uppercase tracking-tight leading-tight">{{ $gallery->title }}</h4>
                                <span class="text-[9px] font-black uppercase bg-red-50 text-widi-red border border-red-100 px-2 py-1 rounded">
                                    {{ $gallery->category }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 leading-relaxed font-medium">
                                {{ Str::limit($gallery->description, 60) }}
                            </p>
                            
                            <p class="text-[9px] font-black text-widi-red mt-4 uppercase italic">Klik untuk detail lengkap</p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                        <p class="text-gray-500 font-bold">Belum ada dokumentasi.</p>
                    </div>
                @endforelse
            </div>
            
            <div id="noDataMessage" class="hidden text-center py-16 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 mt-8">
                <p class="text-gray-500 font-bold text-lg">Tidak ada foto untuk kategori ini.</p>
            </div>

        </div>
    </div>


    <template x-teleport="body">
        <div x-show="modalOpen" 
             class="fixed inset-0 z-[100] flex items-center justify-center px-4 py-12 overflow-hidden bg-black/80" 
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            
            <div @click.away="modalOpen = false" 
                 class="relative w-full max-w-4xl bg-white border-4 border-widi-dark shadow-[10px_10px_0px_#D92323] rounded-3xl overflow-hidden flex flex-col md:flex-row max-h-[90vh]"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
                
                <button @click="modalOpen = false" class="absolute top-4 right-4 z-10 bg-widi-red text-white p-2 rounded-full border-2 border-widi-dark hover:scale-110 transition active:scale-95">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <div class="md:w-3/5 bg-gray-100 flex items-center justify-center p-2">
                    <img :src="modalData.img" :alt="modalData.title" class="w-full h-full object-contain rounded-xl">
                </div>

                <div class="md:w-2/5 p-8 flex flex-col h-full bg-white overflow-y-auto custom-scrollbar">
                    <span class="inline-block px-3 py-1 bg-red-50 text-widi-red text-[10px] font-black rounded-lg uppercase w-fit mb-4" x-text="modalData.category"></span>
                    <h3 class="text-2xl font-black text-widi-dark uppercase leading-tight mb-4" x-text="modalData.title"></h3>
                    <div class="w-12 h-1.5 bg-widi-red mb-6 rounded-full"></div>
                    
                    <div class="text-gray-600 font-medium text-sm leading-relaxed mb-8 flex-grow">
                        <p x-text="modalData.desc" class="whitespace-pre-line"></p>
                    </div>

                    <div class="pt-6 border-t border-gray-100 flex justify-between items-center text-[10px] text-gray-400 font-bold uppercase tracking-wider">
                        <span class="flex items-center"><svg class="w-3.5 h-3.5 mr-1.5 text-widi-yellow" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> Verified</span>
                        <span x-text="modalData.date"></span>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <footer class="bg-widi-dark text-white pt-16 pb-8 border-t-8 border-widi-red">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-12 text-center md:text-left">
            
            <div class="flex flex-col items-center md:items-start">
                 <div class="bg-white p-2 rounded-xl mb-4 inline-block">
                     <img src="{{ asset('logo.png') }}" alt="Logo" class="h-14 w-auto object-contain">
                 </div>
                 <h2 class="text-2xl font-black mb-2 tracking-tight">CV. WIDI <span class="text-widi-red">ELEKTRICAL</span></h2>
                 <p class="text-gray-400 text-sm leading-relaxed font-medium">Mitra terpercaya untuk kenyamanan dan pendingin ruangan Anda. Profesional, Cepat, dan Bergaransi.</p>
            </div>

            <div>
                <h3 class="text-lg font-black mb-6 text-widi-yellow uppercase tracking-widest">Hubungi Kami</h3>
                <p class="mb-4 flex justify-center md:justify-start items-center hover:text-widi-yellow transition cursor-pointer font-medium text-gray-300">
                    <svg class="h-5 w-5 mr-3 text-widi-red" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg> 
                    0812-3456-7890
                </p>
                <p class="flex justify-center md:justify-start items-center hover:text-widi-yellow transition cursor-pointer font-medium text-gray-300">
                    <svg class="h-5 w-5 mr-3 text-widi-red" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg> 
                    Jl. Rahayu No.270, Sumber, Doko, Kec. Ngasem, Kabupaten Kediri, Jawa Timur 64182
                </p>
            </div>

            <div>
                <h3 class="text-lg font-black mb-6 text-widi-yellow uppercase tracking-widest">Jam Operasional</h3>
                <ul class="space-y-3 text-sm text-gray-300 font-medium">
                    <li class="flex justify-between md:justify-start"><span class="w-28 font-bold text-white">Senin - Sabtu:</span> <span>08.00 - 17.00 WIB</span></li>
                    <li class="flex justify-between md:justify-start text-widi-red"><span class="w-28 font-bold">Minggu:</span> <span>Libur / Perjanjian</span></li>
                </ul>
            </div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 mt-12 pt-8 border-t border-gray-800 text-center text-xs text-gray-500 font-bold tracking-wider uppercase">
            <p>&copy; {{ date('Y') }} CV. Widi Elektrical. All rights reserved. | <a href="#" class="hover:text-widi-yellow transition">Privacy Policy</a></p>
        </div>
    </footer>

    <script>
        function filterGallery() {
            var input = document.getElementById("galleryFilter");
            var filter = input.value.trim(); 
            var grid = document.getElementById("galleryGrid");
            var items = grid.getElementsByClassName("gallery-item");
            var noData = document.getElementById("noDataMessage");
            var count = 0;

            for (var i = 0; i < items.length; i++) {
                var category = items[i].getAttribute("data-category");

                if (filter === "all" || category === filter) {
                    items[i].style.display = ""; 
                    items[i].style.opacity = "0";
                    setTimeout((function(el) { return function() { el.style.opacity = "1"; el.style.transform = "scale(1)"; } })(items[i]), 50);
                    count++;
                } else {
                    items[i].style.opacity = "0";
                    items[i].style.transform = "scale(0.95)";
                    setTimeout((function(el) { return function() { el.style.display = "none"; } })(items[i]), 200);
                }
            }

            setTimeout(function() {
                if (count === 0) {
                    noData.style.display = "block";
                } else {
                    noData.style.display = "none";
                }
            }, 250);
        }
    </script>
</body>
</html>