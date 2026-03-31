<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="{{ asset('logo.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        widi: {
                            red: '#D92323',    /* Merah Utama */
                            darkred: '#9F1A1A', /* Merah Gelap untuk Gradasi */
                            yellow: '#FFD700', /* Kuning Petir */
                        }
                    },
                    fontFamily: {
                        sans: ['Figtree', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        /* Container untuk logo biar bisa dikasih efek putar */
        .lightning-orbit-container {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            /* Memastikan orbit tidak terpotong */
            overflow: visible; 
        }

        /* Lingkaran Petir Utama (Emas) - Berputar Searah Jarum Jam */
        .lightning-orbit-container::before {
            content: '';
            position: absolute;
            width: 140%; /* Lebih besar dari logonya */
            height: 140%;
            border-radius: 50%;
            border: 4px solid transparent;
            border-top-color: #FFD700; /* Warna Emas */
            border-right-color: #FFD700;
            /* Efek Glow/Bersinar */
            box-shadow: 0 0 25px #FFD700, inset 0 0 15px #FFD700;
            animation: spin-lightning 4s linear infinite;
            z-index: 10;
        }

        /* Lingkaran Petir Kedua (Putih Tipis) - Berputar Berlawanan Arah (Biar dinamis) */
        .lightning-orbit-container::after {
            content: '';
            position: absolute;
            width: 130%;
            height: 130%;
            border-radius: 50%;
            border: 2px solid transparent;
            border-bottom-color: rgba(255, 255, 255, 0.7);
            border-left-color: rgba(255, 255, 255, 0.7);
            animation: spin-lightning-reverse 7s linear infinite;
            z-index: 10;
        }

        /* Animasi Putar */
        @keyframes spin-lightning {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes spin-lightning-reverse {
            0% { transform: rotate(360deg); }
            100% { transform: rotate(0deg); }
        }

        /* Pattern background halus untuk sisi kiri */
        .bg-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-50 font-sans">
    
    <div class="min-h-screen flex overflow-hidden">
        
        <div class="hidden lg:flex lg:w-5/12 bg-gradient-to-br from-widi-red to-widi-darkred items-center justify-center relative overflow-hidden">
            <div class="absolute inset-0 bg-pattern z-0"></div>

            <div class="relative z-20 flex flex-col items-center text-center px-8">
                <div class="lightning-orbit-container mb-20">
                    <img src="{{ asset('logo.png') }}" alt="Logo Widi Elektrical" class="w-64 h-auto relative z-30 bg-white/10 p-4 rounded-full backdrop-blur-sm shadow-2xl">
                </div>
                
                <h2 class="text-4xl font-black text-white uppercase tracking-widest mb-2 drop-shadow-lg relative z-30">
                    CV. WIDI <span class="text-widi-yellow">ELEKTRICAL</span>
                </h2>
                <p class="text-red-100 text-lg font-medium tracking-wider relative z-30">
                    Solusi Pendingin Profesional & Terpercaya
                </p>
            </div>

            <svg class="absolute bottom-0 left-0 w-full text-gray-50 h-24 z-10" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none">
                <polygon points="0,100 100,0 100,100"/>
            </svg>
        </div>

        <div class="w-full lg:w-7/12 flex items-center justify-center p-6 sm:p-12 bg-white relative">
            <div class="w-full max-w-md space-y-8 z-20">
                <div class="lg:hidden flex flex-col items-center justify-center mb-8">
                     <img src="{{ asset('logo.png') }}" alt="Logo" class="w-28 h-auto mb-4 bg-red-50 p-2 rounded-full shadow-md">
                     <h3 class="font-black text-widi-red text-xl">CV. WIDI ELEKTRICAL</h3>
                </div>

                {{ $slot }}

            </div>
             <svg class="lg:hidden absolute top-0 left-0 w-full text-widi-red h-16 z-0 opacity-10" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none">
                <polygon points="0,0 100,0 0,100"/>
            </svg>
        </div>

    </div>
</body>
</html>