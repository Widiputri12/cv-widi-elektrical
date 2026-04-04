<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl uppercase tracking-tighter text-[#1A1A1A]">
                Edit <span class="text-[#D92323]">Teknisi</span>
            </h2>
            <a href="{{ route('admin.technicians.index') }}" class="font-black text-xs px-6 py-3 rounded-xl text-[#1A1A1A] bg-white border-4 border-[#1A1A1A] shadow-[4px_4px_0px_#1A1A1A] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all uppercase">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-[#F3F4F6] min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white border-4 border-[#1A1A1A] shadow-[8px_8px_0px_#1A1A1A] rounded-3xl p-8">
                <div class="flex items-center gap-3 mb-6 border-b-4 border-gray-100 pb-4">
                    <div class="w-12 h-12 bg-[#FFD700] border-4 border-[#1A1A1A] rounded-full flex items-center justify-center font-black text-[#1A1A1A] text-xl">
                        ✏️
                    </div>
                    <div>
                        <h3 class="font-black text-xl uppercase italic text-[#1A1A1A]">Edit Data Personel</h3>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Update informasi teknisi: {{ $technician->name }}</p>
                    </div>
                </div>

                <form action="{{ route('admin.technicians.update', $technician->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-[11px] font-black uppercase text-[#1A1A1A] mb-2 tracking-widest">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $technician->name) }}" required 
                            class="w-full border-4 border-[#1A1A1A] rounded-xl p-4 text-sm font-bold focus:ring-0 focus:border-[#D92323] transition-all bg-gray-50 focus:bg-white">
                        @error('name') <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-black uppercase text-[#1A1A1A] mb-2 tracking-widest">Email Login</label>
                        <input type="email" name="email" value="{{ old('email', $technician->email) }}" required 
                            class="w-full border-4 border-[#1A1A1A] rounded-xl p-4 text-sm font-bold focus:ring-0 focus:border-[#D92323] transition-all bg-gray-50 focus:bg-white">
                        @error('email') <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-black uppercase text-[#1A1A1A] mb-2 tracking-widest">No. WhatsApp</label>
                        <input type="text" name="phone" value="{{ old('phone', $technician->phone) }}" required 
                            class="w-full border-4 border-[#1A1A1A] rounded-xl p-4 text-sm font-bold focus:ring-0 focus:border-[#D92323] transition-all bg-gray-50 focus:bg-white">
                        @error('phone') <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="p-4 bg-yellow-50 border-4 border-yellow-400 rounded-xl">
                        <label class="block text-[11px] font-black uppercase text-yellow-800 mb-2 tracking-widest">Password Baru (Opsional)</label>
                        <input type="password" name="password" placeholder="Kosongkan jika tidak ingin ganti password" 
                            class="w-full border-4 border-yellow-400 rounded-xl p-4 text-sm font-bold focus:ring-0 focus:border-yellow-600 transition-all bg-white">
                        <p class="text-[9px] text-yellow-600 font-bold mt-2 uppercase">*Hanya isi jika teknisi lupa password.</p>
                        @error('password') <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-4 border-t-4 border-gray-100 flex justify-end">
                        <button type="submit" class="w-full md:w-auto bg-[#D92323] text-white font-black px-12 py-4 rounded-xl border-4 border-[#1A1A1A] shadow-[4px_4px_0px_#1A1A1A] uppercase text-sm hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all">
                            💾 Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>