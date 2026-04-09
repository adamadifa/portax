@extends('layouts.app')
@section('titlepage', 'Roles')

@section('content')
    <!-- Page Header & Breadcrumbs -->
    <div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 leading-tight">Manajemen Roles</h2>
            <p class="text-sm text-slate-500">Kelola peran dan hak akses pengguna dalam aplikasi.</p>
        </div>
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <i class="far fa-folder-open text-[#003d9e]"></i>
            <span class="font-medium">Settings</span>
            <i class="fas fa-chevron-right text-[10px] opacity-50"></i>
            <i class="fas fa-shield-alt"></i>
            <span>Roles</span>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 col-sm-12 col-xs-12">
            <div class="card border-0 shadow-sm rounded-xl overflow-hidden">
                <div class="card-header bg-white border-b border-slate-100 py-4 px-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-[#003d9e]">
                            <i class="fas fa-user-tag text-lg"></i>
                        </div>
                        <h5 class="font-bold text-slate-700 mb-0">Daftar Role</h5>
                    </div>
                    <button class="btn btn-primary px-4 py-2 rounded-lg flex items-center gap-2 shadow-sm transition-all active:scale-95" id="btncreateRole" style="background-color: #003d9e; border-color: #003d9e;">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Role</span>
                    </button>
                </div>
                <div class="card-body p-0">
                    <!-- Search Bar -->
                    <div class="px-6 py-4 bg-slate-50 border-b border-slate-100">
                        <form action="{{ route('roles.index') }}">
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors">
                                    <i class="ti ti-search text-slate-400 group-focus-within:text-[#003d9e]"></i>
                                </div>
                                <div class="flex gap-2">
                                    <input type="text" name="name" value="{{ Request('name') }}" 
                                        class="flex-1 pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all font-medium text-slate-700"
                                        placeholder="Cari nama role...">
                                    <button class="px-4 py-2 bg-slate-700 hover:bg-slate-800 text-white text-sm font-bold rounded-lg transition-colors shadow-sm">
                                        Cari
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 font-semibold text-xs uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-4 text-center" style="width: 80px;">No.</th>
                                    <th class="px-6 py-4">Role Name</th>
                                    <th class="px-6 py-4">Guard</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse ($roles as $d)
                                    <tr class="transition-colors hover:bg-slate-50/50">
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-sm font-bold text-slate-400 leading-none">
                                                {{ $roles->firstItem() + $loop->index }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500">
                                                    <i class="fas fa-shield-alt text-xs"></i>
                                                </div>
                                                <span class="font-bold text-slate-700">{{ ucwords($d->name) }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 text-[11px] font-bold uppercase tracking-tight">
                                                {{ $d->guard_name }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('roles.createrolepermission', Crypt::encrypt($d->id)) }}"
                                                    class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all duration-200 border border-blue-100"
                                                    title="Set Permissions">
                                                    <i class="ti ti-shield-lock text-lg"></i>
                                                </a>
                                                <a href="#" class="editRole w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all duration-200 border border-emerald-100"
                                                    id="{{ $d->id }}" title="Edit Role">
                                                    <i class="ti ti-edit text-lg"></i>
                                                </a>
                                                <form method="POST" name="deleteform" class="deleteform m-0"
                                                    action="{{ route('roles.delete', Crypt::encrypt($d->id)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="delete-confirm w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center hover:bg-rose-600 hover:text-white transition-all duration-200 border border-rose-100">
                                                        <i class="ti ti-trash text-lg"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center gap-2">
                                                <i class="fas fa-folder-open text-3xl text-slate-200"></i>
                                                <span class="text-slate-400 font-medium small">Tidak ada data role ditemukan.</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Pagination Section -->
                @if ($roles->hasPages())
                    <div class="card-footer bg-white border-t border-slate-100 py-4 px-6">
                        {{ $roles->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modals -->
    <x-modal-form id="mdlcreateRole" size="" show="loadcreateRole" title="Tambah Role" />
    <x-modal-form id="mdleditRole" size="" show="loadeditRole" title="Edit Role" />
@endsection

@push('myscript')
<script>
    $(function() {
        $("#btncreateRole").click(function(e) {
            e.preventDefault();
            $('#mdlcreateRole').modal("show");
            $("#loadcreateRole").load('/roles/create');
        });

        $(".editRole").click(function(e) {
            var id = $(this).attr("id");
            e.preventDefault();
            $('#mdleditRole').modal("show");
            $("#loadeditRole").load('/roles/' + id + '/edit');
        });

        // Delete Confirmation
        $('.delete-confirm').click(function(event) {
            var form = $(this).closest("form");
            event.preventDefault();
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Data Role ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
