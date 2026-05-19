@extends('layouts.app')
@section('titlepage', 'Saldo Awal Buku Besar')

@section('content')
    <!-- Page Header -->
    <div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Saldo Awal Buku Besar</h2>
            <p class="text-slate-500 text-sm">Kelola saldo awal buku besar perusahaan per cabang.</p>
        </div>
        @can('saldoawalbukubesar.create')
            <a href="{{ route('saldoawalbukubesar.create') }}" class="bg-[#003d9e] hover:bg-blue-800 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors shadow-sm shadow-blue-200">
                <i class="fas fa-plus"></i>
                <span>Buat Saldo Awal</span>
            </a>
        @endcan
    </div>

    <!-- Content Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <!-- Search & Filter Toolbar -->
        <div class="p-4 border-b border-slate-100 bg-slate-50/50">
            <form action="{{ route('saldoawalbukubesar.index') }}" class="flex flex-wrap items-center gap-3 w-full">
                <!-- Cabang Select -->
                <div class="w-full md:w-56">
                    <select name="kode_cabang" id="kode_cabang" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm transition-all select-styled">
                        <option value="">Cabang</option>
                        @foreach ($cabang as $d)
                            <option {{ Request('kode_cabang') == $d->kode_cabang ? 'selected' : '' }} value="{{ $d->kode_cabang }}">
                                {{ $d->nama_cabang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Bulan Select -->
                <div class="w-full md:w-44">
                    <select name="bulan" id="bulan" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm transition-all select-styled">
                        <option value="">Bulan</option>
                        @foreach ($list_bulan as $d)
                            <option {{ Request('bulan') == $d['kode_bulan'] ? 'selected' : '' }} value="{{ $d['kode_bulan'] }}">
                                {{ $d['nama_bulan'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tahun Select -->
                <div class="w-full md:w-36">
                    <select name="tahun" id="tahun" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm transition-all select-styled">
                        <option value="">Tahun</option>
                        @for ($t = $start_year; $t <= date('Y'); $t++)
                            <option
                                @if (!empty(Request('tahun'))) {{ Request('tahun') == $t ? 'selected' : '' }}
                                @else {{ date('Y') == $t ? 'selected' : '' }} @endif
                                value="{{ $t }}">{{ $t }}
                            </option>
                        @endfor
                    </select>
                </div>

                <!-- Action Button -->
                <button type="submit" class="bg-[#003d9e] hover:bg-blue-800 text-white px-5 py-2 rounded-lg font-medium text-sm transition-colors shadow-sm shadow-blue-200 flex items-center gap-2">
                    <i class="fas fa-search"></i>
                    Cari
                </button>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Kode Saldo Awal</th>
                        <th class="px-4 py-3">Bulan</th>
                        <th class="px-4 py-3">Tahun</th>
                        <th class="px-4 py-3">Cabang</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($saldoawalbukubesar as $d)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="px-4 py-3 text-slate-500 text-sm">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3">
                                <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-xs font-mono font-medium border border-slate-200">{{ $d->kode_saldo_awal }}</span>
                            </td>
                            <td class="px-4 py-3 text-slate-800 font-medium text-sm">{{ $nama_bulan[$d->bulan] }}</td>
                            <td class="px-4 py-3 text-slate-600 text-sm">{{ $d->tahun }}</td>
                            <td class="px-4 py-3 text-slate-600 text-sm">
                                <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                    {{ $d->nama_cabang }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @can('saldoawalbukubesar.show')
                                        <button class="btnShow w-8 h-8 rounded-lg flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors"
                                            kode_saldo_awal="{{ Crypt::encrypt($d->kode_saldo_awal) }}" title="Detail">
                                            <i class="fas fa-file-alt text-xs"></i>
                                        </button>
                                    @endcan
                                    @can('saldoawalbukubesar.delete')
                                        <form method="POST" name="deleteform" class="deleteform inline-block"
                                            action="{{ route('saldoawalbukubesar.delete', Crypt::encrypt($d->kode_saldo_awal)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="delete-confirm w-8 h-8 rounded-lg flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-100 transition-colors" title="Hapus">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="far fa-folder-open text-4xl mb-3 text-slate-300"></i>
                                    <p>Belum ada data saldo awal.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tailwind Modal Implementation -->
    <div id="tailwindModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Background backdrop -->
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300 ease-out opacity-0" id="modalBackdrop"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <!-- Modal panel -->
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all duration-300 ease-out sm:my-8 sm:w-full sm:max-w-xl opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" id="modalPanel">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div id="modalContent">
                            <div class="flex justify-center p-8">
                                <i class="fas fa-circle-notch fa-spin text-[#003d9e] text-2xl"></i>
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

        // Tailwind Modal controllers
        const modal = document.getElementById('tailwindModal');
        const backdrop = document.getElementById('modalBackdrop');
        const panel = document.getElementById('modalPanel');
        
        function openModal(url) {
            modal.classList.remove('hidden');
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                panel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
                panel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
            }, 10);

            $("#modalContent").html('<div class="flex justify-center p-8"><i class="fas fa-circle-notch fa-spin text-[#003d9e] text-2xl"></i></div>');
            $("#modalContent").load(url);
        }

        function closeModal() {
            backdrop.classList.add('opacity-0');
            panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            panel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        if (backdrop) {
            backdrop.addEventListener('click', closeModal);
        }

        $(".btnShow").click(function(e) {
            e.preventDefault();
            var kode_saldo_awal = $(this).attr("kode_saldo_awal");
            openModal('/saldoawalbukubesar/' + kode_saldo_awal + '/show');
        });
    });
</script>
@endpush
