@extends('layouts.app')
@section('titlepage', 'Salesman')

@section('content')
@section('navigasi')
    <span>Salesman</span>
@endsection

<style>
    /* Salesman Page Specific Styles */
    .salesman-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .salesman-header {
        background: linear-gradient(135deg, #03204f 0%, #1e3a8a 100%);
        color: #ffffff !important;
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .salesman-header h4 {
        margin: 0;
        font-weight: 700;
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #ffffff !important;
    }

    .salesman-header i {
        color: #ffffff !important;
    }

    .salesman-header .btn {
        background: #ffc800;
        color: #1a1a1a;
        border: none;
        border-radius: 8px;
        padding: 8px 16px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s;
    }

    .salesman-header .btn:hover {
        background: #ffd700;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(255, 200, 0, 0.3);
    }
</style>

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card salesman-card">
            <div class="salesman-header">
                <h4>
                    <i class="ti ti-users"></i>
                    Data Salesman
                </h4>
                @can('salesman.create')
                    <a href="#" class="btn" id="btncreateSalesman">
                        <i class="ti ti-plus me-2"></i> Tambah Salesman
                    </a>
                @endcan
            </div>
            <div class="card-body" style="padding: 20px;">
                <!-- Search Form -->
                <div class="search-card">
                    <form action="{{ route('salesman.index') }}">
                        <div class="row g-2">
                            <div class="col-12">
                                <div class="input-group-search">
                                    <input type="text" 
                                           class="form-control" 
                                           name="nama_salesman" 
                                           value="{{ Request('nama_salesman') }}"
                                           placeholder="Cari nama salesman...">
                                    <button type="submit" class="btn-search">
                                        <i class="ti ti-search"></i>
                                    </button>
                                </div>
                            </div>
                            @hasanyrole($roles_show_cabang)
                                <div class="col-lg-6 col-sm-12 col-md-12">
                                    <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang"
                                        textShow="nama_cabang" selected="{{ Request('kode_cabang') }}" />
                                </div>
                            @endhasanyrole
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table modern-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode</th>
                                <th>Nama Salesman</th>
                                <th>Alamat</th>
                                <th>No. HP</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Komisi</th>
                                <th>Cabang</th>
                                <th>Marker</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($salesman as $d)
                                <tr>
                                    <td style="color: #6c757d; font-weight: 500;">
                                        {{ $loop->iteration + $salesman->firstItem() - 1 }}
                                    </td>
                                    <td>
                                        <span class="code-badge">
                                            <i class="ti ti-barcode me-1"></i>{{ $d->kode_salesman }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="table-name">
                                            <i class="ti ti-user me-1" style="color: #6c757d;"></i>{{ textCamelCase($d->nama_salesman) ?: '-' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="table-address" title="{{ textCamelCase($d->alamat_salesman) }}">
                                            <i class="ti ti-map-pin me-1" style="color: #6c757d;"></i>{{ textCamelCase($d->alamat_salesman) ?: '-' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="table-phone">
                                            <i class="ti ti-phone me-1"></i>
                                            <span>{{ $d->no_hp_salesman ?: '-' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="category-badge">
                                            <i class="ti ti-tag me-1"></i>{{ $d->nama_kategori_salesman }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($d->status_aktif_salesman == 1)
                                            <span class="status-badge aktif">
                                                <i class="ti ti-check" style="font-size: 12px;"></i>Aktif
                                            </span>
                                        @else
                                            <span class="status-badge nonaktif">
                                                <i class="ti ti-x" style="font-size: 12px;"></i>Non Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($d->status_komisi_salesman == 1)
                                            <span class="komisi-badge aktif">
                                                <i class="ti ti-check" style="font-size: 12px;"></i>Aktif
                                            </span>
                                        @else
                                            <span class="komisi-badge nonaktif">
                                                <i class="ti ti-x" style="font-size: 12px;"></i>Non Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td style="font-weight: 500; color: #495057;">
                                        <i class="ti ti-building me-1" style="color: #6c757d;"></i>{{ $d->kode_cabang }}
                                    </td>
                                    <td>
                                        @if (!empty($d->marker))
                                            <img src="{{ getdocMarker($d->marker) }}" alt="Marker"
                                                class="avatar-img">
                                        @else
                                            <div class="avatar-placeholder">
                                                <i class="ti ti-user"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            @can('salesman.edit')
                                                <a href="#" class="action-btn edit editSalesman"
                                                    kode_salesman="{{ Crypt::encrypt($d->kode_salesman) }}"
                                                    title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                            @endcan
                                            @can('salesman.delete')
                                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                                    action="{{ route('salesman.delete', Crypt::encrypt($d->kode_salesman)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="#" class="action-btn delete delete-confirm" title="Hapus">
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center py-4">
                                        <i class="ti ti-inbox" style="font-size: 40px; color: #d1d5db;"></i>
                                        <p class="text-muted mt-2 mb-0" style="font-size: 14px;">Tidak ada data salesman</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    {{ $salesman->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreateSalesman" size="" show="loadcreateSalesman" title="Tambah Salesman" />
<x-modal-form id="mdleditSalesman" size="" show="loadeditSalesman" title="Edit Salesman" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {
        $("#btncreateSalesman").click(function(e) {
            $('#mdlcreateSalesman').modal("show");
            $("#loadcreateSalesman").load('/salesman/create');
        });

        $(".editSalesman").click(function(e) {
            var kode_salesman = $(this).attr("kode_salesman");
            e.preventDefault();
            $('#mdleditSalesman').modal("show");
            $("#loadeditSalesman").load('/salesman/' + kode_salesman + '/edit');
        });
    });
</script>
@endpush
