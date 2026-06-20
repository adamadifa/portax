@extends('layouts.app')
@section('titlepage', 'Jurnal Umum')

@section('content')
@section('navigasi')
    <span>Jurnal Umum</span>
@endsection

<!-- Page Header -->
<div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Jurnal Umum</h2>
        <p class="text-slate-500 text-sm">Kelola data pencatatan jurnal umum perusahaan.</p>
    </div>
    @can('jurnalumum.create')
        <a href="#" id="btnCreate" class="bg-[#003d9e] hover:bg-blue-800 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors shadow-sm shadow-blue-200 text-sm font-semibold">
            <i class="ti ti-plus"></i>
            <span>Input Jurnal Umum</span>
        </a>
    @endcan
</div>

<!-- Content Card -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <!-- Search & Filter Toolbar -->
    <div class="p-4 border-b border-slate-100 bg-slate-50/50">
        <form action="{{ route('jurnalumum.index') }}" id="formSearch" class="flex flex-wrap items-center gap-3 w-full">
            <!-- Dari Tanggal -->
            <div class="w-full md:w-44 relative">
                <input type="text" name="dari" id="dari" value="{{ Request('dari') }}" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] transition-all flatpickr-date" placeholder="Dari Tanggal">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i class="ti ti-calendar text-base"></i>
                </div>
            </div>

            <!-- Sampai Tanggal -->
            <div class="w-full md:w-44 relative">
                <input type="text" name="sampai" id="sampai" value="{{ Request('sampai') }}" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] transition-all flatpickr-date" placeholder="Sampai Tanggal">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i class="ti ti-calendar text-base"></i>
                </div>
            </div>

            @if (auth()->user()->kode_cabang == 'PST' || empty(auth()->user()->kode_cabang))
            <!-- Cabang Select -->
            <div class="w-full md:w-56">
                <select name="kode_cabang_search" id="kode_cabang_search" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm transition-all select-styled">
                    <option value="">Semua Cabang</option>
                    @foreach ($cabang as $d)
                        <option {{ Request('kode_cabang_search') == $d->kode_cabang ? 'selected' : '' }} value="{{ $d->kode_cabang }}">
                            {{ textUpperCase($d->nama_cabang) }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            <!-- Action Button -->
            <button type="submit" class="bg-[#003d9e] hover:bg-blue-800 text-white px-5 py-2 rounded-lg font-medium text-sm transition-colors shadow-sm shadow-blue-200 flex items-center gap-2">
                <i class="ti ti-search text-base"></i>
                Cari Data
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                    <th class="px-4 py-3" style="width: 10%">Kode JU</th>
                    <th class="px-4 py-3" style="width: 10%">Tanggal</th>
                    <th class="px-4 py-3" style="width: 25%">Keterangan</th>
                    <th class="px-4 py-3" style="width: 20%">Akun</th>

                    <th class="px-4 py-3 text-right">Debet</th>
                    <th class="px-4 py-3 text-right">Kredit</th>
                    <th class="px-4 py-3">Dept</th>
                    <th class="px-4 py-3 text-right">#</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($jurnalumum as $d)
                    @php
                        $debet = $d->debet_kredit == 'D' ? $d->jumlah : 0;
                        $kredit = $d->debet_kredit == 'K' ? $d->jumlah : 0;
                        $row_class = !empty($d->kode_cr) ? 'bg-blue-50/50 hover:bg-blue-50 transition-colors' : 'hover:bg-slate-50 transition-colors';
                    @endphp
                    <tr class="{{ $row_class }}">
                        <td class="px-4 py-3">
                            <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-xs font-mono font-medium border border-slate-200">{{ $d->kode_ju }}</span>
                        </td>
                        <td class="px-4 py-3 text-slate-600 text-sm">{{ formatIndo($d->tanggal) }}</td>
                        <td class="px-4 py-3 text-slate-800 text-sm font-medium">{{ $d->keterangan }}</td>
                        <td class="px-4 py-3 text-slate-600 text-sm font-medium">
                            @if (!empty($d->kode_akun_portax))
                                {{ $d->kode_akun_portax }} - {{ $d->nama_akun }}
                            @else
                                <div class="flex flex-col gap-1">
                                    <span class="text-rose-600 bg-rose-50 px-2 py-0.5 rounded border border-rose-100 text-xs font-semibold inline-flex items-center gap-1 w-max">
                                        <i class="ti ti-alert-circle text-xs"></i>
                                        Belum Dihubungkan ({{ $d->kode_akun }})
                                    </span>
                                    <span class="text-slate-400 text-xs">{{ $d->nama_akun_portal ?? 'Nama Akun Portal Tidak Ditemukan' }}</span>
                                </div>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-right text-emerald-600 font-semibold text-sm">
                            {{ $debet > 0 ? formatAngkaDesimal($debet) : '-' }}
                        </td>
                        <td class="px-4 py-3 text-right text-red-600 font-semibold text-sm">
                            {{ $kredit > 0 ? formatAngkaDesimal($kredit) : '-' }}
                        </td>
                        <td class="px-4 py-3 text-slate-500 text-xs font-bold">{{ $d->kode_dept }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @can('jurnalumum.edit')
                                    <button class="btnEdit w-8 h-8 rounded-lg flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors"
                                        kode_ju="{{ Crypt::encrypt($d->kode_ju) }}" title="Edit">
                                        <i class="ti ti-edit text-sm"></i>
                                    </button>
                                @endcan
                                @can('jurnalumum.delete')
                                    <form method="POST" name="deleteform" class="deleteform inline-block"
                                        action="{{ route('jurnalumum.delete', Crypt::encrypt($d->kode_ju)) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-confirm w-8 h-8 rounded-lg flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-100 transition-colors" title="Hapus">
                                            <i class="ti ti-trash text-sm"></i>
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center">
                                <i class="ti ti-folder-off text-4xl mb-3 text-slate-300"></i>
                                <p>Belum ada data jurnal umum.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<x-modal-form id="modal" show="loadmodal" title="" />
@endsection

@push('myscript')
<script>
    $(function() {


        // Toggle placeholder styling for select elements
        $('.select-styled').each(function() {
            const $this = $(this);
            const checkVal = () => {
                if ($this.val() === "") {
                    $this.addClass('text-slate-400').removeClass('text-slate-700');
                } else {
                    $this.addClass('text-slate-700').removeClass('text-slate-400');
                }
            };
            $this.on('change', checkVal);
            checkVal();
        });

        function loading() {
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        };

        $("#btnCreate").click(function(e) {
            e.preventDefault();
            loading();
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Input Jurnal Umum");
            $("#modal").find("#loadmodal").load(`/jurnalumum/create`);
            $("#modal").find(".modal-dialog").addClass("modal-xl");
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            loading();
            const kode_ju = $(this).attr('kode_ju');
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Edit Jurnal Umum");
            $("#modal").find("#loadmodal").load(`/jurnalumum/${kode_ju}/edit`);
            $("#modal").find(".modal-dialog").removeClass("modal-xl");
        });
    });
</script>
@endpush
