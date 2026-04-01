<x-app-layout>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="mini-orbit-container">
                    <img src="{{ asset('logo.png') }}" class="h-8 w-auto relative z-10" alt="Logo Widi">
                </div>
                <div>
                    <h2 class="font-black text-2xl text-gray-800 leading-none tracking-tight">
                        CV. WIDI <span class="text-red-700">ELEKTRICAL</span>
                    </h2>
                    <p class="text-[10px] text-red-600 font-bold uppercase tracking-widest mt-1">
                        @if(Auth::user()->role === 'admin') Administrator @else Member Area @endif
                    </p>
                </div>
            </div>
            
            <div class="hidden md:flex flex-col items-end">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 mb-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse mr-2"></span>
                    System Online
                </span>
                <p class="text-xs font-bold text-gray-500">
                    {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
                </p>
            </div>
        </div>

        <style>
            .mini-orbit-container { position: relative; width: 50px; height: 50px; display: flex; justify-content: center; align-items: center; }
            .mini-orbit-container::before { content: ''; position: absolute; width: 100%; height: 100%; border-radius: 50%; border: 2px solid transparent; border-top-color: #FFD700; border-right-color: #D92323; animation: spin-lightning 3s linear infinite; }
            .mini-orbit-container::after { content: ''; position: absolute; width: 80%; height: 80%; border-radius: 50%; border: 1px solid transparent; border-bottom-color: rgba(0, 0, 0, 0.1); border-left-color: rgba(0, 0, 0, 0.1); animation: spin-lightning-reverse 5s linear infinite; }
            @keyframes spin-lightning { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
            @keyframes spin-lightning-reverse { 0% { transform: rotate(360deg); } 100% { transform: rotate(0deg); } }
            
            #map { height: 280px; width: 100%; border-radius: 0.75rem; z-index: 10; border: 3px solid #1A1A1A; box-shadow: 4px 4px 0px #1A1A1A; }
            .map-crosshair { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -100%); z-index: 1000; pointer-events: none; color: #D92323; filter: drop-shadow(0px 2px 2px rgba(0,0,0,0.5)); }
            .custom-scrollbar::-webkit-scrollbar { width: 4px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #999; }
        </style>
    </x-slot>

    <div class="py-10 bg-[#F9FAFB] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(Auth::user()->role === 'admin')
                @php $pendingGallery = \App\Models\Gallery::where('status', 'pending')->count(); @endphp
                @if($pendingGallery > 0)
                <div class="mb-8 bg-[#FFD700]/20 border-l-4 border-[#FFD700] rounded-r-xl p-6 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="bg-[#FFD700] p-3 rounded-xl text-[#1A1A1A] shadow-inner">
                            <svg class="w-6 h-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-black text-gray-800 tracking-tight text-lg uppercase">Verifikasi Foto!</h3>
                            <p class="text-sm text-gray-600 font-medium">Ada <span class="font-bold text-red-600">{{ $pendingGallery }}</span> bukti pekerjaan baru.</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.galleries.index') }}" class="w-full md:w-auto text-center text-sm bg-[#1A1A1A] text-[#FFD700] font-black px-6 py-3 rounded-lg shadow-[3px_3px_0px_#D92323]">CEK SEKARANG</a>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Total Order</p>
                        <h3 class="text-3xl font-black text-gray-800 mt-2">{{ \App\Models\Order::count() }}</h3>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-[#FFD700]">
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Pending</p>
                        <h3 class="text-3xl font-black text-yellow-500 mt-2">{{ \App\Models\Order::where('status', 'pending')->count() }}</h3>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-green-500">
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Selesai</p>
                        <h3 class="text-3xl font-black text-green-600 mt-2">{{ \App\Models\Order::where('status', 'completed')->count() }}</h3>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-[#1A1A1A]">
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Pelanggan</p>
                        <h3 class="text-3xl font-black text-[#1A1A1A] mt-2">{{ \App\Models\User::where('role', 'customer')->count() }}</h3>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-[#1A1A1A]">
                        <h3 class="font-black text-white text-sm uppercase">Pesanan Terbaru</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <tbody class="divide-y divide-gray-100">
                                @foreach(\App\Models\Order::latest()->take(5)->get() as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="font-black text-gray-900 text-sm">#{{ $order->id }} {{ $order->user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-gray-700">
                                        @foreach($order->services as $svc)
                                            {{ $svc->name }}{{ !$loop->last ? ',' : '' }}
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2 py-1 rounded text-[10px] font-black uppercase {{ $order->status == 'pending' ? 'bg-yellow-100' : 'bg-green-100' }}">{{ $order->status }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-[#D92323] font-black text-xs uppercase">Detail</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            @else
                {{-- VIEW CUSTOMER --}}
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2">
                        <div class="bg-[#D92323] rounded-t-3xl p-8 text-white relative overflow-hidden">
                            <h3 class="font-black text-xl text-[#FFD700] uppercase tracking-widest mb-1">Formulir Panggilan</h3>
                            <p class="font-black text-3xl text-white">Servis AC & Instalasi</p>
                            <svg class="absolute right-0 top-0 h-40 w-40 text-black opacity-10 transform translate-x-4 -translate-y-4" viewBox="0 0 24 24" fill="currentColor"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path></svg>
                        </div>

                        <div class="bg-white border-2 border-t-0 border-[#1A1A1A] rounded-b-3xl p-8 shadow-[6px_6px_0px_#1A1A1A]">
                            <form action="{{ route('orders.store') }}" method="POST">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                                    <div>
                                        <label class="block text-[11px] font-black text-gray-500 uppercase mb-2 tracking-widest">Nama Pelanggan</label>
                                        <input type="text" value="{{ Auth::user()->name }}" class="w-full bg-gray-100 border-2 border-gray-200 rounded-xl text-sm font-bold p-3 cursor-not-allowed" readonly>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-black text-[#1A1A1A] uppercase mb-2 tracking-widest">Nomor WhatsApp *</label>
                                        <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}" 
                                            class="w-full border-2 @error('phone') border-red-500 @else border-gray-300 @enderror rounded-xl p-3 text-sm font-bold focus:ring-0 focus:border-[#D92323] transition-colors" 
                                            placeholder="Contoh: 081234567890">
                                        @error('phone')
                                            <p class="text-red-600 text-[10px] font-black uppercase mt-1 italic tracking-wider">⚠️ {{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                {{-- REVISI: TOPIK PILIH LAYANAN (BISA PILIH LEBIH DARI 1) --}}
                                <div class="mb-6">
                                    <label class="block text-[11px] font-black text-[#1A1A1A] uppercase mb-3 tracking-widest">Pilih Layanan (Bisa pilih lebih dari satu) *</label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach($services as $service)
                                        <label class="relative flex items-center p-4 border-2 border-gray-200 rounded-2xl cursor-pointer hover:border-[#D92323] transition-all group has-[:checked]:border-[#D92323] has-[:checked]:bg-red-50 shadow-sm">
                                            <input type="checkbox" name="service_ids[]" value="{{ $service->id }}" 
                                                   class="w-5 h-5 text-[#D92323] border-2 border-gray-300 rounded focus:ring-0 cursor-pointer"
                                                   {{ is_array(old('service_ids')) && in_array($service->id, old('service_ids')) ? 'checked' : '' }}>
                                            
                                            <div class="ml-4">
                                                <p class="text-sm font-black text-gray-800 uppercase tracking-tight group-hover:text-[#D92323]">{{ $service->name }}</p>
                                                <p class="text-[11px] font-bold text-[#D92323]">Rp {{ number_format($service->price, 0, ',', '.') }}</p>
                                            </div>
                                        </label>
                                        @endforeach
                                    </div>
                                    @error('service_ids')
                                        <p class="text-red-600 text-[10px] font-black uppercase mt-2 italic tracking-wider">⚠️ {{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-1 gap-5 mb-6">
                                    <div>
                                        <label class="block text-[11px] font-black text-[#1A1A1A] uppercase mb-2 tracking-widest">Jadwal Kedatangan *</label>
                                        <div class="flex gap-2">
                                            <div class="w-3/5">
                                                <input type="date" name="booking_date" value="{{ old('booking_date') }}" 
                                                    class="w-full border-2 @error('booking_date') border-red-500 @else border-gray-300 @enderror rounded-xl p-3 text-sm font-bold focus:ring-0 focus:border-[#D92323] transition-colors">
                                            </div>
                                            <div class="w-2/5">
                                                <select name="booking_time" class="w-full border-2 border-gray-300 rounded-xl p-3 text-sm font-bold focus:ring-0 focus:border-[#D92323] cursor-pointer">
                                                    <option value="08:00" {{ old('booking_time') == '08:00' ? 'selected' : '' }}>08:00</option>
                                                    <option value="10:00" {{ old('booking_time') == '10:00' ? 'selected' : '' }}>10:00</option>
                                                    <option value="13:00" {{ old('booking_time') == '13:00' ? 'selected' : '' }}>13:00</option>
                                                    <option value="15:00" {{ old('booking_time') == '15:00' ? 'selected' : '' }}>15:00</option>
                                                </select>
                                            </div>
                                        </div>
                                        @error('booking_date')
                                            <p class="text-red-600 text-[10px] font-black uppercase mt-1 italic tracking-wider">⚠️ {{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-6 bg-gray-50 p-4 rounded-2xl border-2 border-dashed border-gray-300">
                                    <div class="flex justify-between items-center mb-3">
                                        <label class="text-[11px] font-black text-[#1A1A1A] uppercase tracking-widest">Lokasi GPS *</label>
                                        <button type="button" onclick="getLocation()" class="text-[10px] bg-[#1A1A1A] text-[#FFD700] px-3 py-2 rounded-lg font-black uppercase shadow-sm">📍 Cari Saya</button>
                                    </div>
                                    <div class="relative w-full mb-3 rounded-xl overflow-hidden">
                                        <div id="map"></div>
                                        <div class="map-crosshair">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3 mb-3">
                                        <div>
                                            <input type="text" id="latitude" name="latitude" value="{{ old('latitude') }}" class="w-full bg-gray-100 border-0 rounded-lg text-xs font-mono text-center p-2" readonly placeholder="Lat">
                                            @error('latitude') <p class="text-red-600 text-[9px] font-black italic">Pin Lokasi Wajib</p> @enderror
                                        </div>
                                        <div>
                                            <input type="text" id="longitude" name="longitude" value="{{ old('longitude') }}" class="w-full bg-gray-100 border-0 rounded-lg text-xs font-mono text-center p-2" readonly placeholder="Lng">
                                            @error('longitude') <p class="text-red-600 text-[9px] font-black italic">Pin Lokasi Wajib</p> @enderror
                                        </div>
                                    </div>
                                    <label class="block text-[11px] font-black text-[#1A1A1A] uppercase mb-2 tracking-widest">Detail Alamat Lengkap *</label>
                                    <textarea id="address_detail" name="address_detail" rows="3" class="w-full border-2 @error('address_detail') border-red-500 @else border-gray-300 @enderror rounded-xl p-3 text-sm font-bold focus:ring-0 focus:border-[#D92323]" placeholder="Geser peta untuk isi alamat otomatis...">{{ old('address_detail', Auth::user()->address) }}</textarea>
                                    @error('address_detail')
                                        <p class="text-red-600 text-[10px] font-black uppercase mt-1 italic tracking-wider">⚠️ {{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit" class="w-full bg-[#D92323] hover:bg-[#b01c1c] text-white font-black py-4 rounded-xl shadow-[4px_4px_0px_#1A1A1A] border-2 border-[#1A1A1A] uppercase tracking-widest transition-all">Kirim Pesanan Sekarang</button>
                            </form>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-[#1A1A1A] rounded-2xl p-6 shadow-[5px_5px_0px_#D92323] border-2 border-[#1A1A1A]">
                            <h4 class="font-black text-[#FFD700] mb-4 text-[11px] uppercase tracking-widest border-b border-gray-700 pb-2">Statistik Akun</h4>
                            <div class="flex justify-between text-center mt-4">
                                <div><div class="font-black text-2xl text-white">{{ $myOrders->count() }}</div><div class="text-[9px] text-gray-400 uppercase">Total</div></div>
                                <div><div class="font-black text-2xl text-[#FFD700]">{{ $myOrders->where('status', 'pending')->count() }}</div><div class="text-[9px] text-[#FFD700] uppercase tracking-tighter">Proses</div></div>
                                <div><div class="font-black text-2xl text-green-500">{{ $myOrders->where('status', 'completed')->count() }}</div><div class="text-[9px] text-green-500 uppercase tracking-tighter">Selesai</div></div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl p-5 border-2 border-gray-200 h-[400px] flex flex-col">
                            <h4 class="font-black text-[#1A1A1A] mb-4 text-[11px] uppercase border-b-2 pb-2 tracking-widest">Riwayat Servis</h4>
                            <div class="overflow-y-auto flex-1 space-y-3 pr-1 custom-scrollbar">
                            @forelse($myOrders as $order)
                                <div class="p-4 border-2 rounded-2xl 
                                    {{ $order->payment_status == 'paid' ? 'bg-blue-50 border-blue-200' : 
                                    ($order->status == 'completed' ? 'bg-green-50 border-green-200' : 
                                    ($order->status == 'cancelled' ? 'bg-red-50 border-red-100 opacity-80' : 'bg-white border-gray-200')) }} mb-4 shadow-sm">
                                    
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-[9px] font-black uppercase px-2 py-1 rounded-md 
                                            {{ $order->status == 'completed' || $order->payment_status == 'paid' ? 'bg-green-200 text-green-800' : ($order->status == 'cancelled' ? 'bg-red-200 text-red-800' : 'bg-yellow-100 text-yellow-700') }}">
                                            {{ $order->payment_status == 'paid' ? 'COMPLETED' : $order->status }}
                                        </span>
                                        <span class="text-[9px] font-black uppercase px-2 py-1 rounded-md {{ $order->payment_status == 'paid' ? 'bg-blue-600 text-white' : 'bg-red-100 text-red-700' }}">
                                            {{ $order->payment_status == 'paid' ? 'LUNAS ✅' : 'BELUM BAYAR ❌' }}
                                        </span>
                                    </div>

                                    <h5 class="font-black text-gray-800 text-sm mb-3 leading-tight uppercase">
                                        Order #{{ $order->id }} - 
                                        @foreach($order->services as $svc)
                                            {{ $svc->name }}{{ !$loop->last ? ',' : '' }}
                                        @endforeach
                                    </h5>

                                    {{-- URUTAN LOGIKA (PENTING!) --}}
                                    
                                    {{-- A. JIKA SUDAH LUNAS --}}
                                    @if($order->payment_status == 'paid')
                                        <div class="flex items-center gap-2 p-3 bg-blue-100/50 rounded-xl border-2 border-blue-200">
                                            <span class="text-blue-600">✅</span>
                                            <p class="text-[9px] text-blue-700 italic font-bold uppercase">Terima kasih! Pesanan telah selesai & lunas.</p>
                                        </div>

                                    {{-- B. JIKA DIBATALKAN (Menampilkan Catatan Asli Admin) --}}
                                    {{-- JIKA STATUS DIBATALKAN --}}
                                    @elseif($order->status == 'cancelled')
                                        <div class="p-3 bg-white border-2 border-dashed border-red-300 rounded-xl">
                                            <p class="text-[9px] font-black text-red-600 uppercase mb-1">🚫 CATATAN PEMBATALAN:</p>
                                            
                                            {{-- TAMPILKAN HASIL KETIKAN ADMIN --}}
                                            <p class="text-[10px] text-gray-700 font-bold italic leading-tight">
                                                @if(!empty($order->cancel_notes))
                                                    "{{ $order->cancel_notes }}"
                                                @else
                                                    "Pesanan dibatalkan tanpa catatan spesifik."
                                                @endif
                                            </p>
                                        </div>

                                    {{-- C. JIKA SUDAH SELESAI TAPI BELUM BAYAR (TOMBOL BAYAR) --}}
                                    @elseif($order->status == 'completed' && $order->payment_status == 'unpaid')
                                        @if($order->snap_token)
                                            <button onclick="bayarPesanan('{{ $order->snap_token }}')" 
                                                class="w-full bg-[#FFD700] text-[#1A1A1A] text-xs font-black py-3 rounded-xl border-2 border-[#1A1A1A] shadow-[4px_4px_0px_#1A1A1A] uppercase hover:shadow-none hover:translate-x-0.5 hover:translate-y-0.5 transition-all">
                                                💳 Bayar Sekarang (Rp {{ number_format($order->total_price, 0, ',', '.') }})
                                            </button>
                                        @else
                                            <div class="text-center p-2 bg-red-50 border-2 border-dashed border-red-200 rounded-xl">
                                                <p class="text-[10px] font-black text-red-600 uppercase mb-1">Menyiapkan Link Pembayaran...</p>
                                            </div>
                                        @endif

                                    {{-- D. JIKA MASIH PENDING (TOMBOL CANCEL PELANGGAN) --}}
                                    @elseif($order->status == 'pending')
                                        <form action="{{ route('orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Apa Anda yakin ingin membatalkan pesanan ini?')">
                                            @csrf
                                            <button type="submit" class="w-full bg-white text-red-600 text-[10px] font-black py-2 rounded-lg border-2 border-red-600 uppercase hover:bg-red-50 transition-all">
                                                ❌ Batalkan Pesanan
                                            </button>
                                        </form>

                                    {{-- E. JIKA SEDANG DIPROSES --}}
                                    @else
                                        <div class="flex items-center gap-2 p-3 bg-gray-50 rounded-xl border-2 border-gray-100">
                                            <span class="animate-spin text-gray-400">⚙️</span>
                                            <p class="text-[9px] text-gray-400 italic font-bold uppercase">Teknisi sedang bekerja/menuju lokasi.</p>
                                        </div>
                                    @endif

                                </div>
                            @empty
                                <p class="text-xs font-bold text-center mt-10 text-gray-400 italic uppercase tracking-widest">Belum ada pesanan</p>
                            @endforelse
                            </div>
                        </div>

                        <a href="https://wa.me/6282257917387" target="_blank" class="flex justify-center items-center w-full bg-green-500 text-white font-black py-4 rounded-xl border-2 border-[#1A1A1A] shadow-[4px_4px_0px_#1A1A1A] hover:shadow-none transition-all uppercase tracking-widest text-xs">Hubungi Admin Kami</a>

                <script>
                    var defaultLat = -7.818838, defaultLng = 112.012563;
                    var addressInput = document.getElementById('address_detail');
                    var map = L.map('map', { center: [defaultLat, defaultLng], zoom: 15, zoomControl: false });
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

                    map.on('move', function () {
                        var center = map.getCenter();
                        document.getElementById('latitude').value = center.lat.toFixed(6);
                        document.getElementById('longitude').value = center.lng.toFixed(6);
                    });

                    function fetchAddress(lat, lng) {
                        var oldVal = addressInput.value;
                        addressInput.value = "🔄 Mencari alamat...";
                        fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
                            .then(res => res.json())
                            .then(data => { addressInput.value = data.display_name || oldVal; })
                            .catch(() => { addressInput.value = oldVal; });
                    }

                    map.on('moveend', function () { fetchAddress(map.getCenter().lat, map.getCenter().lng); });
                    
                    if(!document.getElementById('latitude').value) {
                        document.getElementById('latitude').value = defaultLat;
                        document.getElementById('longitude').value = defaultLng;
                    }

                    function getLocation() {
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(pos => map.flyTo([pos.coords.latitude, pos.coords.longitude], 17));
                        }
                    }

                    function bayarPesanan(token) {
                        window.snap.pay(token, {
                            onSuccess: function(result) { 
                                alert("Pembayaran Berhasil!"); 
                                window.location.href = "/dashboard"; 
                            },
                            onPending: function() { window.location.reload(); },
                            onError: function() { alert("Pembayaran Gagal!"); window.location.reload(); }
                        });
                    }
                </script>
            @endif
        </div>
    </div>
</x-app-layout>