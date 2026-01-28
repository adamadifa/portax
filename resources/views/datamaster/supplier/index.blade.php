@extends('layouts.app')
@section('titlepage', 'Data Supplier')

@section('content')
    <!-- Page Header -->
    <div class="mb-5 flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Title & Subtitle -->
        <div>
            <h2 class="text-2xl font-bold text-slate-800 leading-tight">Data Supplier</h2>
            <p class="text-sm text-slate-500">Manage supplier data and information.</p>
        </div>
        <!-- Actions -->
        @can('supplier.create')
            <button id="btncreateSupplier" class="bg-[#003d9e] hover:bg-blue-800 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors shadow-sm shadow-blue-200 text-sm font-medium">
                <i class="ti ti-plus"></i>
                <span>Tambah Supplier</span>
            </button>
        @endcan
    </div>

    <!-- Filter Section (Matches Pelanggan Style) -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-4">
        <div class="p-3 border-b border-slate-100 bg-slate-50/50">
            <form action="{{ route('supplier.index') }}" class="flex flex-col md:flex-row gap-3">
                
                <!-- Nama Supplier Filter -->
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ti ti-search text-slate-400"></i>
                    </div>
                    <input type="text" name="nama_supplier" value="{{ Request('nama_supplier') }}" 
                        class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 placeholder-slate-400 transition-all"
                        placeholder="Cari Nama Supplier">
                </div>

                <!-- Search Button -->
                <div class="w-full md:w-auto">
                    <button type="submit" class="w-full md:w-auto bg-[#003d9e] hover:bg-blue-800 text-white px-4 py-2.5 rounded-lg font-medium text-sm transition-colors shadow-sm shadow-blue-200 flex items-center justify-center gap-2">
                        <i class="ti ti-search"></i>
                        <span class="md:hidden">Cari</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Card List Container (Compact Full-Width) -->
    <div class="flex flex-col gap-2 mt-3">
        @forelse ($supplier as $d)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-2 hover:shadow-md transition-shadow flex flex-col md:flex-row items-center gap-3">
                
                <!-- 1. Identity (Fixed Width for Alignment) -->
                <div class="flex items-center gap-3 w-full md:w-72 md:shrink-0 border-b md:border-b-0 md:border-r border-slate-100 pb-2 md:pb-0 md:pr-3">
                    <!-- Icon Placeholder (or Logo if available) -->
                    <div class="flex-shrink-0 relative">
                        <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 border-2 border-slate-100">
                            <i class="ti ti-building-store text-lg"></i>
                        </div>
                    </div>
                    
                    <!-- Text Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-0.5">
                             <h4 class="font-bold text-slate-800 text-sm truncate" title="{{ textupperCase($d->nama_supplier) }}">
                                {{ textupperCase($d->nama_supplier) }}
                            </h4>
                        </div>
                         <div class="flex flex-col gap-0.5">
                             <span class="inline-flex items-center gap-1.5 text-xs text-[#003d9e] font-mono font-medium bg-blue-50 px-2 py-0.5 rounded w-fit">
                                <i class="ti ti-barcode text-[10px]"></i> {{ $d->kode_supplier }}
                            </span>
                         </div>
                    </div>
                </div>

                <!-- 2. Details Info (Single Row) -->
                <div class="flex-1 w-full grid grid-cols-2 md:grid-cols-4 gap-y-1 gap-x-3">
                     <!-- Alamat -->
                    <div class="min-w-0">
                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold mb-0">Alamat</p>
                        <div class="flex items-center gap-1">
                            <i class="ti ti-map-pin text-slate-400 text-xs"></i>
                            <span class="text-sm font-medium text-slate-700 truncate" title="{{ $d->alamat_supplier }}">{{ textCamelCase($d->alamat_supplier) }}</span>
                        </div>
                    </div>
                    
                    <!-- Contact Person -->
                     <div class="min-w-0">
                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold mb-0">Contact</p>
                        <div class="flex items-center gap-1">
                            <i class="ti ti-user text-slate-400 text-xs"></i>
                            <span class="text-sm font-medium text-slate-700 truncate">{!! $d->contact_person !!}</span>
                        </div>
                    </div>

                    <!-- No HP -->
                    <div class="min-w-0">
                         <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold mb-0">No. HP</p>
                         <div class="flex items-center gap-1">
                             <i class="ti ti-phone text-slate-400 text-xs"></i>
                             <span class="text-sm font-medium text-slate-700">{{ $d->no_hp_supplier }}</span>
                         </div>
                    </div>

                    <!-- Email/Rekening (Combined or Split) -->
                    <div class="min-w-0">
                         <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold mb-0">Email</p>
                         <div class="flex items-center gap-1">
                             <i class="ti ti-mail text-slate-400 text-xs"></i>
                             <span class="text-sm font-medium text-slate-700 truncate" title="{{ $d->email_supplier }}">{{ $d->email_supplier ?? '-' }}</span>
                         </div>
                    </div>
                </div>

                <!-- 3. Actions -->
                <div class="w-full md:w-auto flex flex-col md:flex-row items-center border-t md:border-t-0 md:border-l border-slate-100 pt-2 md:pt-0 md:pl-3 justify-end md:justify-center">
                    <div class="inline-flex rounded-md shadow-sm isolate" role="group">
                        @can('supplier.edit')
                            <button class="editSupplier w-8 h-8 flex items-center justify-center bg-amber-50 text-amber-600 hover:bg-amber-100 transition-colors border border-amber-200 rounded-l-lg focus:z-10"
                                kode_supplier="{{ Crypt::encrypt($d->kode_supplier) }}" title="Edit">
                                <i class="ti ti-pencil text-xs"></i>
                            </button>
                        @endcan
                        @can('supplier.delete')
                            <form method="POST" name="deleteform" class="deleteform inline-block"
                                action="{{ route('supplier.delete', Crypt::encrypt($d->kode_supplier)) }}">
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
            Showing {{ $supplier->firstItem() }} to {{ $supplier->lastItem() }} of {{ $supplier->total() }} entries
        </div>
        <div class="flex gap-1">
            {{ $supplier->links('pagination::tailwind') }} 
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
        $("#btncreateSupplier").click(function(e) { 
            e.preventDefault(); 
            openModal('/supplier/create'); 
        });
        
        $(document).on('click', '.editSupplier', function(e) {
            e.preventDefault();
            var kode_supplier = $(this).attr("kode_supplier");
            openModal('/supplier/' + kode_supplier + '/edit');
        });
    });
</script>
@endpush
