@extends('layouts.app')
<style>
    .select2-container .select2-selection--single {
        height: 46px !important;
        padding: 10px 12px !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 0.5rem !important;
        background-color: #fff !important;
        display: flex !important;
        align-items: center !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: normal !important;
        padding-left: 0 !important;
        color: #1e293b !important;
        font-size: 0.875rem !important;
        flex-grow: 1 !important;
        display: flex !important;
        align-items: center !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 44px !important;
        top: 1px !important;
        right: 8px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #94a3b8 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__clear {
        margin-right: 0px !important;
        font-weight: bold !important;
        color: #cbd5e1 !important;
        order: 2 !important;
        margin-left: auto !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__clear:hover {
        color: #64748b !important;
    }
    .form-select {
        border-color: #cbd5e1 !important;
        border-radius: 0.5rem !important;
    }
    .form-select:focus {
            border-color: #003d9e !important;
            box-shadow: 0 0 0 1px #003d9e !important;
    }
</style>
@section('titlepage', 'Laporan Marketing')

@section('content')

@section('navigasi')
    <span>Laporan Marketing</span>
@endsection
<div class="row">
    <div class="col-xl-6 col-md-12 col-sm-12">
        <div class="nav-align-left nav-tabs-shadow mb-4">
            <ul class="nav nav-tabs" role="tablist">
                @can('mkt.penjualan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#penjualan"
                            aria-controls="penjualan" aria-selected="false" tabindex="-1">
                            Penjualan
                        </button>
                    </li>
                @endcan
                @can('mkt.rekappenjualan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekappenjualan"
                            aria-controls="penjualan" aria-selected="false" tabindex="-1">
                            Rekap Penjualan
                        </button>
                    </li>
                @endcan
                @can('mkt.kasbesar')
                    <li class="nav-item" role="kasbesar">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#kasbesar" aria-controls="kasbesar"
                            aria-selected="false" tabindex="-1">
                            Kas Besar
                        </button>
                    </li>
                @endcan
                @can('mkt.retur')
                    <li class="nav-item" role="retur">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#retur" aria-controls="retur"
                            aria-selected="false" tabindex="-1">
                            Retur
                        </button>
                    </li>
                @endcan
                @can('mkt.tunaikredit')
                    <li class="nav-item" role="retur">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tunaikredit"
                            aria-controls="tunaikredit" aria-selected="false" tabindex="-1">
                            Tunai Kredit
                        </button>
                    </li>
                @endcan
                @can('mkt.kartupiutang')
                    <li class="nav-item" role="retur">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#kartupiutang"
                            aria-controls="kartupiutang" aria-selected="false" tabindex="-1">
                            Kartu Piutang
                        </button>
                    </li>
                @endcan
                @can('mkt.pembelian')
                    <li class="nav-item" role="pembelian">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#pembelian"
                            aria-controls="pembelian" aria-selected="false" tabindex="-1">
                            Pembelian
                        </button>
                    </li>
                @endcan
            </ul>
            <div class="tab-content">
                @can('mkt.penjualan')
                    <div class="tab-pane fade active show" id="penjualan" role="tabpanel">
                        @include('marketing.laporan.penjualan')
                    </div>
                @endcan
                @can('mkt.rekappenjualan')
                    <div class="tab-pane fade" id="rekappenjualan" role="tabpanel">
                        @include('marketing.laporan.rekappenjualan')
                    </div>
                @endcan
                @can('mkt.kasbesar')
                    <div class="tab-pane fade" id="kasbesar" role="tabpanel">
                        @include('marketing.laporan.kasbesar')
                    </div>
                @endcan
                @can('mkt.retur')
                    <div class="tab-pane fade" id="retur" role="tabpanel">
                        @include('marketing.laporan.retur')
                    </div>
                @endcan
                @can('mkt.tunaikredit')
                    <div class="tab-pane fade" id="tunaikredit" role="tabpanel">
                        @include('marketing.laporan.tunaikredit')
                    </div>
                @endcan
                @can('mkt.dpp')
                    <div class="tab-pane fade" id="dpp" role="tabpanel">
                        @include('marketing.laporan.dpp')
                    </div>
                @endcan
                @can('mkt.omsetpelanggan')
                    <div class="tab-pane fade" id="omsetpelanggan" role="tabpanel">
                        @include('marketing.laporan.omsetpelanggan')
                    </div>
                @endcan
                @can('mkt.rekappelanggan')
                    <div class="tab-pane fade" id="rekappelanggan" role="tabpanel">
                        @include('marketing.laporan.rekappelanggan')
                    </div>
                @endcan
                @can('mkt.rekapkendaraan')
                    <div class="tab-pane fade" id="rekapkendaraan" role="tabpanel">
                        @include('marketing.laporan.rekapkendaraan')
                    </div>
                @endcan
                @can('mkt.rekapwilayah')
                    <div class="tab-pane fade" id="rekapwilayah" role="tabpanel">
                        @include('marketing.laporan.rekapwilayah')
                    </div>
                @endcan
                @can('mkt.analisatransaksi')
                    <div class="tab-pane fade" id="analisatransaksi" role="tabpanel">
                        @include('marketing.laporan.analisatransaksi')
                    </div>
                @endcan
                @can('mkt.tunaitransfer')
                    <div class="tab-pane fade" id="tunaitransfer" role="tabpanel">
                        @include('marketing.laporan.tunaitransfer')
                    </div>
                @endcan
                @can('mkt.effectivecall')
                    <div class="tab-pane fade" id="effectivecall" role="tabpanel">
                        @include('marketing.laporan.effectivecall')
                    </div>
                @endcan
                @can('mkt.kartupiutang')
                    <div class="tab-pane fade" id="kartupiutang" role="tabpanel">
                        @include('marketing.laporan.kartupiutang')
                    </div>
                @endcan
                @can('mkt.pembelian')
                    <div class="tab-pane fade" id="pembelian" role="tabpanel">
                        @include('marketing.laporan.pembelian')
                    </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
