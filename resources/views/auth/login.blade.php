<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-10">
        <h2 class="mt-6 text-3xl font-black text-gray-900 tracking-tight">
            Selamat Datang Kembali! 👋
        </h2>
        <p class="mt-2 text-sm text-gray-600">
            Silakan masuk untuk mengelola pesanan Anda.
        </p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Alamat Email')" class="font-bold text-gray-700" />
            <x-text-input id="email" class="block mt-2 w-full border-gray-300 focus:border-widi-red focus:ring-widi-red rounded-xl shadow-sm py-3 px-4" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@gmail.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Fungsi Mata Menggunakan Alpine.js --}}
        <div x-data="{ show: false }">
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Password')" class="font-bold text-gray-700" />
                @if (Route::has('password.request'))
                    <a class="text-sm font-bold text-widi-red hover:text-widi-darkred hover:underline transition" href="{{ route('password.request') }}">
                        {{ __('Lupa Password?') }}
                    </a>
                @endif
            </div>
            
            <div class="relative">
                <x-text-input id="password" class="block mt-2 w-full border-gray-300 focus:border-widi-red focus:ring-widi-red rounded-xl shadow-sm py-3 px-4"
                            x-bind:type="show ? 'text' : 'password'"
                            name="password"
                            required autocomplete="current-password" placeholder="••••••••" />
                
                {{-- Tombol Mata --}}
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center mt-2 text-gray-400 hover:text-widi-red transition-colors">
                    <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-widi-red shadow-sm focus:ring-widi-red h-5 w-5" name="remember">
                <span class="ms-2 text-sm text-gray-600 font-medium">{{ __('Ingat Saya') }}</span>
            </label>
        </div>

        <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-lg text-base font-black text-white bg-gradient-to-r from-widi-red to-widi-darkred hover:from-widi-darkred hover:to-widi-red transition-all duration-300 transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-widi-red uppercase tracking-wider">
            Masuk Sekarang
        </button>

        <div class="mt-8 text-center">
            <p class="text-sm text-gray-600 font-medium">
                Belum punya akun pelanggan? 
                <a href="{{ route('register') }}" class="font-black text-widi-red hover:text-widi-darkred hover:underline transition ml-1">
                    Daftar Disini Gratis!
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>