@extends('layouts.app')
@section('titlepage', 'Data Retur')

@section('content')
    <!-- Page Header -->
    <div class="mb-5 flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Title & Subtitle -->
        <div>
            <h2 class="text-2xl font-bold text-slate-800 leading-tight">Data Retur</h2>
            <p class="text-sm text-slate-500">Manage return transactions.</p>
        </div>
        <!-- Actions -->
        <div class="flex flex-wrap gap-2">
            @can('retur.create')
                <a href="{{ route('retur.create') }}" class="bg-[#003d9e] hover:bg-blue-800 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors shadow-sm shadow-blue-200 text-sm font-medium" id="btnCreate">
                    <i class="ti ti-plus"></i>
                    <span>Input Retur</span>
                </a>
            @endcan
        </div>
    </div>

    <!-- Content Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        
        <!-- Search & Filter / Toolbar -->
        <div class="p-3 border-b border-slate-100 bg-slate-50/50">
            <form action="{{ route('retur.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-x-3 gap-y-1">
                
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
                        class="flatpickr-date w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 placeholder-slate-400 transition-all font-medium"
                        placeholder="Dari Tanggal">
                </div>

                <!-- Sampai Tgl -->
                <div class="md:col-span-6 relative">
                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ti ti-calendar text-slate-400"></i>
                    </div>
                    <input type="text" name="sampai" value="{{ Request('sampai') }}" 
                        class="flatpickr-date w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 placeholder-slate-400 transition-all font-medium"
                        placeholder="Sampai Tanggal">
                </div>

                <!-- Row 2: Other Filters & Action -->
                
@section('style')
<style>
    /* Select2 Customization to match Tailwind Inputs perfectly */
    .select2-container .select2-selection--single {
        height: 42px !important; /* Match Tailwind input height */
        background-color: #fff !important;
        border: 1px solid #cbd5e1 !important; /* slate-300 */
        border-radius: 0.5rem !important; /* rounded-lg */
        box-sizing: border-box !important;
    }
    
    /* Text Adjustment */
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #334155 !important; /* slate-700 */
        font-size: 0.875rem !important; /* text-sm */
        font-weight: 500 !important;
        padding-left: 2.75rem !important; /* Space for icon */
        padding-right: 2rem !important;
        line-height: 40px !important; /* Vertically center text */
        display: block !important;
    }

    /* Placeholder Color */
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #94a3b8 !important; /* slate-400 */
    }

    /* Arrow Positioning */
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
        right: 8px !important;
        top: 0 !important;
        position: absolute;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #94a3b8 transparent transparent transparent !important;
        margin-top: -2px !important;
    }

    /* Focus State */
    .select2-container--default.select2-container--open .select2-selection--single,
    .select2-container--default .select2-selection--single:focus {
        border-color: #003d9e !important;
        box-shadow: 0 0 0 2px rgba(0, 61, 158, 0.2) !important; 
        outline: none;
    }

    /* Dropdown Panel */
    .select2-dropdown {
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.5rem !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
        z-index: 9999;
        margin-top: 4px;
        background-color: #fff !important;
    }

    .select2-results__option {
        padding: 8px 12px;
        font-size: 0.875rem;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #003d9e !important; /* Primary Brand Blue */
        color: white !important;
    }
    
    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1px solid #cbd5e1 !important;
        border-radius: 0.375rem !important;
        padding: 6px 12px !important;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field:focus {
        border-color: #003d9e !important;
        box-shadow: none !important;
        outline: none !important;
    }

    /* Ensure dropdowns appear above cards */
    [x-data] > div[x-show] {
        z-index: 100 !important;
    }

    /* Alpine.js cloak */
    [x-cloak] {
        display: none !important;
    }

    /* Pure CSS Print Dropdown - No JavaScript needed */
    .print-dropdown-wrapper {
        position: relative;
    }

    .print-dropdown-menu {
        z-index: 100;
        pointer-events: none;
    }

    .print-dropdown-wrapper:hover .print-dropdown-menu,
    .print-dropdown-menu:hover {
        opacity: 1 !important;
        visibility: visible !important;
        pointer-events: auto;
    }
</style>
@endsection

                <!-- Cabang (Conditional) -->
                @if($userHasCabang)
                <div class="md:col-span-2 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="ti ti-building text-slate-400"></i>
                    </div>
                    <select name="kode_cabang_search" id="kode_cabang_search" class="select2Kodecabangsearch w-full pl-10 pr-8 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 appearance-none cursor-pointer font-medium">
                        <option value="">Semua Cabang</option>
                        @foreach ($cabang as $c)
                            <option value="{{ $c->kode_cabang }}" {{ Request('kode_cabang_search') == $c->kode_cabang ? 'selected' : '' }}>{{ $c->nama_cabang }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                

                 <!-- Salesman -->
                 <div class="{{ $userHasCabang ? 'md:col-span-2' : 'md:col-span-3' }} relative">
                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="ti ti-tie text-slate-400"></i>
                    </div>
                     <select name="kode_salesman_search" id="kode_salesman_search" class="select2Kodesalesmansearch w-full pl-10 pr-8 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 appearance-none cursor-pointer font-medium">
                         <option value="">Semua Salesman</option>
                     </select>
                </div>

                 <!-- No Faktur -->
                 <div class="{{ $userHasCabang ? 'md:col-span-2' : 'md:col-span-3' }} relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ti ti-barcode text-slate-400"></i>
                    </div>
                    <input type="text" name="no_faktur_search" value="{{ Request('no_faktur_search') }}" 
                        class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 placeholder-slate-400 transition-all font-medium"
                        placeholder="No. Faktur">
                </div>

                <!-- Kode Pelanggan -->
                 <div class="md:col-span-2 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ti ti-code text-slate-400"></i>
                    </div>
                    <input type="text" name="kode_pelanggan_search" value="{{ Request('kode_pelanggan_search') }}" 
                        class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 placeholder-slate-400 transition-all font-medium"
                        placeholder="Kode Pelanggan">
                </div>

                <!-- Nama Pelanggan -->
                 <div class="{{ $userHasCabang ? 'md:col-span-3' : 'md:col-span-3' }} relative">
                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ti ti-user text-slate-400"></i>
                    </div>
                    <input type="text" name="nama_pelanggan_search" value="{{ Request('nama_pelanggan_search') }}" 
                        class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 placeholder-slate-400 transition-all font-medium"
                        placeholder="Nama Pelanggan">
                </div>

                <!-- Search Button (Icon Only) -->
                <div class="md:col-span-1">
                    <button type="submit" class="h-full w-full bg-[#003d9e] hover:bg-blue-800 text-white rounded-lg font-medium text-sm transition-colors shadow-sm shadow-blue-200 flex items-center justify-center">
                        <i class="ti ti-search"></i>
                    </button>
                </div>
            </form>
        </div>

    </div> 
    <!-- End Filter Section Card -->

    <!-- Card List Container -->
    <div class="flex flex-col gap-2 mt-3">
        @forelse ($retur as $d)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-3 hover:shadow-md transition-shadow flex flex-col md:flex-row items-start md:items-center gap-3 relative">
                
                <!-- 1. Identitas (Left Fixed) -->
                <div class="flex items-start gap-3 w-full md:w-72 md:shrink-0 border-b md:border-b-0 md:border-r md:border-slate-200/60 pb-2 md:pb-0 md:pr-4">
                    <!-- Icon placeholder -->
                    <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
                        <i class="ti ti-arrow-back-up text-lg"></i>
                    </div>
                    
                    <div class="flex-1 min-w-0">
                         <div class="flex items-center gap-2 mb-0.5">
                            <span class="font-bold text-slate-800 text-sm truncate" title="{{ $d->no_retur }}">{{ $d->no_retur }}</span>
                        </div>
                        <div class="text-xs text-slate-500 font-medium mb-1">
                            {{ DateToIndo($d->tanggal) }}
                        </div>
                         <h4 class="font-bold text-slate-700 text-sm truncate" title="{{ $d->nama_pelanggan }}">
                             {{ textUpperCase($d->nama_pelanggan) }}
                        </h4>
                    </div>
                </div>

                <!-- 2. Details Info (Horizontal Grid) -->
                <div class="flex-1 w-full grid grid-cols-2 lg:grid-cols-5 gap-y-2 gap-x-3 items-center">
                    
                    <!-- Salesman -->
                     <div class="min-w-0">
                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold mb-0">Salesman</p>
                        <span class="text-sm font-medium text-slate-700 truncate block" title="{{ $d->nama_salesman }}">{{ textUpperCase($d->nama_salesman) }}</span>
                    </div>

                    <!-- Cabang -->
                    <div class="min-w-0">
                         <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold mb-0">Cabang</p>
                         <span class="text-sm font-medium text-slate-700">{{ textUpperCase($d->nama_cabang) }}</span>
                    </div>

                    <!-- Jenis Retur -->
                     <div class="min-w-0">
                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold mb-0">Jenis</p>
                        @if ($d->jenis_retur == 'GB')
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">
                                Ganti Barang
                            </span>
                        @else
                             <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded">
                                Potong Faktur
                            </span>
                        @endif
                    </div>

                    <!-- No Faktur Ref -->
                    <div class="min-w-0">
                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold mb-0">No. Faktur</p>
                        <span class="text-xs font-bold text-slate-600 bg-slate-100 px-1.5 py-0.5 rounded">{{ $d->no_faktur }}</span>
                    </div>

                    <!-- Total -->
                    <div class="min-w-0">
                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold mb-0">Total Retur</p>
                        <span class="text-sm font-bold text-[#003d9e]">{{ formatRupiah($d->total_retur) }}</span>
                    </div>

                </div>

                <!-- 3. Actions -->
                <div class="w-full md:w-auto flex flex-col md:flex-row items-center justify-end gap-1 border-t md:border-t-0 md:border-l border-slate-200/60 pt-2 md:pt-0 md:pl-4">
                    
                     <div class="inline-flex rounded-md shadow-sm isolate" role="group">
                        
                        <!-- Detail -->
                        @can('retur.show')
                            <a href="#" class="btnShow group relative w-8 h-8 flex items-center justify-center bg-white text-blue-600 hover:bg-blue-50 border-y border-l border-slate-200 rounded-l-lg hover:z-10 transition-all" 
                               no_retur="{{ Crypt::encrypt($d->no_retur) }}" title="Detail">
                                <i class="ti ti-file-description text-xs"></i>
                            </a>
                        @endcan
                        
                        <!-- Delete -->
                        @can('retur.delete')
                             <form method="POST" name="deleteform" class="deleteform d-inline" action="{{ route('retur.delete', Crypt::encrypt($d->no_retur)) }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="delete-confirm w-8 h-8 flex items-center justify-center bg-white text-slate-500 hover:bg-rose-50 hover:text-rose-500 border border-slate-200 rounded-r-lg hover:z-10 transition-all" title="Hapus">
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
                <h5 class="text-sm font-medium text-slate-600">Tidak ada data retur</h5>
                <p class="text-xs mt-1">Coba ubah filter pencarian anda.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-3 bg-white p-3 rounded-xl shadow-sm border border-slate-200 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="text-xs text-slate-500">
             Showing {{ $retur->firstItem() }} to {{ $retur->lastItem() }} of {{ $retur->total() }} entries
        </div>
        <div class="flex gap-1">
            {{ $retur->links('pagination::tailwind') }} 
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

@endsection

@push('myscript')
<script>
    $(function() {
        $(".flatpickr-date").flatpickr();

         // --- Standard Modal Logic (Tailwind) ---
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
        window.closeTailwindModal = closeModal; // Expose for child views if needed

        // --- Action Triggers ---
        $(".btnShow").click(function(e) {
            e.preventDefault();
            const no_retur = $(this).attr('no_retur');
            openModal(`/retur/${no_retur}/show`);
        });

        // --- Select2 Initialization ---
        const select2Kodecabangsearch = $('.select2Kodecabangsearch');
        if (select2Kodecabangsearch.length) {
            select2Kodecabangsearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2Kodesalesmansearch = $('.select2Kodesalesmansearch');
        if (select2Kodesalesmansearch.length) {
            select2Kodesalesmansearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Salesman',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        // --- Dependent Dropdown Logic ---
        function getsalesmanbyCabang() {
            var kode_cabang = $("#kode_cabang_search").val();
            var kode_salesman = "{{ Request('kode_salesman_search') }}";
            
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
                    console.log(respond);
                    $("#kode_salesman_search").html(respond);
                }
            });
        }

        // Initial Load
        getsalesmanbyCabang();

        // Change Event
        $("#kode_cabang_search").change(function(e) {
            getsalesmanbyCabang();
        });
        
        // SweetAlert Delete (using global logic or custom)
         $(".delete-confirm").click(function(e) {
            var form = $(this).closest("form");
            e.preventDefault();
            Swal.fire({
                title: 'Apakah Anda Yakin Data Ini Mau Di Hapus ?',
                text: "Jika Dihapus Maka Data Akan Hilang Permanen",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus Saja!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        });

    });
</script>
@endpush
