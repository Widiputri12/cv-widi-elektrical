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

        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" class="font-bold text-gray-700" />
            <x-text-input id="name" class="block mt-2 w-full border-gray-300 focus:border-widi-red focus:ring-widi-red rounded-xl shadow-sm py-3 px-4" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Isi nama lengkap Anda" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Alamat Email (Khusus Gmail)')" class="font-bold text-gray-700" />
            <x-text-input id="email" class="block mt-2 w-full border-gray-300 focus:border-widi-red focus:ring-widi-red rounded-xl shadow-sm py-3 px-4" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="contoh@gmail.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <p class="text-xs text-gray-500 mt-1 italic">*Wajib menggunakan akhiran @gmail.com</p>
        </div>

        <div>
            <x-input-label for="phone" :value="__('Nomor WhatsApp')" class="font-bold text-gray-700" />
            <x-text-input id="phone" class="block mt-2 w-full border-gray-300 focus:border-widi-red focus:ring-widi-red rounded-xl shadow-sm py-3 px-4" type="text" inputmode="numeric" pattern="[0-9]*" name="phone" :value="old('phone')" placeholder="Contoh: 08123456789" required />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <div>
             <x-input-label for="address" :value="__('Alamat Lengkap')" class="font-bold text-gray-700" />
             <textarea id="address" name="address" class="block mt-2 w-full border-gray-300 focus:border-widi-red focus:ring-widi-red rounded-xl shadow-sm py-3 px-4" rows="3" placeholder="Nama Jalan, Nomor Rumah, RT/RW, Kelurahan, Patokan..." required>{{ old('address') }}</textarea>
             <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" class="font-bold text-gray-700" />
            <x-text-input id="password" class="block mt-2 w-full border-gray-300 focus:border-widi-red focus:ring-widi-red rounded-xl shadow-sm py-3 px-4"
                            type="password"
                            name="password"
                            required autocomplete="new-password" placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="font-bold text-gray-700" />
            <x-text-input id="password_confirmation" class="block mt-2 w-full border-gray-300 focus:border-widi-red focus:ring-widi-red rounded-xl shadow-sm py-3 px-4"
                            type="password"
                            name="password_confirmation"
                            required autocomplete="new-password" placeholder="Ketik ulang password" />
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