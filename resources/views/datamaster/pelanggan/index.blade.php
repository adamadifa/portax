@extends('layouts.app')
@section('titlepage', 'Data Pelanggan')

@section('content')
    <!-- Page Header -->
    <div class="mb-5 flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Title & Subtitle -->
        <div>
            <h2 class="text-2xl font-bold text-slate-800 leading-tight">Data Pelanggan</h2>
            <p class="text-sm text-slate-500">Manage customer data and performance.</p>
        </div>
        <!-- Actions -->
        @can('pelanggan.create')
            <button id="btncreatePelanggan" class="bg-[#003d9e] hover:bg-blue-800 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors shadow-sm shadow-blue-200 text-sm font-medium">
                <i class="ti ti-plus"></i>
                <span>Tambah Pelanggan</span>
            </button>
        @endcan
    </div>

    <!-- Statistics Cards (Converted to Tailwind) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <!-- Card 1: Database Pelanggan -->
        <!-- Card 1: Database Pelanggan -->
        <div class="bg-gradient-to-br from-[#003d9e] to-[#002a6f] rounded-xl shadow-[0_2px_8px_rgba(0,0,0,0.15)] border-none overflow-hidden relative group hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
            <div class="p-4 flex items-end justify-between">
                <div class="z-10 relative">
                    <h5 class="text-white/90 font-bold mb-1 flex items-center gap-2">
                        <i class="ti ti-database text-white"></i> Database
                    </h5>
                    <p class="text-xs text-white/70 mb-2">Total Database Pelanggan</p>
                    <h4 class="text-2xl font-bold text-white">{{ formatRupiah($jmlpelanggan) }}</h4>
                </div>
                 <div class="absolute right-0 bottom-0 opacity-20 transition-transform group-hover:scale-110 group-hover:opacity-30">
                    <img src="{{ asset('assets/img/illustrations/card-advance-sale.png') }}" height="120" class="h-28 object-contain filter brightness-0 invert" alt="view sales">
                 </div>
            </div>
        </div>
        
        <!-- Card 2: Pelanggan Aktif -->
        <!-- Card 2: Pelanggan Aktif -->
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-xl shadow-[0_2px_8px_rgba(0,0,0,0.15)] border-none overflow-hidden relative group hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
             <div class="p-4 flex items-end justify-between">
                <div class="z-10 relative">
                    <h5 class="text-white/90 font-bold mb-1 flex items-center gap-2">
                        <i class="ti ti-user-check text-white"></i> Aktif
                    </h5>
                     <p class="text-xs text-white/70 mb-2">Pelanggan Aktif Transaksi</p>
                    <h4 class="text-2xl font-bold text-white">{{ formatRupiah($jmlpelangganaktif) }}</h4>
                </div>
                <div class="absolute right-0 bottom-0 opacity-20 transition-transform group-hover:scale-110 group-hover:opacity-30">
                    <img src="{{ asset('assets/img/illustrations/girl-with-laptop.png') }}" height="120" class="h-28 object-contain filter brightness-0 invert" alt="view sales">
                </div>
            </div>
        </div>

        <!-- Card 3: Pelanggan Non Aktif -->
        <!-- Card 3: Pelanggan Non Aktif -->
         <div class="bg-gradient-to-br from-rose-500 to-rose-700 rounded-xl shadow-[0_2px_8px_rgba(0,0,0,0.15)] border-none overflow-hidden relative group hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
             <div class="p-4 flex items-end justify-between">
                <div class="z-10 relative">
                    <h5 class="text-white/90 font-bold mb-1 flex items-center gap-2">
                        <i class="ti ti-user-x text-white"></i> Non Aktif
                    </h5>
                    <p class="text-xs text-white/70 mb-2">Pelanggan Tidak Transaksi</p>
                    <h4 class="text-2xl font-bold text-white">{{ formatRupiah($jmlpelanggannonaktif) }}</h4>
                </div>
                <div class="absolute right-0 bottom-0 opacity-20 transition-transform group-hover:scale-110 group-hover:opacity-30">
                     <img src="{{ asset('assets/img/illustrations/inactive-customer.png') }}" height="120" class="h-28 object-contain filter brightness-0 invert" alt="view sales">
                </div>
            </div>
        </div>
    </div>


    <!-- Content Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        
        <!-- Search & Filter / Toolbar -->
        <div class="p-3 border-b border-slate-100 bg-slate-50/50">
            <form action="{{ route('pelanggan.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-x-3 gap-y-1">
                @php
                    $userHasCabang = false;
                @endphp
                @hasanyrole($roles_show_cabang)
                    @php $userHasCabang = true; @endphp
                @endhasanyrole

                <!-- Row 1: Date Filters (Full Width Split) -->
                
                <!-- Dari Tgl -->
                <div class="md:col-span-6 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ti ti-calendar text-slate-400"></i>
                    </div>
                     <input type="text" name="dari" value="{{ Request('dari') }}" 
                        class="flatpickr-date w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 placeholder-slate-400 transition-all"
                        placeholder="Dari Tanggal">
                </div>

                <!-- Sampai Tgl -->
                 <div class="md:col-span-6 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ti ti-calendar text-slate-400"></i>
                    </div>
                     <input type="text" name="sampai" value="{{ Request('sampai') }}" 
                        class="flatpickr-date w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 placeholder-slate-400 transition-all"
                        placeholder="Sampai Tanggal">
                </div>

                <!-- Row 2: Other Filters & Action -->

                <!-- Status -->
                <div class="md:col-span-2 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ti ti-filter text-slate-400"></i>
                    </div>
                    <select name="status" id="status" class="w-full pl-10 pr-8 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 appearance-none cursor-pointer">
                        <option value="">Status</option>
                        <option value="aktif" {{ Request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ Request('status') == 'nonaktif' ? 'selected' : '' }}>Non Aktif</option>
                    </select>
                    <i class="ti ti-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                </div>
                
                <!-- Cabang (Conditional) -->
                @if($userHasCabang)
                <div class="md:col-span-2 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ti ti-building text-slate-400"></i>
                    </div>
                    <select name="kode_cabang" id="kode_cabang_filter" class="w-full pl-10 pr-8 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 appearance-none cursor-pointer">
                        <option value="">Cabang</option>
                        @foreach ($cabang as $c)
                            <option value="{{ $c->kode_cabang }}" {{ Request('kode_cabang') == $c->kode_cabang ? 'selected' : '' }}>{{ $c->nama_cabang }}</option>
                        @endforeach
                    </select>
                    <i class="ti ti-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                </div>
                @endif

                <!-- Salesman -->
                <div class="{{ $userHasCabang ? 'md:col-span-2' : 'md:col-span-3' }} relative">
                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ti ti-tie text-slate-400"></i>
                    </div>
                     <select name="kode_salesman" id="kode_salesman" class="w-full pl-10 pr-8 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 appearance-none cursor-pointer">
                         <option value="">Salesman</option>
                     </select>
                     <i class="ti ti-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                </div>

                <!-- Kode -->
                 <div class="{{ $userHasCabang ? 'md:col-span-2' : 'md:col-span-3' }} relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ti ti-barcode text-slate-400"></i>
                    </div>
                    <input type="text" name="kode_pelanggan" value="{{ Request('kode_pelanggan') }}" 
                        class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 placeholder-slate-400 transition-all"
                        placeholder="Kode">
                </div>

                <!-- Nama -->
                 <div class="{{ $userHasCabang ? 'md:col-span-3' : 'md:col-span-3' }} relative">
                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ti ti-user text-slate-400"></i>
                    </div>
                    <input type="text" name="nama_pelanggan" value="{{ Request('nama_pelanggan') }}" 
                        class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 placeholder-slate-400 transition-all"
                        placeholder="Nama Pelanggan">
                </div>

                <!-- Search Button (Icon Only) -->
                <div class="md:col-span-1">
                    <button type="submit" class="h-full aspect-square bg-[#003d9e] hover:bg-blue-800 text-white rounded-lg font-medium text-sm transition-colors shadow-sm shadow-blue-200 flex items-center justify-center">
                        <i class="ti ti-search"></i>
                    </button>
                </div>
            </form>
        </div>

    </div> 
    <!-- End Filter Section Card -->

    <!-- Card List Container -->
    <div class="flex flex-col gap-2 mt-3">
        @forelse ($pelanggan as $d)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-2 hover:shadow-md transition-shadow flex flex-col md:flex-row items-center gap-3">
                
                <!-- 1. Foto & Identitas -->
                <div class="flex items-center gap-3 w-full md:w-72 md:shrink-0 border-b md:border-b-0 md:border-r border-slate-100 pb-2 md:pb-0 md:pr-3">
                    <!-- Foto -->
                    <div class="flex-shrink-0 relative">
                        @php
                            $path = $d->foto ? '/pelanggan/' . $d->foto : '';
                            $exists = $d->foto && Storage::disk('public')->exists($path);
                        @endphp
                        @if ($exists)
                            <img src="{{ getfotoPelanggan($d->foto) }}" class="w-12 h-12 rounded-full object-cover border-2 border-slate-100 shadow-sm" alt="Foto">
                        @else
                            <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 border-2 border-slate-100">
                                <i class="ti ti-user text-lg"></i>
                            </div>
                        @endif
                        <!-- Status Badge Absolute -->
                        <div class="absolute -bottom-1 -right-1 bg-white rounded-full p-0.5 shadow-sm">
                             @if ($d->status_aktif_pelanggan == 1)
                                <i class="ti ti-circle-check text-emerald-500 text-xs"></i>
                            @else
                                <i class="ti ti-circle-x text-rose-500 text-xs"></i>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Text Info -->
                     <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-0.5">
                             <h4 class="font-bold text-slate-800 text-sm truncate" title="{{ textCamelCase($d->nama_pelanggan) }}">
                                {{ textCamelCase($d->nama_pelanggan) }}
                            </h4>
                        </div>
                         <div class="flex flex-col gap-0.5">
                             <span class="inline-flex items-center gap-1.5 text-xs text-[#003d9e] font-mono font-medium bg-blue-50 px-2 py-0.5 rounded w-fit">
                                <i class="ti ti-barcode text-[10px]"></i> {{ $d->kode_pelanggan }}
                            </span>
                         </div>
                    </div>
                </div>

                <!-- 2. Details Info (Horizontal) -->
                <div class="flex-1 w-full grid grid-cols-2 md:grid-cols-4 gap-y-1 gap-x-3">
                     <!-- Wilayah -->
                    <div class="min-w-0">
                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold mb-0">Wilayah</p>
                        <div class="flex items-center gap-1">
                            <i class="ti ti-map-pin text-slate-400 text-xs"></i>
                            <span class="text-sm font-medium text-slate-700 truncate">{{ textCamelCase($d->nama_wilayah) }}</span>
                        </div>
                    </div>
                    
                    <!-- Salesman -->
                     <div class="min-w-0">
                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold mb-0">Salesman</p>
                        <div class="flex items-center gap-1">
                            <i class="ti ti-tie text-slate-400 text-xs"></i>
                            <span class="text-sm font-medium text-slate-700 truncate">{{ textCamelCase($d->nama_salesman) }}</span>
                        </div>
                    </div>

                    <!-- Cabang -->
                    <div class="min-w-0">
                         <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold mb-0">Cabang</p>
                         <div class="flex items-center gap-1">
                             <i class="ti ti-building text-slate-400 text-xs"></i>
                             <span class="text-sm font-medium text-slate-700">{{ $d->kode_cabang }}</span>
                         </div>
                    </div>

                    <!-- Limit -->
                    <div class="min-w-0">
                         <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold mb-0">Limit Kredit</p>
                         @if (empty($d->limit_pelanggan))
                             <span class="inline-block text-xs font-semibold text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded">Belum Setup</span>
                        @else
                            <span class="text-sm font-bold text-[#003d9e]">{{ formatRupiah($d->limit_pelanggan) }}</span>
                        @endif
                    </div>
                </div>

                <!-- 3. Actions -->
                <div class="w-full md:w-auto flex flex-col md:flex-row items-center border-t md:border-t-0 md:border-l border-slate-100 pt-2 md:pt-0 md:pl-3 justify-end md:justify-center">
                    <div class="inline-flex rounded-md shadow-sm isolate" role="group">
                        @can('pelanggan.edit')
                            <button class="editPelanggan w-8 h-8 flex items-center justify-center bg-amber-50 text-amber-600 hover:bg-amber-100 transition-colors border border-amber-200 rounded-l-lg focus:z-10"
                                kode_pelanggan="{{ Crypt::encrypt($d->kode_pelanggan) }}" title="Edit">
                                <i class="ti ti-pencil text-xs"></i>
                            </button>
                        @endcan
                        @can('pelanggan.show')
                            <a href="{{ route('pelanggan.show', Crypt::encrypt($d->kode_pelanggan)) }}" class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors border-y border-r border-blue-200 -ml-px focus:z-10" title="Detail">
                                <i class="ti ti-file-text text-xs"></i>
                            </a>
                        @endcan
                        @can('pelanggan.delete')
                            <form method="POST" name="deleteform" class="deleteform inline-block"
                                action="{{ route('pelanggan.delete', Crypt::encrypt($d->kode_pelanggan)) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-confirm w-8 h-8 flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-100 transition-colors border-y border-r border-red-200 rounded-r-lg -ml-px focus:z-10" title="Delete">
                                    <i class="ti ti-trash text-xs"></i>
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>

            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 text-center text-slate-400 flex flex-col items-center">
                <div class="w-14 h-14 bg-slate-50 rounded-full flex items-center justify-center mb-2">
                    <i class="ti ti-inbox text-2xl text-slate-300"></i>
                </div>
                 <h5 class="text-sm font-medium text-slate-600">Tidak ada data ditemukan</h5>
                <p class="text-xs mt-1">Coba ubah filter pencarian anda.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-3 bg-white p-3 rounded-xl shadow-sm border border-slate-200 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="text-xs text-slate-500">
            Showing {{ $pelanggan->firstItem() }} to {{ $pelanggan->lastItem() }} of {{ $pelanggan->total() }} entries
        </div>
        <div class="flex gap-1">
            {{ $pelanggan->links('pagination::tailwind') }} 
        </div>
    </div>

    <!-- Tailwind Modal Implementation -->
    <div id="tailwindModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300 ease-out opacity-0" id="modalBackdrop"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all duration-300 ease-out sm:my-8 sm:w-full sm:max-w-4xl opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" id="modalPanel">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div id="modalContent">
                            <div class="flex justify-center p-8">
                                <i class="ti ti-loader fa-spin text-[#003d9e] text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Nonaktif Modal (Separate if needed, or reuse tailwindModal with load logic) -->

@endsection

@push('myscript')
<script>
    $(function() {
        $(".flatpickr-date").flatpickr();

        // --- Standard Modal Logic ---
        const modal = document.getElementById('tailwindModal');
        const backdrop = document.getElementById('modalBackdrop');
        const panel = document.getElementById('modalPanel');
        const content = document.getElementById('modalContent');
        
        function openModal(url) {
            modal.classList.remove('hidden');
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                panel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
                panel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
            }, 10);
            $("#modalContent").html('<div class="flex justify-center p-8"><i class="ti ti-loader fa-spin text-[#003d9e] text-2xl"></i></div>');
            $("#modalContent").load(url);
        }

        function closeModal() {
            backdrop.classList.add('opacity-0');
            panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            panel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            setTimeout(() => { modal.classList.add('hidden'); }, 300);
        }

        if (backdrop) backdrop.addEventListener('click', closeModal);
        window.closeTailwindModal = closeModal;

        // --- Triggers ---
        $("#btncreatePelanggan").click(function(e) { e.preventDefault(); openModal('/pelanggan/create'); });
        
        $(document).on('click', '.editPelanggan', function(e) {
            e.preventDefault();
            var kode_pelanggan = $(this).attr("kode_pelanggan");
            openModal('/pelanggan/' + kode_pelanggan + '/edit');
        });

        $("#btnNonaktif").click(function(e) {
            e.preventDefault();
            openModal('/pelanggan/nonaktif');
        });

        // --- Helper for Salesman Dropdown in Filters ---
         function getsalesmanbyCabang() {
            var kode_cabang = $("#kode_cabang_filter").val();
            var kode_salesman = "{{ Request('kode_salesman') }}";
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang,
                    kode_salesman: kode_salesman
                },
                cache: false,
                success: function(respond) {
                    $("#kode_salesman").html(respond);
                }
            });
        }
        getsalesmanbyCabang();
        $("#kode_cabang_filter").change(function(e) {
            getsalesmanbyCabang();
        });

    });
</script>
@endpush
