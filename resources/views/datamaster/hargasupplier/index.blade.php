@extends('layouts.app')
@section('titlepage', 'Harga Supplier')

@section('content')
    <!-- Page Header -->
    <div class="mb-5 flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Title & Subtitle -->
        <div>
            <h2 class="text-2xl font-bold text-slate-800 leading-tight">Harga Supplier</h2>
            <p class="text-sm text-slate-500">Manage supplier pricing for products.</p>
        </div>
        <!-- Actions -->
        <button id="btnCreate" class="bg-[#003d9e] hover:bg-blue-800 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors shadow-sm shadow-blue-200 text-sm font-medium">
            <i class="fas fa-plus"></i>
            <span>Tambah Data</span>
        </button>
    </div>

    <!-- Content Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <!-- Search & Filter / Toolbar -->
        <div class="p-4 border-b border-slate-100 bg-slate-50/50">
            @if (Session::get('success'))
                <div class="alert alert-success d-flex align-items-center mb-3" role="alert">
                    <span class="alert-icon text-success me-2">
                        <i class="ti ti-check"></i>
                    </span>
                    {{ Session::get('success') }}
                </div>
            @endif
            @if (Session::get('error'))
                <div class="alert alert-danger d-flex align-items-center mb-3" role="alert">
                    <span class="alert-icon text-danger me-2">
                        <i class="ti ti-ban"></i>
                    </span>
                    {{ Session::get('error') }}
                </div>
            @endif
            <form action="{{ route('hargasupplier.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <!-- Search Nama Produk -->
                <div class="md:col-span-10 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-slate-400"></i>
                    </div>
                    <input type="text" name="nama_produk" value="{{ Request('nama_produk') }}" 
                        class="w-full pl-10 pr-4 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#003d9e] focus:border-[#003d9e] text-sm text-slate-700 placeholder-slate-400 transition-all"
                        placeholder="Cari Nama Produk...">
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
                        <th class="px-4 py-3 text-center">Kode Produk</th>
                        <th class="px-4 py-3">Nama Produk</th>
                        <th class="px-4 py-3 text-right">Harga</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($hargasupplier as $d)
                        <tr class="hover:bg-slate-50/80 transition-colors group text-sm text-slate-700">
                            <td class="px-4 py-2.5 text-center text-slate-500">{{ $loop->iteration + $hargasupplier->firstItem() - 1 }}</td>
                            <td class="px-4 py-2.5 text-center">
                                <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-xs font-mono font-bold border border-slate-200">{{ $d->kode_produk }}</span>
                            </td>
                            <td class="px-4 py-2.5 font-medium text-slate-800">{{ $d->nama_produk }}</td>
                            <td class="px-4 py-2.5 text-right font-semibold text-blue-800">{{ formatRupiah($d->harga) }}</td>
                            <td class="px-4 py-2.5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button class="btnEdit w-8 h-8 rounded-md flex items-center justify-center bg-amber-50 text-amber-600 hover:bg-amber-100 transition-colors border border-amber-200"
                                        kode_produk="{{ Crypt::encrypt($d->kode_produk) }}" title="Edit">
                                        <i class="fas fa-pencil-alt text-xs"></i>
                                    </button>
                                    <form method="POST" name="deleteform" class="deleteform inline-block"
                                        action="{{ route('hargasupplier.delete', Crypt::encrypt($d->kode_produk)) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-confirm w-8 h-8 rounded-md flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-100 transition-colors border border-red-200" title="Delete">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
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

        <!-- Pagination -->
        <div class="px-4 py-3 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <div class="text-xs text-slate-500">
                Showing {{ $hargasupplier->firstItem() }} to {{ $hargasupplier->lastItem() }} of {{ $hargasupplier->total() }} entries
            </div>
            <div class="flex gap-1">
                {{ $hargasupplier->links('pagination::tailwind') }} 
            </div>
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
            const content = document.getElementById('modalContent');
            
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
                openModal("{{ route('hargasupplier.create') }}");
            });

            $(".btnEdit").click(function(e) {
                e.preventDefault();
                var kode_produk = $(this).attr("kode_produk");
                openModal("/hargasupplier/" + kode_produk + "/edit");
            });
        });
    </script>
@endpush
