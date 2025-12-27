@extends('layouts.app')
@section('titlepage', 'Detail Pembelian Marketing')

@section('content')
@section('navigasi')
    <span class="text-muted">Pembelian/</span> Detail
@endsection

<style>
    .detail-header {
        background: linear-gradient(135deg, #03204f 0%, #1e3a8a 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px 12px 0 0;
    }

    .detail-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }

    .info-table td {
        padding: 0.75rem;
        vertical-align: middle;
    }

    .info-table th {
        width: 200px;
        font-weight: 600;
        color: #495057;
    }

    .grandtotal-display {
        text-align: center;
        padding: 2rem;
        background: #f8f9fa;
        border-radius: 12px;
    }

    .grandtotal-display h1 {
        font-size: 3rem;
        font-weight: 700;
        color: #03204f;
        margin: 0;
    }

    .detail-table thead {
        background: #03204f;
        color: white !important;
    }

    .detail-table thead th {
        padding: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.875rem;
        color: white !important;
    }

    .detail-table tbody td {
        padding: 0.75rem;
        vertical-align: middle;
    }

    .detail-table tfoot {
        background: #f8f9fa;
        font-weight: 600;
    }

    .detail-table tfoot td {
        padding: 0.75rem;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .status-aktif {
        background: #d1f2eb;
        color: #0c5460;
    }

    .status-nonaktif {
        background: #f8d7da;
        color: #721c24;
    }

    .btn-action {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-back {
        background: #6c757d;
        color: white;
    }

    .btn-back:hover {
        background: #5a6268;
        color: white;
        transform: translateY(-2px);
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card detail-card mb-4">
            <div class="detail-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-2">
                            <i class="ti ti-shopping-bag me-2"></i>Detail Pembelian Marketing
                        </h4>
                        <p class="mb-0 opacity-75">No. Bukti: {{ $pembelian->no_bukti }}</p>
                    </div>
                    <div>
                        <a href="{{ route('pembelianmarketing.index') }}" class="btn btn-action btn-back">
                            <i class="ti ti-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <!-- Informasi Pembelian -->
                    <div class="col-lg-6 col-md-12 mb-4">
                        <h5 class="mb-3">
                            <i class="ti ti-info-circle me-2"></i>Informasi Pembelian
                        </h5>
                        <table class="table info-table">
                            <tr>
                                <th>No. Bukti</th>
                                <td><strong>{{ $pembelian->no_bukti }}</strong></td>
                            </tr>
                            <tr>
                                <th>Tanggal</th>
                                <td>{{ date('d-m-Y', strtotime($pembelian->tanggal)) }}</td>
                            </tr>
                            <tr>
                                <th>Supplier</th>
                                <td>
                                    <i class="ti ti-building-store me-2"></i>{{ $pembelian->nama_supplier }}
                                </td>
                            </tr>
                            <tr>
                                <th>Kode Supplier</th>
                                <td>{{ $pembelian->kode_supplier }}</td>
                            </tr>
                            <tr>
                                <th>Kode Akun</th>
                                <td>{{ $pembelian->kode_akun }}</td>
                            </tr>
                            <tr>
                                <th>Jenis Transaksi</th>
                                <td>
                                    @if ($pembelian->jenis_transaksi == 'T')
                                        <span class="status-badge status-aktif">TUNAI</span>
                                    @else
                                        <span class="status-badge status-nonaktif">KREDIT</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Jenis Bayar</th>
                                <td>
                                    {{ $jenis_bayar[$pembelian->jenis_bayar] ?? $pembelian->jenis_bayar }}
                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if ($pembelian->status == '0')
                                        <span class="status-badge status-aktif">Aktif</span>
                                    @else
                                        <span class="status-badge status-nonaktif">Nonaktif</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Grand Total -->
                    <div class="col-lg-6 col-md-12 mb-4">
                        <div class="grandtotal-display">
                            <i class="ti ti-shopping-cart" style="font-size: 5rem; color: #03204f; opacity: 0.2;"></i>
                            <p class="text-muted mb-2">GRAND TOTAL</p>
                            <h1>{{ formatAngka($total_bruto) }}</h1>
                        </div>
                    </div>
                </div>

                <!-- Detail Produk -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h5 class="mb-3">
                            <i class="ti ti-list me-2"></i>Detail Produk
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-bordered detail-table">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Produk</th>
                                        <th class="text-end">Jumlah</th>
                                        <th class="text-end">Harga / Dus</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $subtotal = 0;
                                    @endphp
                                    @foreach ($detail as $d)
                                        @php
                                            $subtotal += $d->subtotal;
                                        @endphp
                                        <tr>
                                            <td>{{ $d->kode_produk }}</td>
                                            <td>{{ $d->nama_produk }}</td>
                                            <td class="text-end">{{ formatAngka($d->jumlah) }}</td>
                                            <td class="text-end">{{ formatAngka($d->harga_dus) }}</td>
                                            <td class="text-end">{{ formatAngka($d->subtotal) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-end"><strong>GRAND TOTAL</strong></td>
                                        <td class="text-end"><strong>{{ formatAngka($subtotal) }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Histori Pembayaran -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">
                                <i class="ti ti-cash me-2"></i>Histori Pembayaran
                            </h5>
                            @can('pembayaranpembelianmarketing.create')
                                @if ($pembelian->status == '0')
                                    <a href="#" class="btn btn-primary btn-sm" id="btnCreateBayar">
                                        <i class="ti ti-plus me-1"></i>Input Pembayaran
                                    </a>
                                @endif
                            @endcan
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered detail-table">
                                <thead>
                                    <tr>
                                        <th>No. Bukti</th>
                                        <th>Tanggal</th>
                                        <th>Jenis Bayar</th>
                                        <th class="text-end">Jumlah</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total_bayar = 0;
                                    @endphp
                                    @foreach ($historibayar as $d)
                                        @php
                                            $total_bayar += $d->jumlah;
                                        @endphp
                                        <tr>
                                            <td>{{ $d->no_bukti }}</td>
                                            <td>{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                                            <td>{{ $jenis_bayar[$d->jenis_bayar] ?? $d->jenis_bayar }}</td>
                                            <td class="text-end">{{ formatAngka($d->jumlah) }}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    @can('pembayaranpembelianmarketing.edit')
                                                        <a href="#" class="btnEditBayar" no_bukti="{{ Crypt::encrypt($d->no_bukti) }}">
                                                            <i class="ti ti-edit text-success"></i>
                                                        </a>
                                                    @endcan
                                                    @can('pembayaranpembelianmarketing.delete')
                                                        <form method="POST" name="deleteform" class="deleteform d-inline"
                                                            action="{{ route('pembayaranpembelianmarketing.delete', Crypt::encrypt($d->no_bukti)) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#" class="delete-confirm">
                                                                <i class="ti ti-trash text-danger"></i>
                                                            </a>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if ($historibayar->count() == 0)
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada data pembayaran</td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>TOTAL BAYAR</strong></td>
                                        <td class="text-end"><strong>{{ formatAngka($total_bayar) }}</strong></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        @php
                                            $sisa_bayar = $total_bruto - $total_bayar;
                                            if ($sisa_bayar == 0) {
                                                $color = 'success';
                                                $ket = 'LUNAS';
                                            } elseif ($sisa_bayar < 0) {
                                                $color = 'info';
                                                $ket = 'LEBIH BAYAR';
                                            } else {
                                                $color = 'warning';
                                                $ket = 'BELUM LUNAS';
                                            }
                                        @endphp
                                        <td colspan="3" class="text-end"><strong>SISA BAYAR</strong></td>
                                        <td class="text-end" id="sisabayar"><strong>{{ formatAngka($sisa_bayar) }}</strong></td>
                                        <td class="bg-{{ $color }} text-center text-white"><strong>{{ $ket }}</strong></td>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel"></h5>
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
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
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

        $(".btnEditBayar").click(function(e) {
            e.preventDefault();
            loading();
            const no_bukti = $(this).attr('no_bukti');
            $("#modal").modal("show");
            $(".modal-title").text("Edit Pembayaran");
            $("#loadmodal").load(`/pembayaranpembelianmarketing/${no_bukti}/edit`);
        });
    });
</script>
@endpush
