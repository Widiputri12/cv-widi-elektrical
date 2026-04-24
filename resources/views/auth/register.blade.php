<x-guest-layout>
    <div class="mb-8">
        <h2 class="mt-6 text-3xl font-black text-gray-900 tracking-tight">
            Buat Akun Baru 🚀
        </h2>
        <p class="mt-2 text-sm text-gray-600">
            Gabung sekarang untuk menikmati layanan service AC terbaik.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        {{-- Nama Lengkap --}}
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" class="font-bold text-gray-700" />
            <x-text-input id="name" class="block mt-2 w-full border-gray-300 focus:border-widi-red focus:ring-widi-red rounded-xl shadow-sm py-3 px-4" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Isi nama lengkap Anda" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        {{-- Email --}}
        <div>
            <x-input-label for="email" :value="__('Alamat Email (Khusus Gmail)')" class="font-bold text-gray-700" />
            <x-text-input id="email" class="block mt-2 w-full border-gray-300 focus:border-widi-red focus:ring-widi-red rounded-xl shadow-sm py-3 px-4" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="contoh@gmail.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <p class="text-xs text-gray-500 mt-1 italic">*Wajib menggunakan akhiran @gmail.com</p>
        </div>

        {{-- WhatsApp --}}
        <div>
            <x-input-label for="phone" :value="__('Nomor WhatsApp')" class="font-bold text-gray-700" />
            <x-text-input id="phone" class="block mt-2 w-full border-gray-300 focus:border-widi-red focus:ring-widi-red rounded-xl shadow-sm py-3 px-4" type="text" inputmode="numeric" pattern="[0-9]*" name="phone" :value="old('phone')" placeholder="Contoh: 08123456789" required />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        {{-- Alamat --}}
        <div>
             <x-input-label for="address" :value="__('Alamat Lengkap')" class="font-bold text-gray-700" />
             <textarea id="address" name="address" class="block mt-2 w-full border-gray-300 focus:border-widi-red focus:ring-widi-red rounded-xl shadow-sm py-3 px-4" rows="3" placeholder="Nama Jalan, Nomor Rumah, RT/RW, Kelurahan, Patokan..." required>{{ old('address') }}</textarea>
             <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>

        {{-- Password dengan Mata --}}
        <div x-data="{ show: false }">
            <x-input-label for="password" :value="__('Password')" class="font-bold text-gray-700" />
            <div class="relative">
                <x-text-input id="password" class="block mt-2 w-full border-gray-300 focus:border-widi-red focus:ring-widi-red rounded-xl shadow-sm py-3 px-4"
                                x-bind:type="show ? 'text' : 'password'"
                                name="password"
                                required autocomplete="new-password" placeholder="Minimal 8 karakter" />
                
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

        {{-- Konfirmasi Password dengan Mata --}}
        <div x-data="{ showConfirm: false }">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="font-bold text-gray-700" />
            <div class="relative">
                <x-text-input id="password_confirmation" class="block mt-2 w-full border-gray-300 focus:border-widi-red focus:ring-widi-red rounded-xl shadow-sm py-3 px-4"
                                x-bind:type="showConfirm ? 'text' : 'password'"
                                name="password_confirmation"
                                required autocomplete="new-password" placeholder="Ketik ulang password" />
                
                <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 pr-4 flex items-center mt-2 text-gray-400 hover:text-widi-red transition-colors">
                    <svg x-show="!showConfirm" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="showConfirm" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-lg text-base font-black text-white bg-gradient-to-r from-widi-red to-widi-darkred hover:from-widi-darkred hover:to-widi-red transition-all duration-300 transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-widi-red uppercase tracking-wider mt-8">
            Daftar Sekarang
        </button>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600 font-medium">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-black text-widi-red hover:text-widi-darkred hover:underline transition ml-1">
                    Login Disini
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>