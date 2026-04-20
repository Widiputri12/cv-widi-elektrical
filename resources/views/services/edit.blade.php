<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl uppercase tracking-tighter text-[#1A1A1A]">
                Edit <span class="text-[#D92323]">Layanan</span>
            </h2>
            <a href="{{ route('services.index') }}" class="font-black text-[10px] px-4 py-2 rounded-lg text-[#1A1A1A] bg-white border-2 border-[#1A1A1A] shadow-[2px_2px_0px_#1A1A1A] hover:shadow-none transition-all uppercase">
                ⬅️ Batal
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-[#F3F4F6] min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white border-4 border-[#1A1A1A] shadow-[8px_8px_0px_#FFD700] rounded-2xl overflow-hidden">
                <div class="p-4 bg-[#FFD700] border-b-4 border-[#1A1A1A]">
                    <h3 class="font-black text-[#1A1A1A] text-xs uppercase tracking-widest italic">✏️ Perbarui Data: {{ $service->name }}</h3>
                </div>

                <form action="{{ route('services.update', $service->id) }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block font-black text-xs uppercase tracking-widest text-[#1A1A1A] mb-2">Nama Layanan</label>
                        <input type="text" name="name" value="{{ $service->name }}" 
                            class="w-full border-4 border-[#1A1A1A] rounded-xl p-3 font-bold text-sm focus:ring-0 focus:border-[#D92323] shadow-[4px_4px_0px_#F3F4F6]" required>
                    </div>

                    <div>
                        <label class="block font-black text-xs uppercase tracking-widest text-[#1A1A1A] mb-2">Harga Jasa (Rp)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center font-black text-[#1A1A1A]">Rp</span>
                            <input type="number" name="price" value="{{ $service->price }}" 
                                class="w-full border-4 border-[#1A1A1A] rounded-xl p-3 pl-10 font-bold text-sm focus:ring-0 focus:border-[#D92323] shadow-[4px_4px_0px_#F3F4F6]" required>
                        </div>
                    </div>

                    <div>
                        <label class="block font-black text-xs uppercase tracking-widest text-[#1A1A1A] mb-2">Deskripsi Layanan</label>
                        <textarea name="description" rows="4" 
                            class="w-full border-4 border-[#1A1A1A] rounded-xl p-3 font-bold text-sm focus:ring-0 focus:border-[#D92323] shadow-[4px_4px_0px_#F3F4F6]" required>{{ $service->description }}</textarea>
                    </div>

                    <div class="pt-4 flex gap-4">
                        <button type="submit" class="flex-1 font-black text-sm py-4 rounded-xl text-white bg-[#1A1A1A] border-4 border-[#1A1A1A] shadow-[6px_6px_0px_#D92323] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all uppercase tracking-widest">
                            💾 Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>