@extends('layouts.app')
@section('titlepage', 'Penjualan')

@section('content')
@section('navigasi')
    <span>Penjualan</span>
@endsection

<style>
    /* Penjualan Page Specific Styles */
    .penjualan-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .penjualan-header {
        background: linear-gradient(135deg, #03204f 0%, #1e3a8a 100%);
        color: #ffffff !important;
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .penjualan-header h4 {
        margin: 0;
        font-weight: 700;
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #ffffff !important;
    }

    .penjualan-header i {
        color: #ffffff !important;
    }

    .penjualan-header .btn {
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

    .penjualan-header .btn:hover {
        background: #ffd700;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(255, 200, 0, 0.3);
    }

    .penjualan-header .btn-group {
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
                    Silahkan Gunakan Icon <i class="ti ti-file-invoice text-danger me-1 ms-1"></i> Untuk Membatalkan
                    Faktur !
                </p>
                <p class="mb-0">
                    Silahkan Gunakan Icon <i class="ti ti-adjustments text-warning me-1 ms-1"></i> Untuk Generate No.
                    Faktur
                </p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>

        <div class="card penjualan-card">
            <div class="penjualan-header">
                <h4>
                    <i class="ti ti-shopping-cart"></i>
                    Data Penjualan
                </h4>
                <div class="btn-group">
                    @can('penjualan.create')
                        <a href="{{ route('penjualan.create') }}" class="btn" id="btnCreate">
                            <i class="ti ti-plus me-2"></i>Input Penjualan
                        </a>
                    @endcan
                    @can('penjualan.cetakfaktur')
                        <a href="#" class="btn" id="btnCetakSuratjalan" style="background: #10b981; color: white;">
                            <i class="ti ti-printer me-2"></i>Cetak Banyak Surat Jalan
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body" style="padding: 20px;">
                <!-- Search Form -->
                <div class="search-card">
                    <form action="{{ route('penjualan.index') }}">
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
                        <!-- Row 2: Cabang (jika ada) -->
                        @hasanyrole($roles_show_cabang)
                            <div class="row g-2 mb-2">
                                <div class="col-12">
                                    <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                                        upperCase="true" selected="{{ Request('kode_cabang_search') }}" select2="select2Kodecabangsearch" />
                                </div>
                            </div>
                        @endhasanyrole
                        <!-- Row 3: Salesman, No. Faktur, Kode Pelanggan, Nama Pelanggan sejajar (full width dibagi 4) -->
                        <div class="row g-2 mb-2">
                            <div class="col-lg-3 col-sm-12 col-md-12">
                                <select name="kode_salesman_search" id="kode_salesman_search" class="form-select select2Kodesalesmansearch">
                                    <option value="">Semua Salesman</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12">
                                <x-input-with-icon label="No. Faktur" value="{{ Request('no_faktur_search') }}" name="no_faktur_search"
                                    icon="ti ti-barcode" />
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12">
                                <x-input-with-icon label="Kode Pelanggan" value="{{ Request('kode_pelanggan_search') }}" name="kode_pelanggan_search"
                                    icon="ti ti-barcode" />
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12">
                                <x-input-with-icon label="Nama Pelanggan" value="{{ Request('nama_pelanggan_search') }}" name="nama_pelanggan_search"
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
                                <th>No. Faktur</th>
                                <th>Tanggal</th>
                                <th>Nama Pelanggan</th>
                                <th>Nama Cabang</th>
                                <th>Salesman</th>
                                <th class="text-end">Total</th>
                                <th class="text-center">JT</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($penjualan as $d)
                                @php
                                    $total_netto =
                                        $d->total_bruto - $d->total_retur - $d->potongan - $d->potongan_istimewa - $d->penyesuaian + $d->ppn;
                                    if ($d->status_batal == '1') {
                                        $color = '#ed9993';
                                        $color_text = '#000';
                                    } elseif ($d->status_batal == '2') {
                                        $color = '#edd993';
                                        $color_text = '#000';
                                    } elseif (substr($d->no_faktur, 3, 2) == 'PR') {
                                        $color = '#0084d14f';
                                        $color_text = '#000';
                                    } else {
                                        $color = '';
                                        $color_text = '';
                                    }
                                @endphp

                                <tr style="background-color: {{ $color }}; color:{{ $color_text }}">
                                    <td>
                                        <span class="code-badge">
                                            <i class="ti ti-file-invoice me-1"></i>{{ $d->no_faktur }}
                                        </span>
                                    </td>
                                    <td>
                                        <i class="ti ti-calendar me-1" style="color: #6c757d;"></i>{{ date('d-m-Y', strtotime($d->tanggal)) }}
                                    </td>
                                    <td>
                                        <div class="table-name">
                                            <i class="ti ti-user me-1" style="color: #6c757d;"></i>{{ $d->nama_pelanggan }}
                                        </div>
                                    </td>
                                    <td>
                                        <i class="ti ti-building me-1" style="color: #6c757d;"></i>{{ strtoupper($d->nama_cabang) }}
                                    </td>
                                    <td>
                                        <i class="ti ti-user-circle me-1" style="color: #6c757d;"></i>{{ strtoupper($d->nama_salesman) }}
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
                                        @if ($d->status_batal == 0)
                                            @if ($d->total_bayar == $total_netto)
                                                <span class="status-badge aktif">Lunas</span>
                                            @elseif ($d->total_bayar > $total_netto)
                                                <span class="status-badge aktif">Lunas</span>
                                            @else
                                                <span class="status-badge nonaktif">Belum Lunas</span>
                                            @endif
                                        @else
                                            <span class="status-badge nonaktif">Batal</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            @can('penjualan.edit')
                                                <a href="/penjualan/{{ \Crypt::encrypt($d->no_faktur) }}/edit" class="action-btn edit" title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                            @endcan
                                            @can('penjualan.show')
                                                <a href="{{ route('penjualan.show', Crypt::encrypt($d->no_faktur)) }}" class="action-btn detail"
                                                    title="Detail">
                                                    <i class="ti ti-file-description"></i>
                                                </a>
                                            @endcan
                                            @can('penjualan.show')
                                                <div class="action-btn" style="background: #e3f2fd; color: #1976d2; position: relative;">
                                                    <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"
                                                        style="color: inherit; text-decoration: none;">
                                                        <i class="ti ti-printer"></i>
                                                    </a>
                                                    <ul class="dropdown-menu" style="z-index: 1050;">
                                                        @can('penjualan.cetakfaktur')
                                                            <li>
                                                                <a class="dropdown-item" target="_blank"
                                                                    href="{{ route('penjualan.cetakfaktur', Crypt::encrypt($d->no_faktur)) }}">
                                                                    <i class="ti ti-printer me-1"></i>Cetak Faktur
                                                                </a>
                                                            </li>
                                                        @endcan
                                                        @can('penjualan.cetaksuratjalan')
                                                            <li>
                                                                <a class="dropdown-item" target="_blank"
                                                                    href="{{ route('penjualan.cetaksuratjalan', [1, Crypt::encrypt($d->no_faktur)]) }}">
                                                                    <i class="ti ti-printer me-1"></i>Cetak Surat Jalan 1
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" target="_blank"
                                                                    href="{{ route('penjualan.cetaksuratjalan', [2, Crypt::encrypt($d->no_faktur)]) }}">
                                                                    <i class="ti ti-printer me-1"></i>Cetak Surat Jalan 2
                                                                </a>
                                                            </li>
                                                        @endcan
                                                    </ul>
                                                </div>
                                            @endcan
                                            @can('penjualan.batalfaktur')
                                                <a href="#" class="action-btn btnBatal" style="background: #ffebee; color: #c62828;"
                                                    no_faktur="{{ Crypt::encrypt($d->no_faktur) }}" title="Batal Faktur">
                                                    <i class="ti ti-file-invoice"></i>
                                                </a>
                                            @endcan
                                            @can('penjualan.delete')
                                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                                    action="/penjualan/{{ Crypt::encrypt($d->no_faktur) }}/delete">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="#" class="action-btn delete delete-confirm" title="Hapus">
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                </form>
                                            @endcan
                                            @can('penjualan.edit')
                                                @if (substr($d->no_faktur, 3, 2) == 'PR')
                                                    <a href="/penjualan/{{ Crypt::encrypt($d->no_faktur) }}/generatefaktur" class="action-btn"
                                                        style="background: #fff3cd; color: #856404;" title="Generate Faktur">
                                                        <i class="ti ti-adjustments"></i>
                                                    </a>
                                                @endif
                                            @endcan
                                            @can('visitpelanggan.create')
                                                @if (!empty($d->kode_visit))
                                                    <div class="action-btn" style="background: #d1e7dd; color: #0f5132;" title="Sudah Visit">
                                                        <i class="ti ti-checks"></i>
                                                    </div>
                                                @else
                                                    <a href="#" no_faktur="{{ Crypt::encrypt($d->no_faktur) }}" class="action-btn btnVisit"
                                                        style="background: #e3f2fd; color: #1976d2;" title="Input Visit">
                                                        <i class="ti ti-gps"></i>
                                                    </a>
                                                @endif
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="ti ti-inbox" style="font-size: 40px; color: #d1d5db;"></i>
                                        <p class="mt-2" style="color: #9ca3af;">Tidak ada data penjualan</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    {{ $penjualan->links() }}
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
        function loading() {
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        };
        $("#btnCetakSuratjalan").click(function(e) {
            e.preventDefault();
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Cetak Surat Jalan");
            $("#loadmodal").load(`/penjualan/filtersuratjalan`);
        });

        $(".btnVisit").click(function(e) {
            e.preventDefault();
            no_faktur = $(this).attr('no_faktur');
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Input Visit Pelanggan");
            $("#loadmodal").load(`/visitpelanggan/${no_faktur}/create`);
        });

        $(".btnBatal").click(function(e) {
            e.preventDefault();
            loading();
            const no_faktur = $(this).attr('no_faktur');
            $("#modal").modal("show");
            $(".modal-title").text("Ubah Ke Faktur Batal");
            $("#loadmodal").load(`/penjualan/${no_faktur}/batalfaktur`);
        });

        const select2Kodecabangsearch = $('.select2Kodecabangsearch');
        if (select2Kodecabangsearch.length) {
            select2Kodecabangsearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2Kodesalesmansearch = $('.select2Kodesalesmansearch');
        if (select2Kodesalesmansearch.length) {
            select2Kodesalesmansearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Salesman',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        function getsalesmanbyCabang() {
            var kode_cabang = $("#kode_cabang_search").val();
            var kode_salesman = "{{ Request('kode_salesman_search') }}";
            //alert(selected);
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang,
                    kode_salesman: kode_salesman
                },
                cache: false,
                success: function(respond) {
                    console.log(respond);
                    $("#kode_salesman_search").html(respond);
                }
            });
        }

        getsalesmanbyCabang();
        $("#kode_cabang_search").change(function(e) {
            getsalesmanbyCabang();
        });
    });
</script>
@endpush
