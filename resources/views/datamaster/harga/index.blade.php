@extends('layouts.app')
@section('titlepage', 'Harga')

@section('content')
@section('navigasi')
    <span>Harga</span>
@endsection

<style>
    /* Harga Page Specific Styles */
    .harga-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .harga-header {
        background: linear-gradient(135deg, #03204f 0%, #1e3a8a 100%);
        color: #ffffff !important;
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .harga-header h4 {
        margin: 0;
        font-weight: 700;
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #ffffff !important;
    }

    .harga-header i {
        color: #ffffff !important;
    }

    .harga-header .btn {
        background: #ffc800;
        color: #1a1a1a;
        border: none;
        border-radius: 8px;
        padding: 8px 16px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s;
    }

    .harga-header .btn:hover {
        background: #ffd700;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(255, 200, 0, 0.3);
    }
</style>

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card harga-card">
            <div class="harga-header">
                <h4>
                    <i class="ti ti-tag"></i>
                    Data Harga
                </h4>
                @can('harga.create')
                    <a href="#" class="btn" id="btncreateHarga">
                        <i class="ti ti-plus me-2"></i> Tambah Harga
                    </a>
                @endcan
            </div>
            <div class="card-body" style="padding: 20px;">
                <!-- Search Form -->
                <div class="search-card">
                    <form action="{{ route('harga.index') }}">
                        <div class="row g-2">
                            <div class="col-lg-4 col-sm-12 col-md-12">
                                <input type="text" 
                                       class="form-control" 
                                       name="nama_produk" 
                                       value="{{ Request('nama_produk') }}"
                                       placeholder="Cari nama produk...">
                            </div>
                            @hasanyrole($roles_show_cabang)
                                <div class="col-lg-3 col-sm-12 col-md-12">
                                    <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang"
                                        textShow="nama_cabang" selected="{{ Request('kode_cabang') }}" />
                                </div>
                            @endhasanyrole
                            <div class="col-lg-3 col-sm-12 col-md-12">
                                <x-select label="Kategori" name="kode_kategori_salesman" :data="$kategorisalesman"
                                    key="kode_kategori_salesman" textShow="nama_kategori_salesman"
                                    selected="{{ Request('kode_kategori_salesman') }}" />
                            </div>
                            <div class="col-lg-auto col-sm-12 col-md-12 d-flex align-items-end">
                                <button type="submit" class="btn" style="background: #03204f; color: white; border: none; border-radius: 8px; padding: 10px 20px; white-space: nowrap;">
                                    <i class="ti ti-search me-1"></i>Cari
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table modern-table">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Kode</th>
                                <th>Nama Produk</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-end">Harga/Dus</th>
                                <th class="text-end">Harga/Pack</th>
                                <th class="text-end">Harga/Pcs</th>
                                <th class="text-center">Kategori</th>
                                <th class="text-center">Promo</th>
                                <th class="text-center">PPN</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Cabang</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($harga as $d)
                                <tr>
                                    <td class="text-center" style="color: #6c757d; font-weight: 500;">
                                        {{ $loop->iteration + $harga->firstItem() - 1 }}
                                    </td>
                                    <td class="text-center">
                                        <span class="code-badge">
                                            <i class="ti ti-barcode me-1"></i>{{ $d->kode_harga }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="table-name">
                                            <i class="ti ti-package me-1" style="color: #6c757d;"></i>{{ $d->nama_produk }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <i class="ti ti-ruler me-1" style="color: #6c757d;"></i>{{ $d->satuan }}
                                    </td>
                                    <td class="text-end" style="font-weight: 600; color: #03204f;">
                                        {{ formatRupiah($d->harga_dus) }}
                                    </td>
                                    <td class="text-end" style="font-weight: 600; color: #03204f;">
                                        {{ formatRupiah($d->harga_pack) }}
                                    </td>
                                    <td class="text-end" style="font-weight: 600; color: #03204f;">
                                        {{ formatRupiah($d->harga_pcs) }}
                                    </td>
                                    <td class="text-center">
                                        <span class="category-badge">
                                            <i class="ti ti-tag me-1"></i>{{ $d->kode_kategori_salesman }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if ($d->status_promo == 1)
                                            <span class="status-badge aktif">
                                                <i class="ti ti-check" style="font-size: 12px;"></i>Ya
                                            </span>
                                        @else
                                            <span class="status-badge nonaktif">
                                                <i class="ti ti-x" style="font-size: 12px;"></i>Tidak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($d->status_ppn == 'IN')
                                            <span class="status-badge aktif">INCLUDE</span>
                                        @elseif($d->status_ppn == 'EX')
                                            <span class="status-badge nonaktif">EXCLUDE</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($d->status_aktif_harga == 1)
                                            <span class="status-badge aktif">Aktif</span>
                                        @else
                                            <span class="status-badge nonaktif">Non Aktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <i class="ti ti-building me-1" style="color: #6c757d;"></i>{{ $d->kode_cabang }}
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            @can('harga.edit')
                                                <a href="#" class="action-btn edit editHarga"
                                                    kode_harga="{{ Crypt::encrypt($d->kode_harga) }}"
                                                    title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                            @endcan
                                            @can('harga.delete')
                                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                                    action="{{ route('harga.delete', Crypt::encrypt($d->kode_harga)) }}">
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
                                    <td colspan="13" class="text-center py-4">
                                        <i class="ti ti-inbox" style="font-size: 40px; color: #d1d5db;"></i>
                                        <p class="mt-2" style="color: #9ca3af;">Tidak ada data harga</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    {{ $harga->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreateHarga" size="modal-lg" show="loadcreateHarga" title="Tambah Harga" />
<x-modal-form id="mdleditHarga" size="" show="loadeditHarga" title="Edit Harga" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {
        $("#btncreateHarga").click(function(e) {
            $('#mdlcreateHarga').modal("show");
            $("#loadcreateHarga").load('/harga/create');
        });

        $(".editHarga").click(function(e) {
            var kode_harga = $(this).attr("kode_harga");
            e.preventDefault();
            $('#mdleditHarga').modal("show");
            $("#loadeditHarga").load('/harga/' + kode_harga + '/edit');
        });
    });
</script>
@endpush
