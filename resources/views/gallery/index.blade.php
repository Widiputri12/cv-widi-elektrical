<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Galeri Kegiatan - CV. Widi Elektrical</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

    <nav class="bg-white shadow-md mb-8">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-black text-red-600">CV. WIDI <span class="text-yellow-500">GALERI</span></h1>
            <div class="space-x-4">
                <a href="{{ url('/') }}" class="text-gray-600 font-bold hover:text-black">Home</a>
                @auth
                    <a href="{{ route('gallery.create') }}" class="bg-black text-white px-4 py-2 rounded text-sm font-bold hover:bg-gray-800">+ Upload Foto</a>
                    <a href="{{ url('/dashboard') }}" class="text-red-600 font-bold">Dashboard</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-black text-gray-900">Dokumentasi Pekerjaan Kami</h2>
            <div class="w-24 h-1 bg-red-600 mx-auto mt-2"></div>
            <p class="text-gray-600 mt-2">Bukti nyata kualitas layanan teknisi profesional kami.</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 text-center">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($galleries as $gallery)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden group hover:shadow-2xl transition duration-300">
                <div class="h-48 overflow-hidden relative">
                    <img src="{{ asset('storage/' . $gallery->image_path) }}" alt="{{ $gallery->title }}" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition duration-300"></div>
                </div>
                <div class="p-4 border-b-4 border-yellow-400">
                    <h3 class="font-bold text-lg text-gray-800 leading-tight mb-1">{{ $gallery->title }}</h3>
                    <p class="text-gray-500 text-xs">{{ $gallery->description }}</p>
                    <p class="text-gray-400 text-[10px] mt-2 text-right">{{ $gallery->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @endforeach
        </div>

        @if($galleries->isEmpty())
            <div class="text-center py-20 text-gray-400">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <p>Belum ada foto di galeri.</p>
            </div>
        @endif
    </div>

</body>
</html>     