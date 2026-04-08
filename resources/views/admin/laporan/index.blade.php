<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center print:hidden">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Laporan Transaksi & Pendapatan
            </h2>
            <button onclick="window.print()" class="px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Laporan Resmi
            </button>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen print:bg-white print:py-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- KERTAS LAPORAN --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden print:shadow-none print:border-none print:rounded-none">
                
                <div class="p-8 print:p-0">
                    {{-- KOP SURAT (Tampil Formal di Web & Print) --}}
                    <div class="text-center mb-10 border-b-2 border-gray-800 pb-6">
                        <h1 class="text-2xl font-bold uppercase text-gray-900 tracking-wider">CV. WIDI ELEKTRICAL</h1>
                        <p class="text-sm text-gray-600 mt-1">Jl. Rahayu 268, Kel. Doko, Kec. Ngasem, Kabupaten Kediri, Jawa Timur</p>
                        <p class="text-sm text-gray-600">Email: admin@widielektrical.com | Telp: (+62) 822-5791-7387</p>
                        <h2 class="text-lg font-bold mt-6 uppercase text-gray-800">Laporan Rekapitulasi Pendapatan Jasa</h2>
                        <p class="text-xs text-gray-500 mt-1">Dicetak pada: {{ now()->translatedFormat('d F Y \P\u\k\u\l H:i') }}</p>
                    </div>

                    {{-- RINGKASAN DATA --}}
                    <div class="grid grid-cols-2 gap-6 mb-8 print:mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 uppercase">Total Pendapatan Terkumpul</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 uppercase">Total Pesanan Diproses</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $orders->count() }} Transaksi</p>
                        </div>
                    </div>

                    {{-- TABEL LAPORAN FORMAL --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-300 border border-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-300">No</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-300">Tanggal</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-300">Pelanggan</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-300">Layanan Pekerjaan</th>
                                    <th scope="col" class="px-3 py-3.5 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-300">Status</th>
                                    <th scope="col" class="px-3 py-3.5 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-300">Total Biaya</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($orders as $index => $order)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-900">{{ $index + 1 }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600">{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm font-medium text-gray-900">{{ $order->user->name }}</td>
                                    <td class="px-3 py-4 text-sm text-gray-600">
                                        <ul class="list-disc list-inside">
                                            @foreach($order->services as $service)
                                                <li>{{ $service->name }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-300">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-right font-semibold text-gray-900">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-sm text-gray-500 italic">Belum ada data transaksi yang tercatat.</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <th colspan="5" class="py-3 px-4 text-right text-sm font-bold text-gray-900 uppercase">Grand Total Pendapatan</th>
                                    <th class="py-3 px-4 text-right text-sm font-bold text-blue-700">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- TANDA TANGAN (Hanya muncul saat print) --}}
                    <div class="hidden print:flex justify-end mt-16 text-center">
                        <div class="w-48">
                            <p class="text-sm text-gray-800 mb-20">Kediri, {{ now()->translatedFormat('d F Y') }}<br>Pimpinan CV. Widi Elektrical</p>
                            <p class="text-sm font-bold text-gray-900 underline">Misbahudi</p>
                            <p class="text-xs text-gray-600 mt-1">Direktur Utama</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- STYLE KHUSUS MENGHILANGKAN NAVIGASI SAAT PRINT --}}
    <style>
        @media print {
            body { background-color: white !important; }
            nav, header, footer { display: none !important; }
            @page { margin: 2cm; }
        }
    </style>
</x-app-layout>