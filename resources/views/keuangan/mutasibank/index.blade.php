@extends('layouts.app')
@section('titlepage', 'Mutasi Bank')

@section('content')
@section('navigasi')
    <span>Mutasi Bank</span>
@endsection

<!-- Page Header -->
<div class="mb-5 flex flex-col md:flex-row items-center justify-between gap-4">
    <!-- Title & Subtitle -->
    <div>
        <h2 class="text-2xl font-bold text-slate-800 leading-tight">Mutasi Bank</h2>
        <p class="text-sm text-slate-500">Bank ledger and transaction history.</p>
    </div>
    <!-- Actions -->
    <div class="flex flex-wrap gap-2">
        @can('mutasibank.create')
            <button class="bg-[#003d9e] hover:bg-blue-800 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors shadow-sm shadow-blue-200 text-sm font-medium" id="btnCreate">
                <i class="ti ti-plus"></i>
                <span>Input Mutasi Bank</span>
            </button>
        @endcan
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Navigation Tabs & Filter Section -->
    <div class="col-span-12">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <!-- Tabs Navigation -->
            <div class="mb-6 border-b border-slate-100">
                @include('layouts.navigation_mutasibank')
            </div>

            <!-- Filter Form -->
            <form action="{{ route('mutasibank.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-x-3 gap-y-3">
                <!-- Dari Tgl -->
                <div class="md:col-span-3 relative">
                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ti ti-calendar text-slate-400"></i>
                    </div>
                    <input type="text" name="dari" value="{{ Request('dari') }}" 
                        class="flatpickr-date w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 placeholder-slate-400 transition-all font-medium"
                        placeholder="Dari">
                </div>
                 <!-- Sampai Tgl -->
                 <div class="md:col-span-3 relative">
                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ti ti-calendar text-slate-400"></i>
                    </div>
                    <input type="text" name="sampai" value="{{ Request('sampai') }}" 
                        class="flatpickr-date w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 placeholder-slate-400 transition-all font-medium"
                        placeholder="Sampai">
                </div>

                <!-- Select Bank -->
                <div class="md:col-span-5 relative">
                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="ti ti-building-bank text-slate-400"></i>
                    </div>
                    <select name="kode_bank_search" id="kode_bank_search" class="select2Kodebanksearch w-full pl-10 pr-8 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 appearance-none cursor-pointer font-medium">
                        <option value="">Pilih Bank</option>
                        @foreach ($bank as $d)
                            <option value="{{ $d->kode_bank }}" {{ Request('kode_bank_search') == $d->kode_bank ? 'selected' : '' }}>
                                {{ $d->nama_bank }} {{ !empty($d->no_rekening) ? '(' . $d->no_rekening . ')' : '' }}
                            </option>
                        @endforeach
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

    <!-- Ledger Table -->
    <div class="col-span-12">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-white uppercase bg-[#003d9e]">
                        <tr>
                            <th class="px-4 py-3 font-bold">Tanggal</th>
                            <th class="px-4 py-3 font-bold">No Bukti</th>
                            <th class="px-4 py-3 font-bold">Keterangan</th>
                            <th class="px-4 py-3 font-bold">Akun</th>
                            <th class="px-4 py-3 font-bold text-right">Debet</th>
                            <th class="px-4 py-3 font-bold text-right">Kredit</th>
                            <th class="px-4 py-3 font-bold text-right">Saldo</th>
                            <th class="px-4 py-3 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @php
                            $saldo = $saldo_awal != null ? $saldo_awal->jumlah - $mutasi->debet + $mutasi->kredit : 0;
                            $total_debet = 0;
                            $total_kredit = 0;
                        @endphp

                        <tr class="bg-[#003d9e] text-white font-bold">
                            <td colspan="6" class="px-4 py-3 text-right">SALDO AWAL</td>
                            <td class="px-4 py-3 text-right text-white">
                                @if ($saldo_awal != null)
                                    {{ formatAngka($saldo) }}
                                @else
                                    <span class="px-2 py-0.5 bg-rose-500 rounded text-[10px]">BELUM DI SET</span>
                                @endif
                            </td>
                            <td></td>
                        </tr>

                        @forelse ($ledger as $d)
                            @php
                                $color_cr = !empty($d->kode_cr) ? 'bg-blue-50' : '';
                                $debet = $d->debet_kredit == 'D' ? $d->jumlah : 0;
                                $kredit = $d->debet_kredit == 'K' ? $d->jumlah : 0;
                                $saldo = $saldo - $debet + $kredit;

                                $total_debet += $debet;
                                $total_kredit += $kredit;
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors group {{ $color_cr }}">
                                <td class="px-4 py-3 text-slate-700 whitespace-nowrap">{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                                <td class="px-4 py-3 text-slate-600 font-mono">{{ $d->no_bukti }}</td>
                                <td class="px-4 py-3 text-slate-700">
                                    <div class="font-medium">{{ textCamelCase($d->keterangan) }}</div>
                                    @if (!empty($d->kode_cr))
                                        <span class="inline-block mt-0.5 px-1.5 py-0.5 bg-blue-100 text-blue-700 rounded text-[10px] font-bold">Cost Ratio</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    <div class="flex items-center gap-2">
                                         <span class="px-1.5 py-0.5 bg-slate-100 rounded text-slate-500 font-mono text-xs">{{ $d->kode_akun }}</span>
                                         <span class="truncate max-w-[150px]">{{ $d->nama_akun }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-rose-600">
                                    {{ $debet > 0 ? formatAngka($debet) : '' }}
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-emerald-600">
                                    {{ $kredit > 0 ? formatAngka($kredit) : '' }}
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-slate-800 bg-slate-50/50">
                                    {{ formatAngka($saldo) }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="inline-flex rounded-md shadow-sm isolate">
                                        @can('mutasibank.edit')
                                            <a href="#" class="btnEdit group relative w-8 h-8 flex items-center justify-center bg-white text-amber-500 hover:bg-amber-50 border border-slate-200 rounded-l-lg hover:z-10 transition-all" no_bukti="{{ Crypt::encrypt($d->no_bukti) }}">
                                                <i class="ti ti-edit text-xs"></i>
                                            </a>
                                        @endcan
                                        @can('mutasibank.delete')
                                            <form method="POST" action="{{ route('mutasibank.delete', Crypt::encrypt($d->no_bukti)) }}" class="deleteform">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="cancel-confirm group relative w-8 h-8 flex items-center justify-center bg-white text-slate-500 hover:bg-rose-50 hover:text-rose-500 border-y border-r border-slate-200 rounded-r-lg hover:z-10 transition-all">
                                                    <i class="ti ti-trash text-xs"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-slate-400">
                                    <div class="flex flex-col items-center">
                                        <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mb-2">
                                            <i class="ti ti-inbox text-2xl text-slate-300"></i>
                                        </div>
                                        <p class="text-sm">Tidak ada transaksi pada periode ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Summary Footer -->
    <div class="col-span-12 sticky bottom-4 z-10">
        <div class="bg-[#003d9e] text-white rounded-xl shadow-lg p-4 flex flex-col md:flex-row items-center justify-between gap-4 border border-blue-800">
            <div class="flex items-center gap-4 text-sm">
                <div>
                     <span class="text-blue-200 text-xs block uppercase tracking-wider">Total Debet</span>
                     <span class="font-bold text-rose-300">- {{ formatAngka($total_debet) }}</span>
                </div>
                <div class="w-px h-8 bg-blue-600"></div>
                <div>
                     <span class="text-blue-200 text-xs block uppercase tracking-wider">Total Kredit</span>
                     <span class="font-bold text-emerald-300">+ {{ formatAngka($total_kredit) }}</span>
                </div>
            </div>
            <div class="text-center md:text-right">
                <span class="text-blue-200 text-xs block uppercase tracking-wider font-bold mb-1">Saldo Akhir</span>
                <span class="text-2xl font-black">{{ formatAngka($saldo) }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Tailwind Modal Helper -->
<div id="tailwindModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300 ease-out opacity-0" id="modalBackdrop"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all duration-300 ease-out sm:my-8 sm:w-full sm:max-w-4xl opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" id="modalPanel">
                
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
        $(".flatpickr-date").flatpickr();

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
            openModal('/mutasibank/create', 'Input Mutasi Bank');
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            const no_bukti = $(this).attr('no_bukti');
            openModal(`/mutasibank/${no_bukti}/edit`, 'Edit Mutasi Bank');
        });

        // SweetAlert Delete
        $(".cancel-confirm").click(function(e) {
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
