<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center print:hidden">
            <h2 class="font-black text-lg uppercase tracking-tighter text-[#1A1A1A]">
                Laporan <span class="text-gray-400">Keuangan & Jasa</span>
            </h2>
            <button onclick="window.print()" class="font-black text-[9px] px-4 py-2 rounded border-2 border-[#1A1A1A] shadow-[2px_2px_0px_#000] hover:shadow-none transition-all uppercase">
                🖨️ Print Report
            </button>
        </div>
    </x-slot>

    <div class="py-4 bg-white min-h-screen print:py-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
        {{-- KOTAK FILTER --}}
        <div class="mb-6 bg-gray-50 border-2 border-[#1A1A1A] p-5 print:hidden rounded-xl shadow-sm">
            <h3 class="text-[12px] font-black text-[#1A1A1A] uppercase mb-4 tracking-widest border-b-2 border-gray-200 pb-2">Filter Data Laporan</h3>
            <form action="{{ route('admin.laporan.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-[10px] font-bold text-gray-700 uppercase mb-2 tracking-wider">Mulai Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-[#D92323] focus:ring-0 font-bold">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-700 uppercase mb-2 tracking-wider">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-[#D92323] focus:ring-0 font-bold">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-700 uppercase mb-2 tracking-wider">Jenis Layanan</label>
                    <select name="service_id" class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-[#D92323] focus:ring-0 font-bold cursor-pointer">
                        <option value="">Semua Layanan</option>
                        @foreach($allServices as $svc)
                            <option value="{{ $svc->id }}" {{ request('service_id') == $svc->id ? 'selected' : '' }}>
                                {{ $svc->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="w-full bg-[#1A1A1A] text-white font-black text-[11px] uppercase tracking-widest py-3 rounded-lg hover:bg-[#D92323] transition-colors shadow-sm">
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

            {{-- AREA LAPORAN --}}
            <div class="bg-white border border-[#1A1A1A] print:border-none">
                <div class="p-6 print:p-0">
                    
                    {{-- KOP & RINGKASAN --}}
                    <div class="flex justify-between items-end border-b-2 border-[#1A1A1A] pb-4 mb-4">
                        <div>
                            <h1 class="text-lg font-black uppercase tracking-tighter text-[#1A1A1A]">CV. WIDI ELEKTRICAL</h1>
                            <p class="text-[8px] font-bold text-gray-400 uppercase tracking-[0.2em]">Mechanical & Electrical Specialist</p>
                            <p class="text-[8px] text-gray-400 mt-0.5">Jl. Rahayu 268, Kab. Kediri | (+62) 822-5791-7387</p>
                        </div>
                        <div class="text-right border-l-2 border-[#1A1A1A] pl-4">
                            <h2 class="text-[10px] font-black uppercase italic">Rekapitulasi Keuangan & Jasa</h2>
                            <div class="mt-1 flex gap-3 justify-end text-[9px] font-black uppercase tracking-tighter">
                                <span>Omzet Real: <span class="text-[#1A1A1A]">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span></span>
                                <span class="text-gray-300">|</span>
                                <span>Total Unit: {{ $orders->count() }}</span>
                            </div>
                            <p class="text-[7px] text-gray-400 font-bold uppercase mt-1 italic">Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    {{-- TABEL --}}
                    <div class="border border-[#1A1A1A]">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50 border-b border-[#1A1A1A]">
                                <tr>
                                    <th class="p-2 text-[8px] font-black uppercase text-center w-8 border-r border-[#1A1A1A]">No</th>
                                    <th class="p-2 text-[8px] font-black uppercase border-r border-[#1A1A1A]">Tgl</th>
                                    <th class="p-2 text-[8px] font-black uppercase border-r border-[#1A1A1A]">Pelanggan</th>
                                    <th class="p-2 text-[8px] font-black uppercase border-r border-[#1A1A1A]">Layanan Pekerjaan</th>
                                    <th class="p-2 text-[8px] font-black uppercase border-r border-[#1A1A1A]">Status</th>
                                    <th class="p-2 text-[8px] font-black uppercase text-right">Uang Masuk (Rp)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($orders as $index => $order)
                                @php
                                    // LOGIKA UANG MASUK PER BARIS
                                    $uangMasuk = 0;
                                    $statusBayar = 'BELUM BAYAR / BATAL';
                                    
                                    if ($order->status !== 'cancelled' && $order->payment_status === 'paid') {
                                        if ($order->payment_step === 'dp') {
                                            $uangMasuk = $order->dp_amount;
                                            $statusBayar = 'DP MASUK';
                                        } else {
                                            $uangMasuk = $order->total_price;
                                            $statusBayar = 'LUNAS TOTAL';
                                        }
                                    }
                                @endphp
                                <tr class="text-[9px] {{ $order->status == 'cancelled' ? 'bg-red-50 text-gray-400' : '' }}">
                                    <td class="p-2 text-center font-bold border-r border-gray-100">{{ $index + 1 }}</td>
                                    <td class="p-2 font-bold border-r border-gray-100 uppercase">{{ $order->created_at->format('d/m/y') }}</td>
                                    <td class="p-2 font-black border-r border-gray-100 uppercase">{{ $order->user->name }}</td>
                                    <td class="p-2 border-r border-gray-100 leading-tight">
                                        {{ $order->services->pluck('name')->implode(', ') }}
                                    </td>
                                    <td class="p-2 border-r border-gray-100 italic font-medium">
                                        {{ $statusBayar }}
                                    </td>
                                    <td class="p-2 text-right font-black {{ $uangMasuk > 0 ? 'text-green-700' : '' }}">
                                        {{ number_format($uangMasuk, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="p-4 text-center text-[8px] font-black uppercase text-gray-300">Data Tidak Ditemukan</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="border-t border-[#1A1A1A] bg-gray-50 font-black">
                                <tr>
                                    <td colspan="5" class="p-2 text-right text-[8px] uppercase tracking-widest border-r border-[#1A1A1A]">Grand Total Revenue Valid</td>
                                    <td class="p-2 text-right text-[9px] text-[#D92323]">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- TANDA TANGAN --}}
                    <div class="hidden print:flex justify-end mt-10">
                        <div class="text-center w-40 border-t border-[#1A1A1A] pt-1">
                            <p class="text-[8px] font-black uppercase">Pimpinan Utama</p>
                            <p class="text-[9px] font-black uppercase mt-10">Misbahudi</p>
                        </div>
                    </div>

                </div>
            </div>

            <p class="mt-4 text-center print:hidden text-[7px] font-bold text-gray-400 uppercase tracking-[0.3em]">
                WIPUT Project System - Document ID: CVW-{{ now()->format('Ymd') }}
            </p>

        </div>
    </div>

    <style>
        @media print {
            body { background-color: white !important; }
            nav, header, footer { display: none !important; }
            @page { margin: 1cm; }
        }
    </style>
</x-app-layout>