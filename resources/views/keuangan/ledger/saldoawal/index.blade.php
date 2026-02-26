@extends('layouts.app')
@if (request()->is('samutasibank'))
    @section('titlepage', 'Saldo Awal Mutasi Bank')
@else
    @section('titlepage', 'Saldo Awal Ledger')
@endif

@section('content')
@section('navigasi')
    @if (request()->is('samutasibank'))
        <span>Saldo Awal Mutasi Bank</span>
    @else
        <span>Saldo Awal Ledger</span>
    @endif
@endsection

<!-- Page Header -->
<div class="mb-5 flex flex-col md:flex-row items-center justify-between gap-4">
    <!-- Title & Subtitle -->
    <div>
        <h2 class="text-2xl font-bold text-slate-800 leading-tight">
            {{ request()->is('samutasibank') ? 'Saldo Awal Mutasi Bank' : 'Saldo Awal Ledger' }}
        </h2>
        <p class="text-sm text-slate-500">Manage opening balances for bank ledgers.</p>
    </div>
    <!-- Actions -->
    <div class="flex flex-wrap gap-2">
        @canany(['saledger.create', 'samutasibank.create'])
            <button class="bg-[#003d9e] hover:bg-blue-800 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors shadow-sm shadow-blue-200 text-sm font-medium" id="btnCreate">
                <i class="ti ti-plus"></i>
                <span>Buat Saldo Awal</span>
            </button>
        @endcanany
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Navigation Tabs & Filter Section -->
    <div class="col-span-12">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <!-- Tabs Navigation -->
            <div class="mb-6 border-b border-slate-100">
                @if (request()->is('samutasibank'))
                    @include('layouts.navigation_mutasibank')
                @else
                    @include('layouts.navigation_ledger')
                @endif
            </div>

            <!-- Filter Form -->
            <form action="{{ URL::current() }}" class="grid grid-cols-1 md:grid-cols-12 gap-x-3 gap-y-3">
                <!-- Select Bank -->
                <div class="md:col-span-5 relative">
                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="ti ti-building-bank text-slate-400"></i>
                    </div>
                    <select name="kode_bank_search" id="kode_bank_search" class="select2Kodebanksearch w-full">
                        <option value="">Pilih Bank</option>
                        @foreach ($bank as $d)
                            <option value="{{ $d->kode_bank }}" {{ Request('kode_bank_search') == $d->kode_bank ? 'selected' : '' }}>
                                {{ $d->nama_bank }} {{ !empty($d->no_rekening) ? '(' . $d->no_rekening . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Bulan -->
                <div class="md:col-span-3 relative">
                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="ti ti-calendar-event text-slate-400"></i>
                    </div>
                    <select name="bulan" id="bulan" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 appearance-none cursor-pointer font-medium">
                        <option value="">Bulan</option>
                        @foreach ($list_bulan as $d)
                            <option value="{{ $d['kode_bulan'] }}" {{ Request('bulan') == $d['kode_bulan'] ? 'selected' : '' }}>
                                {{ $d['nama_bulan'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                 <!-- Tahun -->
                 <div class="md:col-span-3 relative">
                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="ti ti-calendar text-slate-400"></i>
                    </div>
                    <select name="tahun" id="tahun" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 appearance-none cursor-pointer font-medium">
                        <option value="">Tahun</option>
                        @for ($t = $start_year; $t <= date('Y'); $t++)
                            <option value="{{ $t }}" @if(!empty(Request('tahun'))) {{ Request('tahun') == $t ? 'selected' : '' }} @else {{ date('Y') == $t ? 'selected' : '' }} @endif>
                                {{ $t }}
                            </option>
                        @endfor
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="md:col-span-1">
                    <button type="submit" class="h-full w-full bg-[#003d9e] hover:bg-blue-800 text-white rounded-lg font-medium text-sm transition-colors shadow-sm shadow-blue-200 flex items-center justify-center">
                        <i class="ti ti-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Section -->
    <div class="col-span-12">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-white uppercase bg-[#003d9e]">
                        <tr>
                            <th class="px-4 py-3 font-bold">No</th>
                            <th class="px-4 py-3 font-bold">Kode</th>
                            <th class="px-4 py-3 font-bold">Tanggal</th>
                            <th class="px-4 py-3 font-bold text-center">Periode</th>
                            <th class="px-4 py-3 font-bold">Bank</th>
                            <th class="px-4 py-3 font-bold text-right">Jumlah</th>
                            <th class="px-4 py-3 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($saldo_awal as $d)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-4 py-3 text-slate-500">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 text-slate-600 font-mono">{{ $d->kode_saldo_awal }}</td>
                                <td class="px-4 py-3 text-slate-700 whitespace-nowrap">{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded text-xs font-bold uppercase tracking-wider">
                                        {{ $nama_bulan[$d->bulan] }} {{ $d->tahun }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-700 font-medium">
                                    {{ $d->nama_bank }} 
                                    <span class="block text-[10px] text-slate-400 font-mono">{{ $d->no_rekening }}</span>
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-slate-800">
                                    {{ formatAngka($d->jumlah) }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @can('saledger.delete')
                                        <form method="POST" action="{{ route('saledger.delete', Crypt::encrypt($d->kode_saldo_awal)) }}" class="deleteform inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="cancel-confirm group relative w-8 h-8 flex items-center justify-center bg-white text-slate-400 hover:bg-rose-50 hover:text-rose-500 border border-slate-200 rounded-lg transition-all shadow-sm">
                                                <i class="ti ti-trash text-xs"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-slate-400">
                                    <div class="flex flex-col items-center">
                                        <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mb-2">
                                            <i class="ti ti-inbox text-2xl text-slate-300"></i>
                                        </div>
                                        <p class="text-sm">Data saldo awal belum tersedia.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Tailwind Modal Helper -->
<div id="tailwindModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300 ease-out opacity-0" id="modalBackdrop"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all duration-300 ease-out sm:my-8 sm:w-full sm:max-w-xl opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" id="modalPanel">
                
                <!-- Close Button -->
                <button type="button" class="absolute top-3 right-3 text-slate-400 hover:text-red-500 transition-colors focus:outline-none z-50 p-2 rounded-full hover:bg-slate-50" onclick="closeTailwindModal()">
                    <i class="ti ti-x text-xl"></i>
                </button>

                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-bold leading-6 text-slate-900 mb-4 border-b border-slate-100 pb-2" id="modalTitle">Modal Process</h3>
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

@section('style')
<style>
    /* Select2 Customization to match Tailwind Inputs perfectly */
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
</style>
@endsection

@endsection

@push('myscript')
<script>
    $(function() {
        // --- Select2 ---
        const select2Kodebanksearch = $('.select2Kodebanksearch');
        if (select2Kodebanksearch.length) {
            select2Kodebanksearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Bank',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        // --- Modal Logic ---
        const modal = document.getElementById('tailwindModal');
        const backdrop = document.getElementById('modalBackdrop');
        const panel = document.getElementById('modalPanel');
        const content = document.getElementById('modalContent');
        const modalTitle = document.getElementById('modalTitle');
        
        function openModal(url, title) {
            if(title) modalTitle.innerText = title;
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

        $("#btnCreate").click(function(e) {
            e.preventDefault();
            openModal('/saledger/create', 'Buat Saldo Awal Ledger');
        });

        // SweetAlert Delete
        $(".cancel-confirm").click(function(e) {
            var form = $(this).closest("form");
            e.preventDefault();
            Swal.fire({
                title: 'Apakah link ini mau dihapus?',
                text: "Jika dihapus maka data akan hilang permanen",
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
