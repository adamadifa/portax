@extends('layouts.app')
@section('titlepage', 'Ganti Barang')

@section('content')
@section('navigasi')
   <span>Ganti Barang</span>
@endsection
    <!-- Page Header -->
    <div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Ganti Barang Gudang Cabang</h2>
            <p class="text-slate-500 text-sm">Kelola data mutasi ganti barang produk di gudang cabang.</p>
        </div>
        @can('gantibarangcbg.create')
            <a href="#" id="btnCreate" class="bg-[#003d9e] hover:bg-blue-800 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors shadow-sm shadow-blue-200">
                <i class="fas fa-plus"></i>
                <span>Tambah Ganti Barang</span>
            </a>
        @endcan
    </div>

@section('style')
<style>
    /* Select2 Customization to match Tailwind Inputs */
    .select2-container .select2-selection--single {
        height: 42px !important;
        background-color: #fff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 0.5rem !important;
        box-sizing: border-box !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #334155 !important;
        font-size: 0.875rem !important;
        font-weight: 500 !important;
        padding-left: 2.75rem !important;
        padding-right: 2rem !important;
        line-height: 40px !important;
        display: block !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #94a3b8 !important;
    }
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
    .select2-container--default.select2-container--open .select2-selection--single,
    .select2-container--default .select2-selection--single:focus {
        border-color: #003d9e !important;
        box-shadow: 0 0 0 2px rgba(0, 61, 158, 0.2) !important;
        outline: none;
    }
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
        background-color: #003d9e !important;
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
</style>
@endsection

    <!-- Content Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <!-- Search & Filter / Toolbar -->
        <div class="p-3 border-b border-slate-100 bg-slate-50/50">
            <form action="{{ route('gantibarangcbg.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-x-3 gap-y-1" id="formSearch">
                
                @php $userHasCabang = false; @endphp
                @hasanyrole($roles_show_cabang)
                    @php $userHasCabang = true; @endphp
                @endhasanyrole

                <!-- Dari -->
                <div class="{{ $userHasCabang ? 'md:col-span-3' : 'md:col-span-5' }} relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-calendar-alt text-slate-400"></i>
                    </div>
                    <input type="text" name="dari" value="{{ Request('dari') }}" class="flatpickr-date w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 transition-all font-medium" placeholder="Dari Tanggal">
                </div>

                <!-- Sampai -->
                <div class="{{ $userHasCabang ? 'md:col-span-3' : 'md:col-span-6' }} relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-calendar-alt text-slate-400"></i>
                    </div>
                    <input type="text" name="sampai" value="{{ Request('sampai') }}" class="flatpickr-date w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 transition-all font-medium" placeholder="Sampai Tanggal">
                </div>

                <!-- Cabang (Conditional) -->
                @if($userHasCabang)
                <div class="md:col-span-5 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-building text-slate-400"></i>
                    </div>
                    <select name="kode_cabang_search" class="select2Kodecabangsearch w-full pl-10 pr-8 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 appearance-none cursor-pointer font-medium">
                        <option value="">Semua Cabang</option>
                        @foreach ($cabang as $c)
                            <option value="{{ $c->kode_cabang }}" {{ Request('kode_cabang_search') == $c->kode_cabang ? 'selected' : '' }}>{{ strtoupper($c->nama_cabang) }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <!-- Search Button (Icon Only) -->
                <div class="md:col-span-1 md:col-start-12">
                    <button type="submit" class="h-full w-full py-2 bg-[#003d9e] hover:bg-blue-800 text-white rounded-lg font-medium text-sm transition-colors shadow-sm shadow-blue-200 flex items-center justify-center">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                        <th class="px-4 py-3">No. Mutasi</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Cabang</th>
                        <th class="px-4 py-3">Keterangan</th>
                        <th class="px-4 py-3 text-right">#</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($gantibarang as $d)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="px-4 py-3">
                                <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-xs font-mono font-medium border border-slate-200">{{ $d->no_mutasi }}</span>
                            </td>
                            <td class="px-4 py-3 text-slate-600 text-sm whitespace-nowrap">{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                            <td class="px-4 py-3 text-slate-800 font-medium text-sm whitespace-nowrap">{{ textUpperCase($d->nama_cabang) }}</td>
                            <td class="px-4 py-3 text-slate-600 text-sm whitespace-nowrap">{{ $d->keterangan }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @can('gantibarangcbg.edit')
                                        <button class="btnEdit w-8 h-8 rounded-lg flex items-center justify-center bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-colors"
                                            no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}" title="Edit">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>
                                    @endcan
                                    @can('gantibarangcbg.show')
                                        <button class="btnShow w-8 h-8 rounded-lg flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors"
                                            no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}" title="Detail">
                                            <i class="fas fa-eye text-xs"></i>
                                        </button>
                                    @endcan
                                    @can('gantibarangcbg.delete')
                                        <form method="POST" name="deleteform" class="deleteform inline-block"
                                            action="{{ route('gantibarangcbg.delete', Crypt::encrypt($d->no_mutasi)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="delete-confirm w-8 h-8 rounded-lg flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-100 transition-colors" title="Delete">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="far fa-folder-open text-4xl mb-3 text-slate-300"></i>
                                    <p>Tidak ada data mutasi ganti barang ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-xs text-slate-500">
                @if($gantibarang->total() > 0)
                    Showing {{ $gantibarang->firstItem() }} to {{ $gantibarang->lastItem() }} of {{ $gantibarang->total() }} entries
                @else
                    Showing 0 entries
                @endif
            </div>
            <div>
                {{ $gantibarang->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>

<!-- Tailwind Modal Implementation for Show/Create -->
<div id="tailwindModal" class="fixed inset-0 z-[1060] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Background backdrop -->
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300 ease-out opacity-0" id="modalBackdrop"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <!-- Modal panel -->
            <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all duration-300 ease-out sm:my-8 sm:w-full sm:max-w-4xl opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" id="modalPanel">
                <div id="modalContent">
                    <!-- Content loaded via AJAX -->
                    <div class="flex justify-center p-8">
                        <i class="fas fa-circle-notch fa-spin text-[#003d9e] text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>
   // --- Global Tailwind Modal Functions ---
   window.openTailwindModal = function() {
       const backdrop = document.getElementById('modalBackdrop');
       const panel = document.getElementById('modalPanel');
       const modal = document.getElementById('tailwindModal');
       
       // Show container
       modal.classList.remove('hidden');
       
       // Trigger reflow
       void modal.offsetWidth;
       
       // Animate in
       backdrop.classList.remove('opacity-0');
       panel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
   };

   window.closeTailwindModal = function() {
       const backdrop = document.getElementById('modalBackdrop');
       const panel = document.getElementById('modalPanel');
       const modal = document.getElementById('tailwindModal');
       
       // Animate out
       backdrop.classList.add('opacity-0');
       panel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
       
       // Hide container after animation
       setTimeout(() => {
           modal.classList.add('hidden');
           document.getElementById('modalContent').innerHTML = `
               <div class="flex justify-center p-8">
                   <i class="fas fa-circle-notch fa-spin text-[#003d9e] text-2xl"></i>
               </div>
           `;
       }, 300);
   };

   // Close modal when clicking backdrop
   document.getElementById('modalBackdrop').addEventListener('click', window.closeTailwindModal);


   $(function() {
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

      $("#btnCreate").click(function(e) {
         e.preventDefault();
         window.openTailwindModal();
         $("#modalContent").load(`/gantibarangcbg/create`);
      });

      $(".btnShow").click(function(e) {
         e.preventDefault();
         var no_mutasi = $(this).attr("no_mutasi");
         window.openTailwindModal();
         $("#modalContent").load(`/gantibarangcbg/${no_mutasi}/show`);
      });

      $(".btnEdit").click(function(e) {
         e.preventDefault();
         var no_mutasi = $(this).attr("no_mutasi");
         window.openTailwindModal();
         $("#modalContent").load(`/gantibarangcbg/${no_mutasi}/edit`);
      });
   });
</script>
@endpush
