@extends('layouts.app')
@section('titlepage', 'Saldo Awal Gudang Cabang')

@section('content')
    <!-- Page Header -->
    <div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Saldo Awal Gudang Cabang</h2>
            <p class="text-slate-500 text-sm">Kelola saldo awal stok gudang cabang.</p>
        </div>
        @can('sagudangcabang.create')
            <a href="{{ route('sagudangcabang.create') }}" class="bg-[#003d9e] hover:bg-blue-800 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors shadow-sm shadow-blue-200">
                <i class="fas fa-plus"></i>
                <span>Buat Saldo Awal</span>
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
            <form action="{{ route('sagudangcabang.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-x-3 gap-y-1">

                @php $userHasCabang = false; @endphp
                @hasanyrole($roles_show_cabang)
                    @php $userHasCabang = true; @endphp
                @endhasanyrole

                <!-- Cabang (Conditional) -->
                @if($userHasCabang)
                <div class="md:col-span-3 relative">
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

                <!-- Bulan -->
                <div class="{{ $userHasCabang ? 'md:col-span-3' : 'md:col-span-4' }} relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-calendar-alt text-slate-400"></i>
                    </div>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-slate-400 text-xs"></i>
                    </div>
                    <select name="bulan" class="w-full pl-10 pr-8 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 appearance-none transition-all font-medium">
                        <option value="">Bulan</option>
                        @foreach ($list_bulan as $d)
                            <option {{ Request('bulan') == $d['kode_bulan'] ? 'selected' : '' }} value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tahun -->
                <div class="{{ $userHasCabang ? 'md:col-span-2' : 'md:col-span-3' }} relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-calendar text-slate-400"></i>
                    </div>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-slate-400 text-xs"></i>
                    </div>
                    <select name="tahun" class="w-full pl-10 pr-8 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 appearance-none transition-all font-medium">
                        <option value="">Tahun</option>
                        @for ($t = $start_year; $t <= date('Y'); $t++)
                            <option @if (!empty(Request('tahun'))) {{ Request('tahun') == $t ? 'selected' : '' }}
                                    @else
                                    {{ date('Y') == $t ? 'selected' : '' }} @endif
                                value="{{ $t }}">{{ $t }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Kondisi -->
                <div class="{{ $userHasCabang ? 'md:col-span-3' : 'md:col-span-4' }} relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-box text-slate-400"></i>
                    </div>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-slate-400 text-xs"></i>
                    </div>
                    <select name="kondisi" class="w-full pl-10 pr-8 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 appearance-none transition-all font-medium">
                        <option value="">GOOD / BAD</option>
                        <option value="GS" {{ Request('kondisi') == 'GS' ? 'selected' : '' }}>GOOD STOK</option>
                        <option value="BS" {{ Request('kondisi') == 'BS' ? 'selected' : '' }}>BAD STOK</option>
                    </select>
                </div>

                <!-- Search Button (Icon Only) -->
                <div class="md:col-span-1">
                    <button type="submit" class="h-full w-full bg-[#003d9e] hover:bg-blue-800 text-white rounded-lg font-medium text-sm transition-colors shadow-sm shadow-blue-200 flex items-center justify-center">
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
                        <th class="px-3 py-2">No</th>
                        <th class="px-3 py-2">Kode</th>
                        <th class="px-3 py-2">Bulan</th>
                        <th class="px-3 py-2">Tahun</th>
                        <th class="px-3 py-2">Good/Bad</th>
                        <th class="px-3 py-2">Cabang</th>
                        <th class="px-3 py-2">Tanggal</th>
                        <th class="px-3 py-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($saldo_awal as $d)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="px-3 py-2 text-slate-500 text-sm">{{ $loop->iteration + $saldo_awal->firstItem() - 1 }}</td>
                            <td class="px-3 py-2">
                                <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-xs font-mono font-medium border border-slate-200">{{ $d->kode_saldo_awal }}</span>
                            </td>
                            <td class="px-3 py-2 text-slate-800 font-medium text-sm">{{ $nama_bulan[$d->bulan] }}</td>
                            <td class="px-3 py-2 text-slate-600 text-sm">{{ $d->tahun }}</td>
                            <td class="px-3 py-2">
                                @if ($d->kondisi == 'GS')
                                    <span class="bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-full text-xs font-medium border border-emerald-100 inline-flex items-center gap-1">
                                        <i class="fas fa-check-circle text-[10px]"></i> GOOD STOK
                                    </span>
                                @else
                                    <span class="bg-red-50 text-red-600 px-2 py-0.5 rounded-full text-xs font-medium border border-red-100 inline-flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle text-[10px]"></i> BAD STOK
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 py-2 text-slate-600 text-sm font-medium">{{ textUpperCase($d->nama_cabang) }}</td>
                            <td class="px-3 py-2 text-slate-600 text-sm">{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                            <td class="px-3 py-2 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @can('sagudangcabang.show')
                                        <button class="btnShow w-8 h-8 rounded-lg flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors"
                                            kode_saldo_awal="{{ Crypt::encrypt($d->kode_saldo_awal) }}" title="Detail">
                                            <i class="fas fa-eye text-xs"></i>
                                        </button>
                                    @endcan
                                    @can('sagudangcabang.edit')
                                        <button class="btnEdit w-8 h-8 rounded-lg flex items-center justify-center bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-colors"
                                            kode_saldo_awal="{{ Crypt::encrypt($d->kode_saldo_awal) }}" title="Edit">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>
                                    @endcan
                                    @can('sagudangcabang.delete')
                                        <form method="POST" name="deleteform" class="deleteform inline-block"
                                            action="{{ route('sagudangcabang.delete', Crypt::encrypt($d->kode_saldo_awal)) }}">
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
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="far fa-folder-open text-4xl mb-3 text-slate-300"></i>
                                    <p>Tidak ada data saldo awal ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <div class="text-xs text-slate-500">
                @if($saldo_awal->total() > 0)
                    Showing {{ $saldo_awal->firstItem() }} to {{ $saldo_awal->lastItem() }} of {{ $saldo_awal->total() }} entries
                @else
                    Showing 0 entries
                @endif
            </div>
            <div class="flex gap-1">
                {{ $saldo_awal->links('pagination::tailwind') }}
            </div>
        </div>
    </div>

    <!-- Tailwind Modal Implementation -->
    <div id="tailwindModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
    $(function() {
        // Select2 initialization
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

        // Tailwind Modal functions
        const modal = document.getElementById('tailwindModal');
        const backdrop = document.getElementById('modalBackdrop');
        const panel = document.getElementById('modalPanel');
        const content = document.getElementById('modalContent');

        function openModal(url) {
            modal.classList.remove('hidden');
            // Animate in
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                panel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
                panel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
            }, 10);

            // Load content
            $("#modalContent").html('<div class="flex justify-center p-8"><i class="fas fa-circle-notch fa-spin text-[#003d9e] text-2xl"></i></div>');
            $("#modalContent").load(url);
        }

        function closeModal() {
            // Animate out
            backdrop.classList.add('opacity-0');
            panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            panel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Close on backdrop click
        if (backdrop) {
            backdrop.addEventListener('click', closeModal);
        }

        // Show detail button
        $(".btnShow").click(function(e) {
            var kode_saldo_awal = $(this).attr("kode_saldo_awal");
            e.preventDefault();
            openModal('/sagudangcabang/' + kode_saldo_awal + '/show');
        });

        // Edit button
        $(".btnEdit").click(function(e) {
            var kode_saldo_awal = $(this).attr("kode_saldo_awal");
            e.preventDefault();
            openModal('/sagudangcabang/' + kode_saldo_awal + '/edit');
        });

        // Expose close function to be called from inside loaded content if needed
        window.closeTailwindModal = closeModal;
    });
</script>
@endpush
