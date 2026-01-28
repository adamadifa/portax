@extends('layouts.app')
@section('titlepage', 'Detail Pelanggan')

@section('content')

<!-- Wrapper -->
<div class="w-full max-w-7xl mx-auto space-y-6">

    <!-- Profile Header / Hero Section -->
    <div class="relative w-full rounded-2xl overflow-hidden bg-white shadow-sm border border-slate-200">
        <!-- Banner/Map -->
        <div class="h-48 bg-slate-100 relative group overflow-hidden">
            @if($pelanggan->latitude && $pelanggan->longitude)
                <iframe 
                    width="100%" 
                    height="100%" 
                    frameborder="0" 
                    scrolling="no" 
                    marginheight="0" 
                    marginwidth="0" 
                    src="https://maps.google.com/maps?q={{ $pelanggan->latitude }},{{ $pelanggan->longitude }}&hl=es&z=14&amp;output=embed"
                    class="w-full h-full object-cover grayscale opacity-80 hover:grayscale-0 hover:opacity-100 transition-all duration-500">
                </iframe>
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent pointer-events-none"></div>
                <a href="https://www.google.com/maps/search/?api=1&query={{ $pelanggan->latitude }},{{ $pelanggan->longitude }}" target="_blank" class="absolute top-4 right-4 bg-white/90 backdrop-blur text-[#003d9e] px-3 py-1.5 rounded-lg shadow-sm text-xs font-bold hover:bg-white transition-colors z-10 flex items-center gap-1.5">
                    <i class="fas fa-map-marked-alt"></i> Buka Maps
                </a>
            @else
                <div class="w-full h-full bg-gradient-to-r from-[#003d9e] to-blue-600 flex items-center justify-center">
                    <div class="text-blue-100 flex flex-col items-center">
                        <i class="fas fa-map-marked-alt text-4xl mb-2 opacity-50"></i>
                        <span class="text-sm font-medium opacity-75">Lokasi tidak tersedia</span>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Profile Info Layer -->
        <div class="px-6 pb-4 relative">
             <div class="flex flex-col sm:flex-row items-end -mt-10 gap-5">
                <!-- Avatar -->
                <div class="relative shrink-0 z-10">
                    <div class="w-24 h-24 rounded-xl border-4 border-white shadow-md overflow-hidden bg-slate-100 relative group">
                         @if (Storage::disk('public')->exists('/pelanggan/' . $pelanggan->foto))
                            <img src="{{ getfotoPelanggan($pelanggan->foto) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                             <div class="w-full h-full flex items-center justify-center bg-slate-200 text-slate-400">
                                <i class="fas fa-user text-3xl"></i>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Name & Meta -->
                <div class="flex-grow pb-1 w-full sm:w-auto text-left">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div>
                            <h1 class="text-xl font-bold text-slate-800 leading-tight">{{ textCamelCase($pelanggan->nama_pelanggan) }}</h1>
                             <div class="flex flex-wrap items-center justify-start gap-y-2 gap-x-2 text-xs font-medium text-slate-500 mt-1">
                                <span class="flex items-center gap-1.5 bg-slate-100 px-2 py-0.5 rounded text-slate-600">
                                    <i class="fas fa-barcode text-[#003d9e]"></i> {{ $pelanggan->kode_pelanggan }}
                                </span>
                                <span class="hidden sm:inline text-slate-300">•</span>
                                <span class="flex items-center gap-1.5">
                                    <i class="fas fa-building text-slate-400"></i> {{ textCamelCase($pelanggan->nama_cabang) }}
                                </span>
                                <span class="hidden sm:inline text-slate-300">•</span>
                                <span class="flex items-center gap-1.5">
                                    <i class="fas fa-user-tie text-slate-400"></i> {{ textCamelCase($pelanggan->nama_salesman) }}
                                </span>
                            </div>
                        </div>
                        
                         <!-- Status -->
                        <div class="shrink-0">
                            @if ($pelanggan->status_aktif_pelanggan === '1')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                    <i class="fas fa-check-circle text-[10px]"></i> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-100">
                                    <i class="fas fa-times-circle text-[10px]"></i> Nonaktif
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Detailed Info -->
        <div class="space-y-6 lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50/50 px-5 py-3 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wide">Informasi Pelanggan</h3>
                    <a href="{{ route('pelanggan.index') }}" class="text-xs font-medium text-slate-500 hover:text-[#003d9e]">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div class="p-5 space-y-4">
                    <!-- List Items -->
                    @php
                        $details = [
                           ['label' => 'NIK', 'value' => $pelanggan->nik, 'icon' => 'fa-id-card'],
                           ['label' => 'No. KK', 'value' => $pelanggan->no_kk, 'icon' => 'fa-address-card'],
                           ['label' => 'Tgl Lahir', 'value' => !empty($pelanggan->tanggal_lahir) ? DateToIndo($pelanggan->tanggal_lahir) : '-', 'icon' => 'fa-birthday-cake'],
                           ['label' => 'No. HP', 'value' => $pelanggan->no_hp_pelanggan, 'icon' => 'fa-phone'],
                           ['label' => 'Wilayah', 'value' => textCamelCase($pelanggan->nama_wilayah), 'icon' => 'fa-map'],
                           ['label' => 'Alamat Rumah', 'value' => textCamelCase($pelanggan->alamat_pelanggan), 'icon' => 'fa-home'],
                           ['label' => 'Alamat Toko', 'value' => textCamelCase($pelanggan->alamat_toko), 'icon' => 'fa-store'],
                           ['label' => 'Koordinat', 'value' => $pelanggan->latitude . ', ' . $pelanggan->longitude, 'icon' => 'fa-map-marker-alt'],
                           ['label' => 'Hari Kunjungan', 'value' => textCamelCase($pelanggan->hari), 'icon' => 'fa-calendar-day'],
                           ['label' => 'LJT', 'value' => $pelanggan->ljt . ' Hari', 'icon' => 'fa-stopwatch'],
                           ['label' => 'Kepemilikan', 'value' => !empty($pelanggan->kepemilikan) ? $kepemilikan[$pelanggan->kepemilikan] : '-', 'icon' => 'fa-user-tag'],
                           ['label' => 'Lama Berjualan', 'value' => !empty($pelanggan->lama_berjualan) ? $lama_berjualan[$pelanggan->lama_berjualan] : '-', 'icon' => 'fa-hourglass-half'],
                           ['label' => 'Status Outlet', 'value' => !empty($pelanggan->status_outlet) ? $status_outlet[$pelanggan->status_outlet] : '-', 'icon' => 'fa-check-double'],
                           ['label' => 'Tipe Outlet', 'value' => !empty($pelanggan->type_outlet) ? $type_outlet[$pelanggan->type_outlet] : '-', 'icon' => 'fa-store-alt'],
                           ['label' => 'Cara Bayar', 'value' => !empty($pelanggan->cara_pembayaran) ? $cara_pembayaran[$pelanggan->cara_pembayaran] : '-', 'icon' => 'fa-wallet'],
                           ['label' => 'Lama Langganan', 'value' => !empty($pelanggan->lama_langganan) ? $lama_langganan[$pelanggan->lama_langganan] : '-', 'icon' => 'fa-handshake'],
                           ['label' => 'Jaminan', 'value' => $pelanggan->jaminan == 1 ? 'Ada' : 'Tidak Ada', 'icon' => 'fa-shield-alt'],
                        ];
                    @endphp

                    @foreach($details as $detail)
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start py-2.5 border-b border-slate-50 last:border-0 hover:bg-slate-50/50 transition-colors px-2 -mx-2 rounded">
                        <div class="flex items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider shrink-0 mt-0.5">
                            <i class="fas {{ $detail['icon'] }} w-4 text-center text-slate-300"></i> {{ $detail['label'] }}
                        </div>
                        <div class="text-sm font-semibold text-slate-700 text-left sm:text-right max-w-full sm:max-w-[60%] break-words leading-snug">
                            {{ $detail['value'] ?: '-' }}
                        </div>
                    </div>
                    @endforeach
                    
                     <div class="pt-3 mt-3 border-t border-slate-100">
                        <div class="grid grid-cols-2 gap-3">
                             <div class="bg-blue-50 p-3 rounded-lg text-center">
                                <span class="block text-[10px] text-[#003d9e] font-bold uppercase mb-1">Omset Toko</span>
                                <span class="block text-sm font-bold text-[#003d9e]">{{ formatRupiah($pelanggan->omset_toko) }}</span>
                            </div>
                            <div class="bg-emerald-50 p-3 rounded-lg text-center">
                                <span class="block text-[10px] text-emerald-500 font-bold uppercase mb-1">Limit</span>
                                <span class="block text-sm font-bold text-emerald-700">{{ formatRupiah($pelanggan->limit_pelanggan) }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            


        </div>

        <!-- Right Column: Transactions -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="border-b border-slate-200">
                     <nav class="flex space-x-4 px-4" aria-label="Tabs">
                        <button class="px-3 py-4 text-sm font-medium text-[#003d9e] border-b-2 border-[#003d9e] focus:outline-none">
                            <i class="fas fa-shopping-cart me-2"></i> Riwayat Penjualan
                        </button>
                    </nav>
                </div>
                
                <div class="p-6">
                    <!-- Search Filter -->
                    <form action="{{ url()->current() }}" method="GET" class="mb-6 space-y-1">
                        <!-- Row 1: Date Range -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar text-slate-400"></i>
                                </div>
                                <input type="text" name="dari" value="{{ Request('dari') }}" class="flatpickr-date w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] transition-colors" placeholder="Dari">
                            </div>
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar text-slate-400"></i>
                                </div>
                                <input type="text" name="sampai" value="{{ Request('sampai') }}" class="flatpickr-date w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] transition-colors" placeholder="Sampai">
                            </div>
                        </div>

                        <!-- Row 2: Search Input + Button -->
                        <div class="flex gap-3">
                            <div class="relative flex-grow">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-barcode text-slate-400"></i>
                                </div>
                                <input type="text" name="no_faktur_search" value="{{ Request('no_faktur_search') }}" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] transition-colors" placeholder="Cari nomor faktur...">
                            </div>
                            <button class="flex-none aspect-square h-full bg-[#003d9e] hover:bg-blue-800 text-white rounded-lg shadow-sm shadow-blue-200 transition-colors flex items-center justify-center px-3.5" title="Cari Data">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Table -->
                    <div class="overflow-x-auto rounded-lg border border-slate-200">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead class="bg-slate-50 text-xs uppercase text-slate-500 font-bold">
                                <tr>
                                    <th class="px-4 py-3">No. Faktur</th>
                                    <th class="px-4 py-3">Tanggal</th>
                                    <th class="px-4 py-3">Salesman</th>
                                    <th class="px-4 py-3 text-right">Total</th>
                                    <th class="px-4 py-3 text-center">JT</th>
                                    <th class="px-4 py-3 text-center">Bayar</th>
                                    <th class="px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse ($penjualan as $d)
                                    @php
                                        $total_netto = $d->total_bruto - $d->total_retur - $d->potongan - $d->potongan_istimewa - $d->penyesuaian + $d->ppn;
                                        // Colors logic
                                        $row_class = "";
                                        if ($d->status_batal == '1') {
                                            $row_class = "bg-red-50 text-red-700";
                                        } elseif (substr($d->no_faktur, 3, 2) == 'PR') {
                                            $row_class = "bg-blue-50 text-blue-700";
                                        }
                                    @endphp
                                    <tr class="hover:bg-slate-50 transition-colors {{ $row_class }}">
                                        <td class="px-4 py-3 font-medium">{{ $d->no_faktur }}</td>
                                        <td class="px-4 py-3">{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                                        <td class="px-4 py-3">{{ $d->nama_salesman }}</td>
                                        <td class="px-4 py-3 text-right font-bold">{{ formatAngka($total_netto) }}</td>
                                        <td class="px-4 py-3 text-center">
                                            @if ($d->jenis_transaksi == 'T')
                                                <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-600">TUNAI</span>
                                            @elseif($d->jenis_transaksi == 'K')
                                                <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-600">KREDIT</span>
                                            @else 
                                                 <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">{{ $d->jenis_transaksi }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if ($d->total_bayar >= $total_netto)
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600">
                                                    <i class="fas fa-check-circle"></i> LUAS
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-rose-600">
                                                    <i class="fas fa-clock"></i> BELUM
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @can('penjualan.show')
                                                 <a href="{{ route('penjualan.show', Crypt::encrypt($d->no_faktur)) }}" class="text-[#003d9e] hover:text-blue-800 transition-colors" title="Lihat Detail">
                                                    <i class="fas fa-file-invoice"></i>
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-8 text-center text-slate-400 italic">
                                            <div class="flex flex-col items-center justify-center">
                                                <i class="fas fa-inbox text-3xl mb-2 text-slate-300"></i>
                                                Belum ada riwayat transaksi.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $penjualan->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        // Initialize flatpickr if not already auto-initialized by layout
        // Assuming global init, but if using .flatpickr-date class:
        if(typeof flatpickr !== 'undefined') {
            $(".flatpickr-date").flatpickr({
                dateFormat: "Y-m-d",
                allowInput: true
            });
        }
    });
</script>
@endpush
