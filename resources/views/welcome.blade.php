<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
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
                    },
                    boxShadow: {
                        'lembut': '0 20px 40px -15px rgba(0,0,0,0.05)',
                        'melayang': '0 25px 50px -12px rgba(217, 35, 35, 0.15)',
                    }
                }
            }
        }
    </script>
    <style>
        .bg-hero-pattern {
            background: linear-gradient(135deg, #D92323 0%, #9F1A1A 100%);
            position: relative;
        }
        /* Ornamen gelombang halus di background Hero */
        .bg-hero-pattern::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 100px;
            background: url('data:image/svg+xml;utf8,<svg viewBox="0 0 1440 120" xmlns="http://www.w3.org/2000/svg"><path d="M0,64L80,69.3C160,75,320,85,480,80C640,75,800,53,960,48C1120,43,1280,53,1360,58.7L1440,64L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z" fill="%23ffffff"/></svg>') no-repeat bottom;
            background-size: cover;
            z-index: 1;
        }
        
        /* Animasi Marquee (Running Text) */
        .marquee-container {
            overflow: hidden;
            white-space: nowrap;
            width: 100%;
            background: #FFD700;
            padding: 14px 0;
            position: relative;
            z-index: 20;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .marquee-content {
            display: inline-block;
            animation: marquee 25s linear infinite;
        }
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* Animasi Ornamen Melayang */
        .float-anim { animation: float 3.5s ease-in-out infinite; }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(5deg); }
        }   

        /* Scrollbar Modern & Luwes */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f8fafc; }
        ::-webkit-scrollbar-thumb { background: #D92323; border-radius: 10px; }
    </style>
</head>
<body class="bg-slate-50 font-sans text-gray-800 antialiased overflow-x-hidden" x-data="{ modalOpen: false, modalData: {} }">

    <nav x-data="{ open: false }" class="bg-white/95 backdrop-blur-md shadow-sm sticky top-0 z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center z-10">
                    <a href="/" class="flex items-center gap-3 group">
                        <img src="{{ asset('logo.png') }}" alt="Logo CV Widi Elektrical" class="h-10 w-auto group-hover:scale-105 transition-transform duration-300">
                        <div>
                            <h1 class="text-xl md:text-2xl font-black text-widi-dark leading-none tracking-tight">CV. WIDI</h1>
                            <span class="text-widi-red font-bold text-[10px] uppercase tracking-[0.2em] mt-0.5 block">Elektrical</span>
                        </div>
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="#tentang" class="text-sm font-bold text-gray-500 hover:text-widi-red transition">Profil</a>
                    <a href="#lokasi" class="text-sm font-bold text-gray-500 hover:text-widi-red transition">Lokasi</a>
                    <a href="#layanan" class="text-sm font-bold text-gray-500 hover:text-widi-red transition">Layanan</a>
                    <a href="#galeri" class="text-sm font-bold text-gray-500 hover:text-widi-red transition">Galeri</a>

                    @if (Route::has('login'))
                        <div class="flex items-center space-x-3 ml-4 pl-6 border-l border-gray-200">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="bg-widi-yellow text-widi-dark px-6 py-2.5 rounded-full font-black text-xs uppercase tracking-widest hover:shadow-lg hover:-translate-y-0.5 transition-all">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-bold text-widi-dark hover:text-widi-red transition">Masuk</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-widi-red text-white px-6 py-2.5 rounded-full font-black text-xs uppercase tracking-widest hover:bg-widi-darkred hover:shadow-lg hover:-translate-y-0.5 transition-all">Daftar</a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>

                <div class="-mr-2 flex items-center md:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-full text-widi-dark bg-gray-100 hover:bg-gray-200 transition">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <div :class="{'block': open, 'hidden': ! open}" class="md:hidden bg-white border-t border-gray-100 absolute w-full shadow-lg rounded-b-2xl">
            <div class="px-4 pt-2 pb-6 space-y-2">
                <a href="#tentang" class="block px-3 py-3 rounded-xl text-base font-bold text-gray-600 hover:text-widi-red hover:bg-red-50">Profil</a>
                <a href="#lokasi" class="block px-3 py-3 rounded-xl text-base font-bold text-gray-600 hover:text-widi-red hover:bg-red-50">Lokasi</a>
                <a href="#layanan" class="block px-3 py-3 rounded-xl text-base font-bold text-gray-600 hover:text-widi-red hover:bg-red-50">Layanan</a>
                <a href="#galeri" class="block px-3 py-3 rounded-xl text-base font-bold text-gray-600 hover:text-widi-red hover:bg-red-50">Galeri</a>
                @if (Route::has('login'))
                    <div class="border-t border-gray-100 pt-4 mt-2">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="block w-full text-center py-3 rounded-xl text-base font-bold text-widi-dark bg-widi-yellow">Ke Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="block px-3 py-3 rounded-xl text-base font-bold text-gray-600">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="block mt-2 w-full text-center py-3 rounded-xl text-base font-bold text-white bg-widi-red shadow-md">Daftar Sekarang</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </nav>

    <div class="bg-hero-pattern pt-24 pb-36 relative text-center overflow-hidden">
        
        <div class="absolute top-10 left-10 text-widi-yellow text-5xl float-anim opacity-80 z-0 cursor-default select-none">✦</div>
        <div class="absolute bottom-32 right-20 text-white text-6xl float-anim opacity-50 z-0 cursor-default select-none" style="animation-delay: 1s;">✦</div>
        <div class="absolute top-32 right-10 w-12 h-12 border-4 border-widi-yellow rounded-full float-anim z-0" style="animation-delay: 0.5s;"></div>
        <div class="absolute bottom-20 left-32 w-8 h-8 bg-widi-yellow/40 rounded-lg rotate-45 float-anim z-0" style="animation-delay: 1.5s;"></div>

        <div class="max-w-4xl mx-auto px-4 relative z-10">
            <span class="inline-block py-1.5 px-4 rounded-full bg-white/20 backdrop-blur-sm text-white text-xs font-bold uppercase tracking-widest mb-6 shadow-sm">
                ✨ Spesialis dalam instalasi listrik, perbaikan peralatan listrik, serta pemasangan dan perawatan AC (pendingin dan ventilasi udara) untuk kebutuhan rumah, gedung, dan industri.
            </span>
            <h2 class="text-4xl md:text-6xl font-black text-white mb-6 tracking-tight leading-tight drop-shadow-md">
                Ciptakan kenyamanan ruangan <br/> Dengan <span class="text-widi-yellow">kesejukan yang optimal dan tahan lama</span>
            </h2>
            <p class="text-white/90 text-lg md:text-xl mb-10 max-w-2xl mx-auto font-medium">
                CV. WIDI ELEKTRICAL menghadirkan layanan instalasi, perawatan, dan perbaikan AC dengan standar kerja profesional dan hasil optimal.
                Dengan harga yang transparan sejak awal, kami siap menjadi solusi terpercaya untuk kenyamanan ruangan Anda.

            </p>
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                <a href="#tentang" class="w-full sm:w-auto bg-widi-yellow text-widi-dark px-12 py-4 rounded-full font-black text-sm uppercase tracking-widest text-center shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all">
                    Profil
                </a>
                <a href="#layanan" class="w-full sm:w-auto bg-white/10 backdrop-blur-md text-white border border-white/30 px-12 py-4 rounded-full font-black text-sm uppercase tracking-widest text-center hover:bg-white/20 transition-all">
                    Pesan Sekarang
                </a>
            </div>
        </div>
    </div>

    <div class="marquee-container">
        <div class="marquee-content text-widi-dark font-black tracking-widest text-xs">
            <span class="mx-6">❄️ SERVICE AC PROFESIONAL</span> • 
            <span class="mx-6">🔧 TEKNISI BERPENGALAMAN</span> • 
            <span class="mx-6">⚡ RESPON CEPAT</span> • 
            <span class="mx-6">💯 100% BERGARANSI</span> • 
            <span class="mx-6">📍 COVERAGE KEDIRI RAYA</span> • 
            <span class="mx-6">❄️ SERVICE AC PROFESIONAL</span> • 
            <span class="mx-6">🔧 TEKNISI BERPENGALAMAN</span> • 
            <span class="mx-6">⚡ RESPON CEPAT</span> • 
            <span class="mx-6">💯 100% BERGARANSI</span> • 
            <span class="mx-6">📍 COVERAGE KEDIRI RAYA</span>
        </div>
    </div>
    
    <div id="tentang" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row gap-16 items-center">
                
                <div class="w-full lg:w-5/12">
                    <div class="relative rounded-[2.5rem] overflow-hidden shadow-melayang group">
                        <img src="{{ asset('logo.png') }}" alt="Misbahudi - Direktur CV Widi" class="w-full h-[550px] object-cover group-hover:scale-105 transition duration-700">
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-widi-dark/90 via-widi-dark/20 to-transparent flex flex-col justify-end p-8">
                            <h4 class="text-3xl font-black text-white tracking-tight">MISBAHUDI</h4>
                            <p class="text-widi-yellow font-bold text-xs uppercase tracking-widest mt-2">Direktur Utama</p>
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-7/12">
                    <span class="text-widi-red font-black text-[10px] uppercase tracking-[0.2em] mb-3 block">Profil Perusahaan</span>
                    <h2 class="text-4xl md:text-5xl font-black text-widi-dark tracking-tight mb-6 leading-tight">
                        Kenyamanan Anda Adalah <span class="text-widi-red">Prioritas Utama Kami</span>
                    </h2>
                    
                    <div class="text-gray-600 leading-relaxed mb-10 space-y-4 font-medium text-lg">
                        <p>CV. WIDI ELEKTRICAL adalah perusahaan yang bergerak di bidang jasa teknik dan kelistrikan yang didirikan pada tanggal 05 Maret 2025 dan berkedudukan di Kabupaten Kediri.</p>
                        <p>Kami hadir sebagai solusi profesional dalam bidang instalasi listrik, perbaikan peralatan listrik, konstruksi elektrikal, serta pemasangan sistem pendingin dan ventilasi udara (AC). Dengan mengedepankan kualitas, ketepatan, dan keselamatan kerja, kami siap melayani kebutuhan industri, instansi, maupun perorangan.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-6 bg-slate-50 rounded-3xl border border-gray-100 hover:shadow-lembut hover:-translate-y-1 transition-all duration-300">
                            <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center mb-4 text-widi-red">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </div>
                            <h4 class="font-black text-lg text-widi-dark mb-2">Visi Kami</h4>
                            <p class="text-sm text-gray-500 font-medium leading-relaxed">Menjadi perusahaan jasa teknik dan kelistrikan yang profesional, terpercaya, dan unggul dalam memberikan solusi terbaik di bidang instalasi dan perawatan elektrikal di Indonesia.</p>
                        </div>
                        <div class="p-6 bg-slate-50 rounded-3xl border border-gray-100 hover:shadow-lembut hover:-translate-y-1 transition-all duration-300">
                            <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center mb-4 text-widi-yellow">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            </div>
                            <h4 class="font-black text-lg text-widi-dark mb-2">Misi Kami</h4>
                            <p class="text-sm text-gray-500 font-medium leading-relaxed">dengan kualitas tinggi, mengutamakan kepuasan pelanggan, mengembangkan tenaga kerja profesional, serta menjalankan usaha dengan standar keselamatan dan kepercayaan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="lokasi" class="py-24 bg-slate-50 relative">
        <div class="absolute top-0 right-0 w-96 h-96 bg-red-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-yellow-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <span class="text-widi-red font-black text-[10px] uppercase tracking-[0.2em] mb-3 block">Kunjungi Kami</span>
                <h3 class="text-3xl md:text-4xl font-black text-widi-dark tracking-tight">Lokasi Kantor Pusat</h3>
            </div>
            
            <div class="flex flex-col lg:flex-row gap-8 items-stretch">
                <div class="w-full lg:w-2/3 bg-white p-3 rounded-[2.5rem] shadow-lembut">
                    <iframe 
                        src="https://maps.google.com/maps?q=Jl.%20Rahayu%20No.268,%20Sumber,%20Doko,%20Kec.%20Ngasem,%20Kabupaten%20Kediri,%20Jawa%20Timur&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                        width="100%" height="450" style="border:0; border-radius: 2rem;" allowfullscreen="" loading="lazy">
                    </iframe>
                </div>
                <div class="w-full lg:w-1/3 flex flex-col gap-6">
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-lembut border border-gray-100 flex-1 flex flex-col justify-center">
                        <div class="w-14 h-14 bg-red-50 text-widi-red rounded-full flex items-center justify-center mb-6">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </div>
                        <h4 class="font-black text-xl text-widi-dark mb-3">Alamat Lengkap</h4>
                        <p class="text-gray-500 font-medium leading-relaxed mb-8">
                            Jl. Rahayu No.268, Sumber, Doko, Kec. Ngasem, Kabupaten Kediri, Jawa Timur 64182
                        </p>
                        <a href="https://maps.google.com/maps?q=Jl.+Rahayu+No.268,+Sumber,+Doko,+Kec.+Ngasem,+Kabupaten+Kediri,+Jawa+Timur" target="_blank" class="w-full bg-widi-dark text-white py-3.5 rounded-full text-center font-bold text-sm hover:bg-gray-800 transition shadow-lg hover:-translate-y-1 hover:shadow-xl">Buka Petunjuk Arah</a>
                    </div>
                    
                    <div class="bg-widi-yellow p-8 rounded-[2rem] shadow-sm flex items-center gap-4">
                        <div class="bg-white/50 p-3 rounded-full">
                            <svg class="w-6 h-6 text-widi-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <h4 class="text-widi-dark font-black text-sm mb-1">Area Layanan</h4>
                            <p class="text-widi-dark/70 font-bold text-xs">Kediri Raya & Sekitarnya</p>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <div id="layanan" class="py-24 max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <span class="text-widi-red font-black text-[10px] uppercase tracking-[0.2em] mb-3 block">Jasa Kami</span>
            <h3 class="text-3xl md:text-4xl font-black text-widi-dark tracking-tight">Pilihan Layanan Terbaik</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($services as $service)
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-melayang hover:-translate-y-2 transition-all duration-300 flex flex-col justify-between h-full group overflow-hidden relative">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gray-100 group-hover:bg-widi-red transition-colors duration-300"></div>
                <div class="p-8">
                    <div class="w-12 h-12 bg-red-50 text-widi-red rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /></svg>
                    </div>
                    <h4 class="text-xl font-black text-widi-dark mb-3">{{ $service->name }}</h4>
                    <p class="text-gray-500 font-medium text-sm leading-relaxed mb-6">{{ Str::limit($service->description, 70) }}</p>
                    
                    <div class="pt-6 border-t border-gray-50">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Mulai Dari</span>
                        <span class="text-xl font-black text-widi-red">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="p-6 pt-0">
                    <a href="{{ route('dashboard') }}" class="block w-full bg-slate-50 text-widi-dark py-3.5 rounded-2xl text-center font-bold text-sm group-hover:bg-widi-dark group-hover:text-white transition-colors">Pesan Sekarang</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div id="galeri" class="py-24 bg-white border-t border-gray-100 relative">
        <div class="max-w-7xl mx-auto px-4 text-center relative z-10">
            <span class="text-widi-red font-black text-[10px] uppercase tracking-[0.2em] mb-3 block">Dokumentasi</span>
            <h3 class="text-3xl md:text-4xl font-black text-widi-dark tracking-tight mb-12">Galeri Hasil Kerja</h3>
            
            <div class="mb-12 inline-block relative">
                <select id="galleryFilter" onchange="filterGallery()" class="appearance-none bg-slate-50 border border-gray-200 text-widi-dark py-3.5 pl-6 pr-12 rounded-full font-bold text-sm cursor-pointer hover:bg-gray-100 transition focus:outline-none focus:ring-2 focus:ring-widi-red/20 shadow-sm">
                    <option value="all">Semua Kategori</option>
                    <option value="Service AC">Service AC</option>
                    <option value="Perbaikan">Perbaikan</option>
                    <option value="Bongkar Pasang">Bongkar Pasang</option>
                    <option value="Instalasi">Instalasi Baru</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="galleryGrid">
                @forelse($galleries as $gallery)
                    <div class="gallery-item rounded-[2rem] overflow-hidden bg-white shadow-lembut cursor-pointer group hover:shadow-xl hover:-translate-y-1 transition-all border border-gray-50" 
                        data-category="{{ $gallery->category }}"
                        @click="modalOpen = true; modalData = { 
                            title: '{{ str_replace(["'", "\r", "\n"], ["", " ", " "], $gallery->title) }}', 
                            desc: '{{ str_replace(["'", "\r", "\n"], ["", " ", " "], $gallery->description) }}', 
                            category: '{{ $gallery->category }}', 
                            date: '{{ $gallery->created_at->format('d M Y') }}',
                            img: '{{ asset('storage/' . $gallery->image_path) }}'
                        }">
                        <div class="h-64 overflow-hidden relative">
                            <span class="absolute top-4 right-4 bg-white/90 backdrop-blur text-widi-dark px-3 py-1.5 rounded-full font-bold text-[10px] uppercase z-10 shadow-sm">{{ $gallery->category }}</span>
                            <img src="{{ asset('storage/' . $gallery->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        </div>
                        <div class="p-6 text-left">
                            <h4 class="font-black text-widi-dark text-lg leading-tight">{{ $gallery->title }}</h4>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-slate-50 rounded-[2rem] p-16 border border-gray-100">
                        <p class="text-gray-400 font-bold uppercase tracking-widest text-sm">Belum ada dokumentasi.</p>
                    </div>
                @endforelse
            </div>
            <div id="noDataMessage" class="hidden py-16 text-gray-400 font-bold uppercase tracking-widest text-sm">Kategori tidak ditemukan.</div>
        </div>
    </div>

    <footer class="bg-widi-dark text-white pt-20 pb-10 rounded-t-[3rem] mt-10">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-12 text-center md:text-left">
            <div>
                 <div class="bg-white p-2.5 rounded-2xl inline-block mb-6 shadow-lg">
                     <img src="{{ asset('logo.png') }}" alt="Logo" class="h-10 w-auto">
                 </div>
                 <h2 class="text-xl font-black mb-3 tracking-tight">CV. WIDI ELEKTRICAL</h2>
                 <p class="text-gray-400 text-sm font-medium leading-relaxed max-w-xs mx-auto md:mx-0">Solusi terpercaya untuk kenyamanan dan pendingin ruangan Anda di Kediri Raya.</p>
            </div>
            <div>
                <h3 class="text-widi-yellow font-bold text-sm uppercase mb-6 tracking-widest">Hubungi Kami</h3>
                <p class="text-white text-sm font-bold mb-4 flex items-center justify-center md:justify-start gap-3">
                    <span class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-widi-red"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path></svg></span>
                    0812-3456-7890
                </p>
                <p class="text-gray-300 text-sm font-medium flex items-center justify-center md:justify-start gap-3">
                    <span class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-widi-red shrink-0"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg></span>
                    <span class="text-left">Jl. Rahayu No.268, Sumber, Doko, Kediri</span>
                </p>
            </div>
            <div>
                <h3 class="text-widi-yellow font-bold text-sm uppercase mb-6 tracking-widest">Jam Operasional</h3>
                <div class="space-y-4">
                    <div class="flex justify-between md:justify-start items-center gap-4 border-b border-gray-700/50 pb-3">
                        <span class="text-gray-300 text-sm font-medium w-28 text-left">Senin - Sabtu</span>
                        <span class="text-white text-sm font-bold bg-white/10 px-3 py-1 rounded-full">08.00 - 17.00 WIB</span>
                    </div>
                    <div class="flex justify-between md:justify-start items-center gap-4">
                        <span class="text-widi-red text-sm font-medium w-28 text-left">Minggu</span>
                        <span class="text-widi-red text-xs font-bold uppercase tracking-wider bg-red-900/30 px-3 py-1 rounded-full">Perjanjian</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 mt-16 pt-8 border-t border-white/10 text-center text-xs text-gray-500 font-medium">
            © {{ date('Y') }} CV. Widi Elektrical. All rights reserved.
        </div>
    </footer>

    <template x-teleport="body">
        <div x-show="modalOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm p-4" x-transition>
            <div @click.away="modalOpen = false" class="relative w-full max-w-4xl bg-white rounded-[2.5rem] shadow-2xl flex flex-col md:flex-row max-h-[90vh] overflow-hidden transform transition-all">
                
                <button @click="modalOpen = false" class="absolute top-4 right-4 bg-white/50 backdrop-blur text-widi-dark p-2 rounded-full z-10 hover:bg-white transition shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <div class="md:w-1/2 bg-gray-100 relative">
                    <img :src="modalData.img" class="w-full h-full object-cover">
                </div>

                <div class="md:w-1/2 p-8 md:p-10 overflow-y-auto bg-white flex flex-col">
                    <span class="bg-red-50 text-widi-red font-bold text-[10px] uppercase tracking-widest px-3 py-1.5 rounded-full inline-block mb-4 w-fit" x-text="modalData.category"></span>
                    <h3 class="text-3xl font-black text-widi-dark leading-tight mb-6" x-text="modalData.title"></h3>
                    <div class="w-12 h-1.5 bg-widi-red rounded-full mb-6"></div>
                    <p x-text="modalData.desc" class="text-gray-500 font-medium text-sm leading-relaxed mb-8 flex-grow"></p>
                    
                    <div class="flex items-center text-[11px] font-bold text-gray-400 uppercase tracking-widest pt-6 border-t border-gray-100">
                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span x-text="modalData.date"></span>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <script>
        function filterGallery() {
            var input = document.getElementById("galleryFilter");
            var filter = input.value; 
            var grid = document.getElementById("galleryGrid");
            var items = grid.getElementsByClassName("gallery-item");
            var noData = document.getElementById("noDataMessage");
            var count = 0;

            for (var i = 0; i < items.length; i++) {
                var category = items[i].getAttribute("data-category");
                if (filter === "all" || category === filter) {
                    items[i].style.display = ""; 
                    count++;
                } else {
                    items[i].style.display = "none";
                }
            }
            noData.style.display = count === 0 ? "block" : "none";
        }
    </script>
</body>
</html>