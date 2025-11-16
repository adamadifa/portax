@extends('layouts.app')
@section('titlepage', 'Retur')

@section('content')
@section('navigasi')
    <span>Retur</span>
@endsection

<style>
    /* Retur Page Specific Styles */
    .retur-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .retur-header {
        background: linear-gradient(135deg, #03204f 0%, #1e3a8a 100%);
        color: #ffffff !important;
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .retur-header h4 {
        margin: 0;
        font-weight: 700;
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #ffffff !important;
    }

    .retur-header i {
        color: #ffffff !important;
    }

    .retur-header .btn {
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

    .retur-header .btn:hover {
        background: #ffd700;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(255, 200, 0, 0.3);
    }
</style>

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card retur-card">
            <div class="retur-header">
                <h4>
                    <i class="ti ti-arrow-back-up"></i>
                    Data Retur
                </h4>
                <div class="btn-group">
                    @can('retur.create')
                        <a href="{{ route('retur.create') }}" class="btn" id="btnCreate">
                            <i class="ti ti-plus me-2"></i>Input Retur
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body" style="padding: 20px;">
                <!-- Search Form -->
                <div class="search-card">
                    <form action="{{ route('retur.index') }}">
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
                                    <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                                        textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang_search') }}"
                                        select2="select2Kodecabangsearch" />
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
                                <x-input-with-icon label="Kode Pelanggan" value="{{ Request('kode_pelanggan_search') }}"
                                    name="kode_pelanggan_search" icon="ti ti-barcode" />
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12">
                                <x-input-with-icon label="Nama Pelanggan" value="{{ Request('nama_pelanggan_search') }}"
                                    name="nama_pelanggan_search" icon="ti ti-users" />
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
                                <th>No. Retur</th>
                                <th>Tanggal</th>
                                <th>No. Faktur</th>
                                <th>Nama Pelanggan</th>
                                <th>Nama Cabang</th>
                                <th>Salesman</th>
                                <th class="text-center">Jenis Retur</th>
                                <th class="text-end">Total</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($retur as $d)
                                <tr>
                                    <td>
                                        <span class="code-badge">
                                            <i class="ti ti-arrow-back-up me-1"></i>{{ $d->no_retur }}
                                        </span>
                                    </td>
                                    <td>
                                        <i class="ti ti-calendar me-1" style="color: #6c757d;"></i>{{ DateToIndo($d->tanggal) }}
                                    </td>
                                    <td>
                                        <span class="code-badge" style="background: #6c757d;">
                                            <i class="ti ti-file-invoice me-1"></i>{{ $d->no_faktur }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="table-name">
                                            <i class="ti ti-user me-1" style="color: #6c757d;"></i>{{ textUpperCase($d->nama_pelanggan) }}
                                        </div>
                                    </td>
                                    <td>
                                        <i class="ti ti-building me-1" style="color: #6c757d;"></i>{{ textUpperCase($d->nama_cabang) }}
                                    </td>
                                    <td>
                                        <i class="ti ti-user-circle me-1" style="color: #6c757d;"></i>{{ textUpperCase($d->nama_salesman) }}
                                    </td>
                                    <td class="text-center">
                                        @if ($d->jenis_retur == 'GB')
                                            <span class="status-badge aktif">Ganti Barang</span>
                                        @else
                                            <span class="status-badge nonaktif">Potong Faktur</span>
                                        @endif
                                    </td>
                                    <td class="text-end" style="font-weight: 600; color: #03204f;">
                                        {{ formatRupiah($d->total_retur) }}
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            @can('retur.show')
                                                <a href="#" class="action-btn detail btnShow" 
                                                   no_retur="{{ Crypt::encrypt($d->no_retur) }}" 
                                                   title="Detail">
                                                    <i class="ti ti-file-description"></i>
                                                </a>
                                            @endcan
                                            @can('retur.delete')
                                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                                    action="{{ route('retur.delete', Crypt::encrypt($d->no_retur)) }}">
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
                                    <td colspan="9" class="text-center py-4">
                                        <i class="ti ti-inbox" style="font-size: 40px; color: #d1d5db;"></i>
                                        <p class="mt-2" style="color: #9ca3af;">Tidak ada data retur</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    {{ $retur->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" size="modal-xl" show="loadmodal" title="" />
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

        $(".btnShow").click(function(e) {
            e.preventDefault();
            loading();
            const no_retur = $(this).attr('no_retur');
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Detail Retur");
            $("#loadmodal").load(`/retur/${no_retur}/show`);
        });
    });
</script>
@endpush
