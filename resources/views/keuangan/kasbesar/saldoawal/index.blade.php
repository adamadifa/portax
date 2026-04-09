@extends('layouts.app')
@section('titlepage', 'Saldo Awal Kas Besar')

@section('content')
    <!-- Page Header -->
    <div class="mb-5 flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Title & Subtitle -->
        <div>
            <h2 class="text-2xl font-bold text-slate-800 leading-tight">Saldo Awal Kas Besar</h2>
            <p class="text-sm text-slate-500">Kelola saldo awal kas besar per cabang.</p>
        </div>
        <!-- Actions -->
        @can('sakasbesar.create')
            <button id="btnCreate" class="bg-[#003d9e] hover:bg-blue-800 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors shadow-sm shadow-blue-200 text-sm font-medium">
                <i class="fas fa-plus"></i>
                <span>Buat Saldo Awal</span>
            </button>
        @endcan
    </div>

    <!-- Content Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden max-w-5xl">
        <!-- Search & Filter / Toolbar -->
        <div class="p-4 border-b border-slate-100 bg-slate-50/50">
            <form action="{{ route('sakasbesar.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <!-- Bulan -->
                <div class="md:col-span-5">
                    <select name="bulan" id="bulan" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#003d9e] focus:border-[#003d9e] text-sm text-slate-700 transition-all">
                        <option value="">Semua Bulan</option>
                        @foreach ($list_bulan as $d)
                            <option {{ Request('bulan') == $d['kode_bulan'] ? 'selected' : '' }}
                                value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tahun -->
                <div class="md:col-span-5">
                    <select name="tahun" id="tahun" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#003d9e] focus:border-[#003d9e] text-sm text-slate-700 transition-all">
                        <option value="">Semua Tahun</option>
                        @for ($t = $start_year; $t <= date('Y'); $t++)
                            <option
                                @if (!empty(Request('tahun'))) {{ Request('tahun') == $t ? 'selected' : '' }}
                                @else
                                {{ date('Y') == $t ? 'selected' : '' }} @endif
                                value="{{ $t }}">{{ $t }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="md:col-span-2">
                    <button type="submit" class="w-full bg-[#003d9e] hover:bg-blue-800 text-white py-2 rounded-lg font-medium text-sm transition-colors shadow-sm shadow-blue-200 flex items-center justify-center gap-2">
                        <i class="fas fa-filter"></i> Cari
                    </button>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                        <th class="px-4 py-3 text-center w-10">No</th>
                        <th class="px-4 py-3">Kode</th>
                        <th class="px-4 py-3">Cabang</th>
                        <th class="px-4 py-3 text-center">Periode</th>
                        <th class="px-4 py-3 text-right">Jumlah Saldo</th>
                        <th class="px-4 py-3 text-center">Tanggal</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($saldo_awal as $d)
                        <tr class="hover:bg-slate-50/80 transition-colors group text-sm text-slate-700">
                            <td class="px-4 py-2.5 text-center text-slate-500">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2.5">
                                <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-xs font-mono font-bold border border-slate-200">{{ $d->kode_saldo_awal }}</span>
                            </td>
                            <td class="px-4 py-2.5 font-medium text-slate-800">{{ textUpperCase($d->nama_cabang) }}</td>
                            <td class="px-4 py-2.5 text-center text-slate-600">{{ $nama_bulan[$d->bulan] }} {{ $d->tahun }}</td>
                            <td class="px-4 py-2.5 text-right font-semibold text-blue-800">{{ formatRupiah($d->jumlah_saldo) }}</td>
                            <td class="px-4 py-2.5 text-center text-slate-500">{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                            <td class="px-4 py-2.5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @can('sakasbesar.delete')
                                        <form method="POST" name="deleteform" class="deleteform inline-block"
                                            action="{{ route('sakasbesar.delete', Crypt::encrypt($d->kode_saldo_awal)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="delete-confirm w-8 h-8 rounded-md flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-100 transition-colors border border-red-200" title="Delete">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-inbox text-3xl mb-2 text-slate-300"></i>
                                    <p class="text-sm">Tidak ada data ditemukan.</p>
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
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300 ease-out opacity-0" id="modalBackdrop"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
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
            // --- Modal Logic ---
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
            window.closeTailwindModal = closeModal;

            // --- Triggers ---
            $("#btnCreate").click(function(e) {
                e.preventDefault();
                openModal("{{ route('sakasbesar.create') }}");
            });

            // Handling delete confirmation with style
            $('.delete-confirm').click(function(event) {
                var form = $(this).closest("form");
                event.preventDefault();
                Swal.fire({
                    title: "Hapus Data?",
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
