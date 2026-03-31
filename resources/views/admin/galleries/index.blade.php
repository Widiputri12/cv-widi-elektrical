<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-xl text-gray-800 leading-tight">
                {{ __('Verifikasi Galeri') }}
            </h2>
            <span class="bg-gray-800 text-white text-xs font-bold px-3 py-1 rounded-full">
                {{ $galleries->count() }} Foto Masuk
            </span>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="text-xl mr-2">✅</span>
                        <span class="font-bold">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-600 uppercase tracking-wider">
                                    FOTO
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-600 uppercase tracking-wider">
                                    DETAIL
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-black text-gray-600 uppercase tracking-wider">
                                    STATUS
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-black text-gray-600 uppercase tracking-wider">
                                    AKSI
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($galleries as $gallery)
                            <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <img class="h-20 w-20 rounded-xl object-cover border-2 border-gray-100 shadow-sm" src="{{ asset('storage/' . $gallery->image_path) }}" alt="Foto">
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900 mb-1">{{ $gallery->title }}</div>
                                    <div class="text-xs text-gray-500 mb-2 truncate max-w-xs bg-gray-50 p-1 rounded">
                                        {{ $gallery->description }}
                                    </div>
                                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">
                                        {{ $gallery->created_at->format('d M Y • H:i') }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($gallery->status == 'pending')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-black rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200 shadow-sm">
                                            ⏳ MENUNGGU
                                        </span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-black rounded-full bg-green-100 text-green-800 border border-green-200 shadow-sm">
                                            ✓ TAYANG
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex flex-col space-y-2 items-center justify-center">
                                        
                                        @if($gallery->status == 'pending')
                                            <form action="{{ route('admin.galleries.approve', $gallery->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="w-28 bg-green-200 hover:bg-green-300 text-green-900 px-4 py-2 rounded-lg text-xs font-bold border border-green-300 shadow-sm transition flex items-center justify-center gap-2">
                                                    <span>✅</span> SETUJUI
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('admin.galleries.destroy', $gallery->id) }}" method="POST" onsubmit="return confirm('Hapus foto ini selamanya?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-28 bg-red-200 hover:bg-red-300 text-red-900 px-4 py-2 rounded-lg text-xs font-bold border border-red-300 shadow-sm transition flex items-center justify-center gap-2">
                                                <span>🗑️</span> HAPUS
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <p class="font-medium">Belum ada foto masuk.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>