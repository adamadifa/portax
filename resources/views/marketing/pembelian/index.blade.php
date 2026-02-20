@extends('layouts.app')
@section('titlepage', 'Pembelian Marketing')

@section('content')
    <!-- Page Header -->
    <div class="mb-5 flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 leading-tight">Data Pembelian Marketing</h2>
            <p class="text-sm text-slate-500">Manage pembelian marketing data.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            @can('pembelianmarketing.create')
                <a href="{{ route('pembelianmarketing.create') }}" class="bg-[#003d9e] hover:bg-blue-800 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors shadow-sm shadow-blue-200 text-sm font-medium">
                    <i class="ti ti-plus"></i>
                    <span>Input Pembelian</span>
                </a>
            @endcan
        </div>
    </div>

    <!-- Info Alert -->
     <div class="mb-4 bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start gap-3">
        <i class="ti ti-info-circle text-blue-600 text-xl mt-0.5"></i>
        <div class="text-sm text-blue-800">
            <h5 class="font-bold mb-1">Informasi</h5>
            <p class="mb-0">
                Halaman untuk mengelola data pembelian marketing.
            </p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-4">
        <div class="p-3 border-b border-slate-100 bg-slate-50/50">
              <form action="{{ route('pembelianmarketing.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-x-3 gap-y-2">
                 <!-- Row 1 -->
                 <div class="md:col-span-2 relative">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ti ti-calendar text-slate-400"></i>
                    </div>
                      <input type="text" name="dari" value="{{ Request('dari') }}" class="flatpickr-date w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] placeholder-slate-400 transition-all font-medium" placeholder="Dari Tanggal">
                 </div>
                 <div class="md:col-span-2 relative">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ti ti-calendar text-slate-400"></i>
                    </div>
                      <input type="text" name="sampai" value="{{ Request('sampai') }}" class="flatpickr-date w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] placeholder-slate-400 transition-all font-medium" placeholder="Sampai Tanggal">
                 </div>
                 <div class="md:col-span-2 relative">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ti ti-barcode text-slate-400"></i>
                    </div>
                      <input type="text" name="no_bukti_search" value="{{ Request('no_bukti_search') }}" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] placeholder-slate-400 transition-all font-medium" placeholder="No. Bukti">
                 </div>
                 <div class="md:col-span-2 relative">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ti ti-barcode text-slate-400"></i>
                    </div>
                      <input type="text" name="kode_supplier_search" value="{{ Request('kode_supplier_search') }}" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] placeholder-slate-400 transition-all font-medium" placeholder="Kode Supplier">
                 </div>
                 <div class="md:col-span-2 relative">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ti ti-users text-slate-400"></i>
                    </div>
                      <input type="text" name="nama_supplier_search" value="{{ Request('nama_supplier_search') }}" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] placeholder-slate-400 transition-all font-medium" placeholder="Nama Supplier">
                 </div>
                 @php
                     $roles_access_all_cabang = config('global.roles_access_all_cabang');
                 @endphp
                 @if (in_array(auth()->user()->roles, $roles_access_all_cabang))
                 <div class="md:col-span-1 relative">
                     <select name="kode_cabang_search" class="w-full pl-2 pr-2 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] placeholder-slate-400 transition-all font-medium">
                         <option value="">Cabang</option>
                         @foreach ($cabang as $c)
                             <option value="{{ $c->kode_cabang }}" {{ Request('kode_cabang_search') == $c->kode_cabang ? 'selected' : '' }}>{{ $c->kode_cabang }}</option>
                         @endforeach
                     </select>
                 </div>
                 @endif
                  <div class="md:col-span-1">
                    <button type="submit" class="h-full w-full bg-[#003d9e] hover:bg-blue-800 text-white rounded-lg font-medium text-sm transition-colors shadow-sm shadow-blue-200 flex items-center justify-center py-2 md:py-0">
                        <i class="ti ti-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Data List -->
    <div class="flex flex-col gap-2 mt-3">
        @forelse ($pembelian as $d)
            @php
                 $total_netto = $d->total_bruto ?? 0;
            @endphp
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-3 hover:shadow-md transition-shadow flex flex-col md:flex-row items-center gap-3">
                <!-- Identitas -->
                 <div class="flex items-start gap-3 w-full md:w-72 md:shrink-0 border-b md:border-b-0 md:border-r md:border-slate-200/60 pb-2 md:pb-0 md:pr-4">
                     <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
                        <i class="ti ti-file-invoice text-lg"></i>
                    </div>
                     <div class="flex-1 min-w-0">
                         <div class="flex items-center gap-2 mb-0.5">
                            <span class="font-bold text-slate-800 text-sm truncate">{{ $d->no_bukti }}</span>
                            @if (!empty($d->kode_cabang))
                                <span class="bg-indigo-100 text-indigo-700 text-[10px] font-bold px-1.5 py-0.5 rounded ml-1">{{ $d->kode_cabang }}</span>
                            @endif
                        </div>
                        <div class="text-xs text-slate-500 font-medium mb-1">
                            {{ date('d-m-Y', strtotime($d->tanggal)) }}
                        </div>
                         <h4 class="font-bold text-slate-700 text-sm truncate">
                             {{ $d->nama_supplier }}
                        </h4>
                    </div>
                </div>
                
                 <!-- Detail Grid -->
                 <div class="flex-1 w-full grid grid-cols-2 lg:grid-cols-4 gap-y-2 gap-x-3 items-center">
                    <!-- Total -->
                    <div class="min-w-0">
                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold mb-0">Total</p>
                        <span class="text-sm font-bold text-[#003d9e]">{{ formatAngka($total_netto) }}</span>
                    </div>
                    <!-- Jenis -->
                    <div class="min-w-0">
                         <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold mb-0">Jenis</p>
                         @if ($d->jenis_transaksi == 'T')
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-600">
                                <i class="ti ti-cash"></i> Tunai
                            </span>
                        @else
                             <span class="inline-flex items-center gap-1 text-xs font-medium text-amber-600">
                                <i class="ti ti-credit-card"></i> Kredit
                            </span>
                        @endif
                    </div>
                     <!-- Status -->
                    <div class="min-w-0">
                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold mb-0">Status</p>
                        @if ($d->status == '1')
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700">LUNAS</span>
                        @else
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">BELUM LUNAS</span>
                        @endif
                    </div>
                 </div>
                 
                 <!-- Actions -->
                  <div class="w-full md:w-auto flex flex-col md:flex-row items-center justify-end gap-1 border-t md:border-t-0 md:border-l border-slate-200/60 pt-2 md:pt-0 md:pl-4">
                       <div class="inline-flex rounded-md shadow-sm isolate" role="group">
                           @can('pembelianmarketing.show')
                            <a href="{{ route('pembelianmarketing.show', Crypt::encrypt($d->no_bukti)) }}" class="group relative w-8 h-8 flex items-center justify-center bg-white text-blue-600 hover:bg-blue-50 border-y border-l border-slate-200 rounded-l-lg hover:z-10 transition-all" title="Detail">
                                <i class="ti ti-file-description text-xs"></i>
                            </a>
                           @endcan
                            @can('pembelianmarketing.delete')
                                <form method="POST" name="deleteform" class="deleteform d-inline" action="{{ route('pembelianmarketing.delete', Crypt::encrypt($d->no_bukti)) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-confirm w-8 h-8 flex items-center justify-center bg-white text-slate-500 hover:bg-rose-50 hover:text-rose-500 border-y border-r border-slate-200 rounded-r-lg hover:z-10 transition-all" title="Hapus">
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
                <h5 class="text-sm font-medium text-slate-600">Tidak ada data pembelian</h5>
                <p class="text-xs mt-1">Coba ubah filter pencarian anda.</p>
            </div>
        @endforelse
    </div>
    
     <!-- Pagination -->
    <div class="mt-3 bg-white p-3 rounded-xl shadow-sm border border-slate-200 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="text-xs text-slate-500">
             Showing {{ $pembelian->firstItem() }} to {{ $pembelian->lastItem() }} of {{ $pembelian->total() }} entries
        </div>
        <div class="flex gap-1">
            {{ $pembelian->links('pagination::tailwind') }} 
        </div>
    </div>
@endsection
@push('myscript')
<script>
    $(function() {
        $(".flatpickr-date").flatpickr();
    });
</script>
@endpush
