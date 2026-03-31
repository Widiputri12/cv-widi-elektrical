<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl text-gray-800 leading-tight">
            {{ __('UPLOAD DOKUMENTASI KEGIATAN') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border-t-4 border-red-600 shadow-xl rounded-lg p-6">
                
                <h3 class="text-lg font-bold mb-4 flex items-center">
                    <span class="bg-yellow-400 w-2 h-6 mr-2"></span>
                    Tambah Foto Baru
                </h3>

                <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-bold text-sm text-gray-700 mb-1">Judul Kegiatan</label>
                        <input type="text" name="title" class="w-full border-gray-300 rounded shadow-sm focus:border-red-500 focus:ring focus:ring-red-200" placeholder="Contoh: Perbaikan AC di Kantor Pos" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold text-sm text-gray-700 mb-1">Pilih Foto</label>
                        <input type="file" name="image" class="w-full border p-2 border-gray-300 rounded shadow-sm bg-gray-50 text-sm" required>
                        <p class="text-xs text-red-500 mt-1">*Format: JPG, PNG. Maks: 2MB</p>
                    </div>

                    <div class="mb-6">
                        <label class="block font-bold text-sm text-gray-700 mb-1">Keterangan (Opsional)</label>
                        <textarea name="description" rows="3" class="w-full border-gray-300 rounded shadow-sm focus:border-red-500 focus:ring focus:ring-red-200" placeholder="Ceritakan sedikit tentang foto ini..."></textarea>
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit" class="bg-black hover:bg-red-700 text-white font-bold py-2 px-6 rounded shadow transition">
                            UPLOAD SEKARANG
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>