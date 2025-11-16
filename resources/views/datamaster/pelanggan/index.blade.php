@extends('layouts.app')
@section('titlepage', 'Pelanggan')

@section('content')
@section('navigasi')
    <span>Pelanggan</span>
@endsection

<style>
    /* Pelanggan Page Specific Styles */
    .pelanggan-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .pelanggan-header {
        background: linear-gradient(135deg, #03204f 0%, #1e3a8a 100%);
        color: #ffffff !important;
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .pelanggan-header h4 {
        margin: 0;
        font-weight: 700;
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #ffffff !important;
    }

    .pelanggan-header i {
        color: #ffffff !important;
    }

    .pelanggan-header .btn {
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

    .pelanggan-header .btn:hover {
        background: #ffd700;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(255, 200, 0, 0.3);
    }

    .pelanggan-header .btn-group {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .stat-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }
</style>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-4 mb-4 col-lg-4 col-12">
        <div class="card stat-card">
            <div class="d-flex align-items-end row">
                <div class="col-7">
                    <div class="card-body text-nowrap">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-database me-2"></i>Database Pelanggan
                        </h5>
                        <p class="mb-2 mt-2" style="color: #6c757d; font-size: 13px;">Jumlah Database Pelanggan</p>
                        <h4 class="text-primary mb-1" style="font-weight: 700;">{{ formatRupiah($jmlpelanggan) }}</h4>
                    </div>
                </div>
                <div class="col-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img src="{{ asset('assets/img/illustrations/card-advance-sale.png') }}" height="140" alt="view sales">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 mb-4 col-lg-4 col-12">
        <div class="card stat-card">
            <div class="d-flex align-items-end row">
                <div class="col-7">
                    <div class="card-body text-nowrap">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-user-check me-2"></i>Pelanggan Aktif
                        </h5>
                        <p class="mb-2 mt-2" style="color: #6c757d; font-size: 13px;">Jumlah Database Pelanggan Aktif</p>
                        <h4 class="text-success mb-1" style="font-weight: 700;">{{ formatRupiah($jmlpelangganaktif) }}</h4>
                    </div>
                </div>
                <div class="col-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img src="{{ asset('assets/img/illustrations/girl-with-laptop.png') }}" height="140" alt="view sales">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 mb-4 col-lg-4 col-12">
        <div class="card stat-card">
            <div class="d-flex align-items-end row">
                <div class="col-7">
                    <div class="card-body text-nowrap">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-user-x me-2"></i>Pelanggan Non Aktif
                        </h5>
                        <p class="mb-2 mt-2" style="color: #6c757d; font-size: 13px;">Jumlah Database Pelanggan Non Aktif</p>
                        <h4 class="text-danger mb-1" style="font-weight: 700;">{{ formatRupiah($jmlpelanggannonaktif) }}</h4>
                    </div>
                </div>
                <div class="col-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img src="{{ asset('assets/img/illustrations/inactive-customer.png') }}" height="140" alt="view sales">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card pelanggan-card">
            <div class="pelanggan-header">
                <h4>
                    <i class="ti ti-users"></i>
                    Data Pelanggan
                </h4>
                <div class="btn-group">
                    @can('pelanggan.create')
                        <a href="#" class="btn" id="btncreatePelanggan">
                            <i class="ti ti-plus me-2"></i>Tambah Pelanggan
                        </a>
                    @endcan
                    @can('pelanggan.show')
                        <form action="/pelanggan/export" method="GET" id="formCetak" target="_blank" class="d-inline">
                            <input type="hidden" name="dari" id='dari_cetak' value="{{ Request('dari') }}" />
                            <input type="hidden" name="sampai" id="sampai_cetak" value="{{ Request('sampai') }}" />
                            <input type="hidden" name="kode_cabang" id="kode_cabang_cetak" value="{{ Request('kode_cabang') }}" />
                            <input type="hidden" name="kode_salesman" id="kode_salesman_cetak" value="{{ Request('kode_salesman') }}" />
                            <input type="hidden" name="status" id="status_cetak" value="{{ Request('status') }}" />
                            <button type="submit" class="btn" style="background: #03204f; color: white;">
                                <i class="ti ti-printer me-1"></i>Cetak
                            </button>
                            <button type="submit" name="exportButton" class="btn" style="background: #10b981; color: white;">
                                <i class="ti ti-download me-1"></i>Export Excel
                            </button>
                            <a href="#" class="btn" id="btnNonaktif" style="background: #ef4444; color: white;">
                                <i class="ti ti-user-x me-1"></i>Nonaktifkan
                            </a>
                        </form>
                    @endcan
                </div>
            </div>
            <div class="card-body" style="padding: 20px;">
                <!-- Search Form -->
                <div class="search-card">
                    <form action="{{ route('pelanggan.index') }}">
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
                        <!-- Row 2: Status, Salesman, Kode Pelanggan, Nama Pelanggan sejajar (full width dibagi 4) -->
                        <div class="row g-2 mb-2">
                            <div class="col-lg-3 col-sm-12 col-md-12">
                                <select name="status" id="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="aktif" {{ Request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ Request('status') == 'nonaktif' ? 'selected' : '' }}>Non Aktif</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-sm-12 col-md-12">
                                <select name="kode_salesman" id="kode_salesman" class="form-select">
                                    <option value="">Semua Salesman</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-sm-12 col-md-12">
                                <x-input-with-icon label="Kode Pelanggan" value="{{ Request('kode_pelanggan') }}" name="kode_pelanggan"
                                    icon="ti ti-barcode" />
                            </div>
                            <div class="col-lg-3 col-sm-12 col-md-12">
                                <x-input-with-icon label="Nama Pelanggan" value="{{ Request('nama_pelanggan') }}" name="nama_pelanggan"
                                    icon="ti ti-user" />
                            </div>
                        </div>
                        <!-- Row 3: Cabang (jika ada) dan Button -->
                        <div class="row g-2">
                            @hasanyrole($roles_show_cabang)
                                <div class="col-lg-3 col-sm-12 col-md-12">
                                    <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                                        selected="{{ Request('kode_cabang') }}" />
                                </div>
                            @endhasanyrole
                            <div class="col-12">
                                <button type="submit" class="btn w-100"
                                    style="background: #03204f; color: white; border: none; border-radius: 8px; padding: 10px 20px;">
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
                                <th>Kode</th>
                                <th>Nama Pelanggan</th>
                                <th>Wilayah/Rute</th>
                                <th class="text-end">Limit</th>
                                <th class="text-center">Foto</th>
                                <th>Salesman</th>
                                <th class="text-center">Cabang</th>
                                <th class="text-center">Tgl Register</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pelanggan as $d)
                                <tr>
                                    <td class="text-center" style="color: #6c757d; font-weight: 500;">
                                        {{ $loop->iteration + $pelanggan->firstItem() - 1 }}
                                    </td>
                                    <td>
                                        <span class="code-badge">
                                            <i class="ti ti-barcode me-1"></i>{{ $d->kode_pelanggan }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="table-name">
                                            <i class="ti ti-user me-1" style="color: #6c757d;"></i>{{ textCamelCase($d->nama_pelanggan) }}
                                        </div>
                                    </td>
                                    <td>
                                        <i class="ti ti-map-pin me-1" style="color: #6c757d;"></i>{{ textCamelCase($d->nama_wilayah) }}
                                    </td>
                                    <td class="text-end">
                                        @if (empty($d->limit_pelanggan))
                                            <i class="ti ti-clipboard-x text-danger"></i>
                                        @else
                                            <span style="font-weight: 600; color: #03204f;">{{ formatRupiah($d->limit_pelanggan) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if (!empty($d->foto))
                                            @if (Storage::disk('public')->exists('/pelanggan/' . $d->foto))
                                                <img src="{{ getfotoPelanggan($d->foto) }}" alt="" class="avatar-img">
                                            @else
                                                <div class="avatar-placeholder">
                                                    <i class="ti ti-user"></i>
                                                </div>
                                            @endif
                                        @else
                                            <div class="avatar-placeholder">
                                                <i class="ti ti-user"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <i class="ti ti-user-circle me-1" style="color: #6c757d;"></i>{{ textCamelCase($d->nama_salesman) }}
                                    </td>
                                    <td class="text-center">
                                        <i class="ti ti-building me-1" style="color: #6c757d;"></i>{{ $d->kode_cabang }}
                                    </td>
                                    <td class="text-center" style="color: #6c757d;">
                                        <i class="ti ti-calendar me-1"></i>{{ date('d-m-Y', strtotime($d->tanggal_register)) }}
                                    </td>
                                    <td class="text-center">
                                        @if ($d->status_aktif_pelanggan == 1)
                                            <span class="status-badge aktif">Aktif</span>
                                        @else
                                            <span class="status-badge nonaktif">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            @can('pelanggan.edit')
                                                <a href="#" class="action-btn edit editPelanggan"
                                                    kode_pelanggan="{{ Crypt::encrypt($d->kode_pelanggan) }}" title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                            @endcan
                                            @can('pelanggan.show')
                                                <a href="{{ route('pelanggan.show', Crypt::encrypt($d->kode_pelanggan)) }}" class="action-btn detail"
                                                    title="Detail">
                                                    <i class="ti ti-file-description"></i>
                                                </a>
                                            @endcan
                                            @can('pelanggan.delete')
                                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                                    action="{{ route('pelanggan.delete', Crypt::encrypt($d->kode_pelanggan)) }}">
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
                                        <p class="mt-2" style="color: #9ca3af;">Tidak ada data pelanggan</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    {{ $pelanggan->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreatePelanggan" size="modal-lg" show="loadcreatePelanggan" title="Tambah Pelanggan" />
<x-modal-form id="mdleditPelanggan" size="modal-lg" show="loadeditPelanggan" title="Edit Pelanggan" />
<x-modal-form id="mdlNonaktifPelanggan" size="modal-xl" show="loadNonaktifPelanggan" title="Nonaktifkan Pelanggan" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {
        $("#btncreatePelanggan").click(function(e) {
            e.preventDefault();
            $('#mdlcreatePelanggan').modal("show");
            $("#loadcreatePelanggan").load('/pelanggan/create');
        });

        $("#btnNonaktif").click(function(e) {
            e.preventDefault();
            $('#mdlNonaktifPelanggan').modal("show");
            $("#loadNonaktifPelanggan").html(`
            <div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>
            `);
            $("#loadNonaktifPelanggan").load('/pelanggan/nonaktif');
        });

        $(".editPelanggan").click(function(e) {
            var kode_pelanggan = $(this).attr("kode_pelanggan");
            e.preventDefault();
            $('#mdleditPelanggan').modal("show");
            $("#loadeditPelanggan").load('/pelanggan/' + kode_pelanggan + '/edit');
        });

        function getsalesmanbyCabang() {
            var kode_cabang = $("#kode_cabang").val();
            var kode_salesman = "{{ Request('kode_salesman') }}";
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
                    $("#kode_salesman").html(respond);
                }
            });
        }

        getsalesmanbyCabang();
        $("#kode_cabang").change(function(e) {
            getsalesmanbyCabang();
        });
    });
</script>
@endpush
