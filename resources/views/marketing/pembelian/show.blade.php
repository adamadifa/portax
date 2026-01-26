@extends('layouts.app')
@section('titlepage', 'Detail Pembelian Marketing')

@section('content')

<style>
    .card-detail {
        border: none;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border-radius: 12px;
    }

    .section-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 1rem;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 0.5rem;
    }

    .info-group {
        margin-bottom: 1rem;
    }

    .info-label {
        font-size: 0.825rem;
        color: #6b7280;
        margin-bottom: 0.25rem;
        font-weight: 500;
    }

    .info-value {
        font-size: 0.95rem;
        color: #111827;
        font-weight: 600;
    }

    .table-compact thead th {
        background-color: #f9fafb;
        color: #374151;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .table-compact tbody td {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        color: #4b5563;
        border-bottom: 1px solid #f3f4f6;
    }

    .table-compact tfoot td {
        padding: 0.75rem 1rem;
        background-color: #f9fafb;
        font-weight: 700;
        color: #1f2937;
    }

    .grand-total-card {
        background: linear-gradient(145deg, #1e3a8a, #1effaa00);
        background-color: #1e3a8a;
        color: white;
        padding: 1.5rem;
        border-radius: 12px;
        position: relative;
        overflow: hidden;
    }

    .grand-total-card::after {
        content: "";
        position: absolute;
        top: -20px;
        right: -20px;
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-badge.success {
        background-color: #d1fae5;
        color: #065f46;
    }

    .status-badge.danger {
        background-color: #fee2e2;
        color: #991b1b;
    }
     .status-badge.warning {
        background-color: #fef3c7;
         color: #92400e;
    }
     .status-badge.info {
        background-color: #dbeafe;
         color: #1e40af;
    }


    .btn-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .btn-icon:hover {
        background-color: #f3f4f6;
    }
</style>

<div class="row">
    <div class="col-12">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1 fw-bold text-dark">Detail Pembelian</h4>
                <p class="text-muted mb-0 small"><i class="ti ti-hash me-1"></i>{{ $pembelian->no_bukti }}</p>
            </div>
            <div>
                 <a href="{{ route('pembelianmarketing.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm rounded-pill px-3">
                    <i class="ti ti-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Column: Information -->
            <div class="col-lg-8">
                <!-- Main Info Card -->
                <div class="card card-detail mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                            <h5 class="section-title mb-0 border-0 p-0 text-primary"><i class="ti ti-file-invoice me-2"></i>Informasi Transaksi</h5>
                            <span class="status-badge {{ $pembelian->status == '1' ? 'success' : 'danger' }}">
                                <i class="ti ti-circle-check me-1"></i> {{ $pembelian->status == '1' ? 'Lunas' : 'Belum Lunas' }}
                            </span>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-group">
                                    <div class="info-label">Tanggal</div>
                                    <div class="info-value"><i class="ti ti-calendar me-1 text-muted"></i>{{ date('d-m-Y', strtotime($pembelian->tanggal)) }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-group">
                                    <div class="info-label">Supplier</div>
                                    <div class="info-value"><i class="ti ti-building me-1 text-muted"></i>{{ $pembelian->nama_supplier }}</div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="info-group">
                                    <div class="info-label">Jenis Transaksi</div>
                                    <div class="info-value">
                                         @if ($pembelian->jenis_transaksi == 'T')
                                            <span class="text-success fw-bold">TUNAI</span>
                                        @else
                                            <span class="text-danger fw-bold">KREDIT</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                 <div class="info-group">
                                    <div class="info-label">Jenis Bayar</div>
                                    <div class="info-value">{{ $jenis_bayar[$pembelian->jenis_bayar] ?? $pembelian->jenis_bayar }}</div>
                                </div>
                            </div>
                             <div class="col-md-6 mt-3">
                                 <div class="info-group">
                                    <div class="info-label">Kode Akun</div>
                                    <div class="info-value font-monospace text-muted">{{ $pembelian->kode_akun }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="card card-detail mb-4">
                    <div class="card-body p-4">
                         <h5 class="section-title text-primary"><i class="ti ti-box me-2"></i>Produk</h5>
                        <div class="table-responsive">
                            <table class="table table-compact table-hover mb-0 w-100">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Produk</th>
                                        <th class="text-end">Jumlah</th>
                                        <th class="text-end">Harga/Dus</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $subtotal = 0; @endphp
                                    @foreach ($detail as $d)
                                        @php $subtotal += $d->subtotal; @endphp
                                        <tr>
                                            <td class="font-monospace text-muted small">{{ $d->kode_produk }}</td>
                                            <td>{{ $d->nama_produk }}</td>
                                            <td class="text-end fw-bold">{{ formatAngka($d->jumlah) }}</td>
                                            <td class="text-end text-muted">{{ formatAngka($d->harga_dus) }}</td>
                                            <td class="text-end fw-bold text-dark">{{ formatAngka($d->subtotal) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-end text-uppercase text-muted small">Total Pembelian</td>
                                        <td class="text-end fs-6 text-primary">{{ formatAngka($subtotal) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Summary & Payments -->
            <div class="col-lg-4">
                 <!-- Grand Total Card -->
                <div class="grand-total-card mb-4 shadow-sm">
                    <div class="position-relative z-1 text-white">
                        <small class="opacity-75 text-uppercase fw-bold letter-spacing-1 d-block mb-1 text-white">Grand Total</small>
                        <h2 class="mb-0 fw-bold text-white">Rp {{ formatAngka($total_bruto) }}</h2>
                    </div>
                </div>

                <!-- Payment History -->
                <div class="card card-detail">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                            <h5 class="section-title mb-0 border-0 p-0 text-primary"><i class="ti ti-history me-2"></i>Pembayaran</h5>
                             @can('pembayaranpembelianmarketing.create')
                                @if ($pembelian->status == '0' && ($total_bruto - $historibayar->sum('jumlah')) > 0 )
                                    <a href="#" class="btn btn-primary btn-sm rounded-pill px-3 py-1" id="btnCreateBayar" style="font-size: 0.75rem;">
                                        <i class="ti ti-plus me-1"></i>Baru
                                    </a>
                                @endif
                            @endcan
                        </div>

                         <div class="table-responsive">
                            <table class="table table-compact table-sm table-borderless mb-0">
                                <thead>
                                    <tr>
                                        <th>Info</th>
                                        <th class="text-end">Jumlah</th>
                                        <th class="text-end">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     @php $total_bayar = 0; @endphp
                                    @foreach ($historibayar as $d)
                                        @php $total_bayar += $d->jumlah; @endphp
                                        <tr>
                                            <td>
                                                <div class="text-dark fw-bold small">{{ $d->no_bukti }}</div>
                                                <div class="text-muted smaller" style="font-size: 0.7rem;">{{ date('d/m/y', strtotime($d->tanggal)) }} • {{ $jenis_bayar[$d->jenis_bayar] ?? $d->jenis_bayar }}</div>
                                            </td>
                                            <td class="text-end fw-bold align-middle">{{ formatAngka($d->jumlah) }}</td>
                                            <td class="text-end align-middle">
                                                 <div class="dropdown">
                                                    <a href="#" class="btn btn-icon btn-sm text-muted" data-bs-toggle="dropdown">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                                         @can('pembayaranpembelianmarketing.edit')
                                                        <li>
                                                            <a class="dropdown-item btnEditBayar" href="#" no_bukti="{{ Crypt::encrypt($d->no_bukti) }}">
                                                                <i class="ti ti-edit me-2 text-warning"></i> Edit
                                                            </a>
                                                        </li>
                                                        @endcan
                                                         @can('pembayaranpembelianmarketing.delete')
                                                        <li>
                                                             <form method="POST" action="{{ route('pembayaranpembelianmarketing.delete', Crypt::encrypt($d->no_bukti)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item delete-confirm text-danger">
                                                                     <i class="ti ti-trash me-2"></i> Hapus
                                                                </button>
                                                            </form>
                                                        </li>
                                                        @endcan
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if ($historibayar->count() == 0)
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-3 small fst-italic">Belum ada pembayaran</td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot class="border-top">
                                     <tr>
                                        <td class="text-muted small">Total Bayar</td>
                                        <td colspan="2" class="text-end fw-bold text-success">{{ formatAngka($total_bayar) }}</td>
                                    </tr>
                                    @php
                                        $sisa_bayar = $total_bruto - $total_bayar;
                                        $status_color = $sisa_bayar <= 0 ? 'text-success' : 'text-danger';
                                        $status_bg = $sisa_bayar <= 0 ? 'success' : 'warning';
                                        $status_text = $sisa_bayar <= 0 ? 'LUNAS' : 'BELUM LUNAS';
                                        if($sisa_bayar < 0) $status_text = 'LEBIH BAYAR';
                                    @endphp
                                     <tr>
                                        <td class="text-muted small">Sisa Bayar</td>
                                        <td colspan="2" class="text-end fw-bold {{ $status_color }}">
                                            {{ formatAngka($sisa_bayar) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="pt-3 text-center">
                                            <span class="status-badge {{ $status_bg }} w-100 justify-content-center py-2">{{ $status_text }}</span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadmodal">
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(document).ready(function() {
        function loading() {
            $("#loadmodal").html(`<div class="d-flex justify-content-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>`);
        }

        $("#btnCreateBayar").click(function(e) {
            e.preventDefault();
            loading();
            const no_bukti = "{{ Crypt::encrypt($pembelian->no_bukti) }}";
            $("#modal").modal("show");
            $(".modal-title").text("Input Pembayaran");
            $("#loadmodal").load(`/pembayaranpembelianmarketing/${no_bukti}/create`);
        });

        $(document).on('click', '.btnEditBayar', function(e) {
             e.preventDefault();
            loading();
            const no_bukti = $(this).attr('no_bukti');
            $("#modal").modal("show");
            $(".modal-title").text("Edit Pembayaran");
            $("#loadmodal").load(`/pembayaranpembelianmarketing/${no_bukti}/edit`);
        });
        
         $(".delete-confirm").click(function(e) {
            var form = $(this).closest("form");
            e.preventDefault();
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        });
    });
</script>
@endpush
