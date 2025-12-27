@extends('layouts.app')
@section('titlepage', 'Pembelian Marketing')

@section('content')
@section('navigasi')
    <span>Pembelian Marketing</span>
@endsection

<style>
    /* Pembelian Page Specific Styles */
    .pembelian-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .pembelian-header {
        background: linear-gradient(135deg, #03204f 0%, #1e3a8a 100%);
        color: #ffffff !important;
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .pembelian-header h4 {
        margin: 0;
        font-weight: 700;
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #ffffff !important;
    }

    .pembelian-header i {
        color: #ffffff !important;
    }

    .pembelian-header .btn {
        background: #ffc800;
        color: #1a1a1a;
        border: none;
        border-radius: 8px;
        padding: 8px 16px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .pembelian-header .btn:hover {
        background: #ffd700;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(255, 200, 0, 0.3);
    }

    .pembelian-header .btn-group {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .info-alert {
        border-radius: 10px;
        border: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
    }
</style>

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <!-- Info Alert -->
        <div class="alert alert-info alert-dismissible d-flex align-items-baseline info-alert" role="alert">
            <span class="alert-icon alert-icon-lg text-info me-2">
                <i class="ti ti-info-circle ti-sm"></i>
            </span>
            <div class="d-flex flex-column ps-1">
                <h5 class="alert-heading mb-2">Informasi</h5>
                <p class="mb-0">
                    Halaman untuk mengelola data pembelian marketing
                </p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>

        <div class="card pembelian-card">
            <div class="pembelian-header">
                <h4>
                    <i class="ti ti-shopping-bag"></i>
                    Data Pembelian Marketing
                </h4>
                <div class="btn-group">
                    @can('pembelianmarketing.create')
                        <a href="{{ route('pembelianmarketing.create') }}" class="btn" id="btnCreate">
                            <i class="ti ti-plus me-2"></i>Input Pembelian
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body" style="padding: 20px;">
                <!-- Search Form -->
                <div class="search-card">
                    <form action="{{ route('pembelianmarketing.index') }}">
                        <!-- Row 1: Dari dan Sampai (Full Width dibagi 2) -->
                        <div class="row g-2 mb-1">
                            <div class="col-lg-6 col-sm-12 col-md-12">
                                <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                    datepicker="flatpickr-date" />
                            </div>
                            <div class="col-lg-6 col-sm-12 col-md-12">
                                <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                    datepicker="flatpickr-date" />
                            </div>
                        </div>
                        <!-- Row 2: No. Bukti, Kode Supplier, Nama Supplier sejajar (full width dibagi 3) -->
                        <div class="row g-2 mb-2">
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <x-input-with-icon label="No. Bukti" value="{{ Request('no_bukti_search') }}" name="no_bukti_search"
                                    icon="ti ti-barcode" />
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <x-input-with-icon label="Kode Supplier" value="{{ Request('kode_supplier_search') }}" name="kode_supplier_search"
                                    icon="ti ti-barcode" />
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <x-input-with-icon label="Nama Supplier" value="{{ Request('nama_supplier_search') }}" name="nama_supplier_search"
                                    icon="ti ti-users" />
                            </div>
                        </div>
                        <!-- Row 4: Button Cari -->
                        <div class="row g-2">
                            <div class="col-12">
                                <button type="submit" class="btn w-100"
                                    style="background: #03204f; color: white; border: none; border-radius: 8px; padding: 10px 20px;">
                                    <i class="ti ti-search me-1"></i>Cari Data
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
                                <th>No. Bukti</th>
                                <th>Tanggal</th>
                                <th>Nama Supplier</th>
                                <th class="text-end">Total</th>
                                <th class="text-center">JT</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pembelian as $d)
                                @php
                                    $total_netto = $d->total_bruto ?? 0;
                                @endphp

                                <tr>
                                    <td>
                                        <span class="code-badge">
                                            <i class="ti ti-file-invoice me-1"></i>{{ $d->no_bukti }}
                                        </span>
                                    </td>
                                    <td>
                                        <i class="ti ti-calendar me-1" style="color: #6c757d;"></i>{{ date('d-m-Y', strtotime($d->tanggal)) }}
                                    </td>
                                    <td>
                                        <div class="table-name">
                                            <i class="ti ti-building-store me-1" style="color: #6c757d;"></i>{{ $d->nama_supplier }}
                                        </div>
                                    </td>
                                    <td class="text-end" style="font-weight: 600; color: #03204f;">
                                        {{ formatAngka($total_netto) }}
                                    </td>
                                    <td class="text-center">
                                        @if ($d->jenis_transaksi == 'T')
                                            <span class="status-badge aktif">{{ $d->jenis_transaksi }}</span>
                                        @elseif($d->jenis_transaksi == 'K')
                                            <span class="status-badge nonaktif">{{ $d->jenis_transaksi }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($d->status == '0')
                                            <span class="status-badge aktif">Aktif</span>
                                        @else
                                            <span class="status-badge nonaktif">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            @can('pembelianmarketing.edit')
                                                <a href="/pembelianmarketing/{{ \Crypt::encrypt($d->no_bukti) }}/edit" class="action-btn edit"
                                                    title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                            @endcan
                                            @can('pembelianmarketing.show')
                                                <a href="{{ route('pembelianmarketing.show', Crypt::encrypt($d->no_bukti)) }}"
                                                    class="action-btn detail" title="Detail">
                                                    <i class="ti ti-file-description"></i>
                                                </a>
                                            @endcan
                                            @can('pembelianmarketing.delete')
                                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                                    action="{{ route('pembelianmarketing.delete', Crypt::encrypt($d->no_bukti)) }}">
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
                                    <td colspan="7" class="text-center py-4">
                                        <i class="ti ti-inbox" style="font-size: 40px; color: #d1d5db;"></i>
                                        <p class="mt-2" style="color: #9ca3af;">Tidak ada data pembelian</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    {{ $pembelian->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" size="" show="loadmodal" title="" />
@endsection
@push('myscript')
<script>
    $(function() {
        // Scripts jika diperlukan
    });
</script>
@endpush
