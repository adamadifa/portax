@extends('layouts.app')
@section('titlepage', 'Cabang')

@section('content')
    <!-- Page Header -->
    <div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Data Cabang</h2>
            <p class="text-slate-500 text-sm">Manage your branch data and configurations.</p>
        </div>
        @can('cabang.create')
            <button id="btncreateCabang" class="bg-[#003d9e] hover:bg-blue-800 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors shadow-sm shadow-blue-200">
                <i class="fas fa-plus"></i>
                <span>Tambah Cabang</span>
            </button>
        @endcan
    </div>

    <!-- Content Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <!-- Search & Filter / Toolbar -->
        <div class="p-3 border-b border-slate-100 bg-slate-50/50">
            <form action="{{ route('cabang.index') }}" class="flex flex-col md:flex-row gap-4 w-full">
                <div class="relative flex-1">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="nama_cabang" value="{{ Request('nama_cabang') }}" 
                        class="w-full pl-10 pr-4 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 placeholder-slate-400 transition-all"
                        placeholder="Cari Nama Cabang...">
                </div>
                <button type="submit" class="bg-[#003d9e] hover:bg-blue-800 text-white px-5 py-2 rounded-lg font-medium text-sm transition-colors shadow-sm shadow-blue-200">
                    Cari
                </button>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                        <th class="px-3 py-2">No</th>
                        <th class="px-3 py-2">Kode</th>
                        <th class="px-3 py-2">Nama Cabang</th>
                        <th class="px-3 py-2">Nama PT</th>
                        <th class="px-3 py-2">Domain</th>
                        <th class="px-3 py-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($cabang as $d)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="px-3 py-2 text-slate-500 text-sm">{{ $loop->iteration + $cabang->firstItem() - 1 }}</td>
                            <td class="px-3 py-2">
                                <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-xs font-mono font-medium border border-slate-200">{{ $d->kode_cabang }}</span>
                            </td>
                            <td class="px-3 py-2 text-slate-800 font-medium text-sm">{{ textUpperCase($d->nama_cabang) }}</td>
                            <td class="px-3 py-2 text-slate-600 text-sm">{{ $d->nama_pt }}</td>
                            <td class="px-3 py-2 text-slate-500 text-sm">{{ $d->domain }}</td>
                            <td class="px-3 py-2 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @can('cabang.edit')
                                        <button class="editCabang w-8 h-8 rounded-lg flex items-center justify-center bg-amber-50 text-amber-600 hover:bg-amber-100 transition-colors"
                                            kode_cabang="{{ Crypt::encrypt($d->kode_cabang) }}" title="Edit">
                                            <i class="fas fa-pencil-alt text-xs"></i>
                                        </button>
                                    @endcan
                                    @can('cabang.delete')
                                        <form method="POST" name="deleteform" class="deleteform inline-block"
                                            action="{{ route('cabang.delete', Crypt::encrypt($d->kode_cabang)) }}">
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
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="far fa-folder-open text-4xl mb-3 text-slate-300"></i>
                                    <p>No data found.</p>
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
                Showing {{ $cabang->firstItem() }} to {{ $cabang->lastItem() }} of {{ $cabang->total() }} entries
            </div>
            <div class="flex gap-1">
                {{ $cabang->links('pagination::tailwind') }} 
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
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all duration-300 ease-out sm:my-8 sm:w-full sm:max-w-lg opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" id="modalPanel">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
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
    </div>

@endsection

@push('myscript')
<script>
    $(function() {
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

        $("#btncreateCabang").click(function(e) {
            e.preventDefault();
            openModal('/cabang/create');
        });

        $(".editCabang").click(function(e) {
            e.preventDefault();
            var kode_cabang = $(this).attr("kode_cabang");
            openModal('/cabang/' + kode_cabang + '/edit');
        });
        
        // Expose close function to be called from inside loaded content if needed
        window.closeTailwindModal = closeModal;
    });
</script>
@endpush
