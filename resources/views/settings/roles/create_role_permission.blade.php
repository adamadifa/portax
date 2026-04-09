@extends('layouts.app')
@section('titlepage', 'Set Role Permissions')

<style>
    .permission-group-item.active {
        background-color: #f1f5f9;
        border-right: 4px solid #003d9e;
        color: #003d9e !important;
    }

    .permission-group-item.active i {
        color: #003d9e !important;
    }

    .permission-group-item:hover:not(.active) {
        background-color: #f8fafc;
    }

    .custom-switch .form-check-input {
        width: 2.25rem;
        height: 1.25rem;
        cursor: pointer;
    }

    .custom-switch .form-check-input:checked {
        background-color: #003d9e;
        border-color: #003d9e;
    }

    #permissionSearch::placeholder {
        color: #94a3b8;
        font-style: italic;
    }

    .sticky-footer {
        position: sticky;
        bottom: 0;
        z-index: 10;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(8px);
        border-top: 1px solid #e2e8f0;
    }

    .group-indicator {
        font-size: 10px;
        padding: 1px 6px;
        border-radius: 10px;
        background: #e2e8f0;
        color: #64748b;
        font-weight: 700;
    }

    .permission-group-item.active .group-indicator {
        background: #003d9e;
        color: white;
    }
</style>

@section('content')
    <!-- Page Header & Breadcrumbs -->
    <div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('roles.index') }}" class="text-sm font-bold text-[#003d9e] hover:underline flex items-center gap-1">
                    <i class="fas fa-arrow-left text-[10px]"></i>
                    Kembali
                </a>
            </div>
            <h2 class="text-2xl font-bold text-slate-800 leading-tight">Set Permissions: {{ ucwords($role->name) }}</h2>
            <p class="text-sm text-slate-500">Konfigurasi hak akses mendetail untuk peran ini.</p>
        </div>
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <i class="far fa-folder-open text-[#003d9e]"></i>
            <span class="font-medium">Settings</span>
            <i class="fas fa-chevron-right text-[10px] opacity-50"></i>
            <i class="fas fa-shield-alt"></i>
            <span>Roles</span>
            <i class="fas fa-chevron-right text-[10px] opacity-50"></i>
            <span>Permission Setup</span>
        </div>
    </div>

    <form action="{{ route('roles.storerolepermission', Crypt::encrypt($role->id)) }}" method="POST" id="permissionForm">
        @csrf
        <div class="row g-4">
            <!-- Sidebar Groups -->
            <div class="col-lg-4 col-md-5">
                <div class="card border-0 shadow-sm rounded-xl overflow-hidden sticky top-24">
                    <div class="card-header bg-white border-b border-slate-100 py-4 px-6 flex items-center justify-between">
                        <h5 class="font-bold text-slate-700 mb-0 flex items-center gap-2">
                            <i class="fas fa-layer-group text-slate-400"></i>
                            Grup Hak Akses
                        </h5>
                    </div>
                    <div class="p-2 max-h-[calc(100vh-250px)] overflow-y-auto" id="groupList">
                        @foreach ($permissions as $key => $d)
                            @php
                                $list_p = explode(',', $d->permissions);
                                $total = count($list_p);
                                $active = 0;
                                foreach ($list_p as $p) {
                                    $p_name = explode('-', $p)[1];
                                    if (in_array($p_name, $rolepermissions)) {
                                        $active++;
                                    }
                                }
                            @endphp
                            <button type="button" 
                                class="permission-group-item w-full flex items-center justify-between px-4 py-3 rounded-lg transition-all duration-200 text-left mb-1 group"
                                data-group-id="group-{{ $d->id_permission_group }}"
                                onclick="switchGroup('group-{{ $d->id_permission_group }}')">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-slate-50 group-hover:bg-white flex items-center justify-center transition-colors">
                                        <i class="fas fa-folder text-slate-300 group-hover:text-slate-400"></i>
                                    </div>
                                    <span class="text-sm font-bold text-slate-600 group-hover:text-slate-800">{{ $d->group_name }}</span>
                                </div>
                                <span class="group-indicator" data-active="{{ $active }}" data-total="{{ $total }}">
                                    <span class="active-count">{{ $active }}</span>/{{ $total }}
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Permission Content -->
            <div class="col-lg-8 col-md-7">
                <div class="card border-0 shadow-sm rounded-xl overflow-hidden min-h-[500px] flex flex-col">
                    <div class="card-header bg-white border-b border-slate-100 py-3 px-6">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <h5 class="font-bold text-slate-700 mb-0" id="activeGroupName">Pilih Grup Permission</h5>
                            </div>
                            <!-- Quick Search & Select All -->
                            <div class="flex items-center gap-2">
                                <div class="relative flex-1 md:w-48">
                                    <i class="fas fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                    <input type="text" id="permissionSearch" class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:outline-none focus:border-[#003d9e] focus:bg-white transition-all" placeholder="Cari hak akses...">
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary py-1.5 px-3 rounded-lg text-[11px] font-bold" onclick="toggleAllInGroup()">
                                    Toggle All
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0 flex-grow" id="permissionContainer">
                        @foreach ($permissions as $d)
                            <div class="group-pane d-none px-6 py-4" id="group-{{ $d->id_permission_group }}">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @php
                                        $list_permissions = explode(',', $d->permissions);
                                    @endphp
                                    @foreach ($list_permissions as $p)
                                        @php
                                            $permission = explode('-', $p);
                                            $permission_id = $permission[0];
                                            $permission_name = $permission[1];
                                            $cek = in_array($permission_name, $rolepermissions);
                                        @endphp
                                        <div class="permission-item p-3 border border-slate-100 rounded-xl hover:bg-slate-50 transition-colors flex items-center justify-between group-hover:border-blue-100" data-name="{{ $permission_name }}">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center text-[#003d9e] border border-slate-100">
                                                    <i class="fas fa-key text-[10px]"></i>
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-bold text-slate-700">{{ $permission_name }}</span>
                                                    <span class="text-[10px] text-slate-400 font-medium">Permit ID: #{{ $permission_id }}</span>
                                                </div>
                                            </div>
                                            <div class="form-check form-switch custom-switch">
                                                <input class="form-check-input permission-checkbox" type="checkbox" name="permission[]" 
                                                    value="{{ $permission_name }}" id="p-{{ $permission_id }}" 
                                                    {{ $cek > 0 ? 'checked' : '' }}
                                                    onchange="updateIndicator('group-{{ $d->id_permission_group }}')">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <!-- Empty State -->
                        <div id="emptyState" class="flex flex-col items-center justify-center py-20 text-slate-400">
                            <i class="fas fa-mouse-pointer text-4xl mb-4 opacity-20"></i>
                            <p class="font-bold mb-1">Pilih Grup Hak Akses</p>
                            <p class="text-xs">Klik pada menu di sisi kiri untuk mengatur detail hak akses.</p>
                        </div>
                    </div>

                    <!-- Sticky Footer -->
                    <div class="sticky-footer py-4 px-6 mt-auto">
                        <div class="flex items-center justify-between gap-4">
                            <div class="hidden md:block">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Status: Berubah Otomatis</span>
                            </div>
                            <button type="submit" class="btn btn-primary px-6 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-blue-900/20 active:scale-95 transition-all" style="background-color: #003d9e; border-color: #003d9e;">
                                <i class="fas fa-save opacity-70"></i>
                                <span>Simpan Perubahan Hak Akses</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('myscript')
<script>
    let activeGroupId = null;

    function switchGroup(groupId) {
        // Toggle Active Class in Sidebar
        $('.permission-group-item').removeClass('active');
        $(`[data-group-id="${groupId}"]`).addClass('active');

        // Toggle Content
        $('.group-pane').addClass('d-none');
        $(`#${groupId}`).removeClass('d-none');

        // Update Title
        const groupName = $(`[data-group-id="${groupId}"]`).find('span.text-sm').text();
        $('#activeGroupName').text(groupName);

        // Hide empty state
        $('#emptyState').addClass('d-none');

        activeGroupId = groupId;
        
        // Reset Search
        $('#permissionSearch').val('').trigger('input');
    }

    function toggleAllInGroup() {
        if (!activeGroupId) return;
        
        const groupPane = $(`#${activeGroupId}`);
        const checkboxes = groupPane.find('.permission-checkbox');
        const allChecked = checkboxes.length === checkboxes.filter(':checked').length;
        
        checkboxes.prop('checked', !allChecked);
        updateIndicator(activeGroupId);
    }

    function updateIndicator(groupId) {
        const groupPane = $(`#${groupId}`);
        const total = groupPane.find('.permission-checkbox').length;
        const active = groupPane.find('.permission-checkbox:checked').length;
        
        const indicator = $(`[data-group-id="${groupId}"]`).find('.group-indicator');
        indicator.find('.active-count').text(active);
        
        if (active > 0) {
            indicator.removeClass('bg-slate-200 text-slate-600').addClass('bg-blue-100 text-blue-700');
        } else {
            indicator.removeClass('bg-blue-100 text-blue-700').addClass('bg-slate-200 text-slate-600');
        }

        if (active === total) {
             indicator.removeClass('bg-blue-100 text-blue-700').addClass('bg-[#003d9e] text-white');
        }
    }

    $(function() {
        // Initial setup for indicators (styling existing counts)
        $('.group-indicator').each(function() {
            const active = parseInt($(this).find('.active-count').text());
            const total = parseInt($(this).text().split('/')[1]);
            const groupId = $(this).closest('.permission-group-item').data('group-id');
            
            if (active > 0) {
                $(this).removeClass('bg-slate-200 text-slate-600').addClass('bg-blue-100 text-blue-700');
            }
            if (active === total && total > 0) {
                $(this).removeClass('bg-blue-100 text-blue-700').addClass('bg-[#003d9e] text-white');
            }
        });

        // Permission Search
        $('#permissionSearch').on('input', function() {
            const query = $(this).val().toLowerCase();
            if (!activeGroupId) return;

            $(`#${activeGroupId} .permission-item`).each(function() {
                const name = $(this).data('name').toLowerCase();
                if (name.includes(query)) {
                    $(this).removeClass('d-none');
                } else {
                    $(this).addClass('d-none');
                }
            });
        });

        // Activate first group by default
        const firstGroup = $('.permission-group-item').first();
        if (firstGroup.length) {
            firstGroup.click();
        }
    });
</script>
@endpush
