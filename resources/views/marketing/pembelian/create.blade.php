@extends('layouts.app')
@section('titlepage', 'Input Pembelian Marketing')
@section('content')

    <style>
        .nonaktif {
            background-color: red;
        }

        /* Modern Card Styling */
        .modern-card {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
            background: #fff;
        }

        .modern-card:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .modern-card .card-body {
            padding: 1.5rem;
        }

        /* Info Card Styling */
        .info-card {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
        }

        .info-card .form-label {
            color: #495057;
            font-weight: 500;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .info-card .form-control,
        .info-card input {
            background: #fff;
            border: 1px solid #dee2e6;
            color: #495057;
            border-radius: 8px;
            padding: 0.625rem 1rem;
        }

        .info-card .form-control::placeholder {
            color: #adb5bd;
        }

        .info-card .form-control:focus {
            background: #fff;
            border-color: #03204f;
            color: #495057;
            box-shadow: 0 0 0 0.2rem rgba(3, 32, 79, 0.1);
        }

        /* Grand Total Card */
        .grandtotal-card {
            background: #fff;
            border: 2px solid #03204f;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            position: relative;
        }

        .grandtotal-card .grandtotal-content {
            position: relative;
            z-index: 1;
            color: #03204f;
        }

        .grandtotal-card .grandtotal-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .grandtotal-card .grandtotal-amount {
            font-size: 3rem;
            font-weight: 700;
            line-height: 1;
            margin: 0;
            color: #03204f;
        }

        .grandtotal-card .grandtotal-icon {
            font-size: 4rem;
            opacity: 0.1;
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #03204f;
        }

        /* Detail Card Styling */
        .detail-card {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .detail-card .card-header {
            background: #03204f;
            color: white;
            border: none;
            padding: 1.25rem 1.5rem;
        }

        .detail-card .card-header .card-title {
            margin: 0;
            font-weight: 600;
            font-size: 1.125rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .detail-card .card-header .card-title i {
            font-size: 1.25rem;
        }

        .detail-card .card-body {
            padding: 1.5rem;
        }

        /* Form Input Styling */
        .form-input-group {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1rem;
        }

        .modern-btn-primary {
            background: #03204f;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            color: white;
            transition: all 0.2s ease;
        }

        .modern-btn-primary:hover {
            background: #021a3d;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(3, 32, 79, 0.2);
        }

        .modern-btn-primary i {
            margin-right: 0.5rem;
        }

        /* Table Styling */
        .modern-table {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e9ecef;
        }

        .modern-table thead {
            background: #03204f;
            color: white;
        }

        .modern-table thead th {
            border: none;
            padding: 0.75rem 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .modern-table tbody td {
            padding: 0.5rem;
            vertical-align: middle;
            border-color: #e9ecef;
        }

        .modern-table tbody tr {
            transition: all 0.2s ease;
        }

        .modern-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .modern-table tfoot {
            background: #f8f9fa;
            font-weight: 600;
        }

        .modern-table tfoot td {
            border: none;
            padding: 0.5rem;
        }

        .btn-action {
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .btn-action:hover {
            transform: translateY(-1px);
        }

        /* Summary/Payment Card */
        .summary-card {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
        }

        .summary-card .form-label {
            font-weight: 600;
            color: #495057;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .summary-card .form-select,
        .summary-card .form-control {
            border-radius: 10px;
            border: 1.5px solid #dee2e6;
            padding: 0.625rem 1rem;
            transition: all 0.2s ease;
        }

        .summary-card .form-select:focus,
        .summary-card .form-control:focus {
            border-color: #03204f;
            box-shadow: 0 0 0 0.2rem rgba(3, 32, 79, 0.1);
        }

        .summary-card .form-group {
            margin-bottom: 1rem;
        }

        .btn-submit {
            background: #03204f;
            border: none;
            border-radius: 8px;
            padding: 0.875rem 1.5rem;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.2s ease;
            font-size: 1rem;
        }

        .btn-submit:hover {
            background: #021a3d;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(3, 32, 79, 0.2);
            color: white;
        }

        .btn-submit i {
            margin-right: 0.5rem;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .grandtotal-card .grandtotal-amount {
                font-size: 2.5rem;
            }

            .grandtotal-card .grandtotal-icon {
                font-size: 4rem;
            }

            .info-card {
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 576px) {
            .grandtotal-card {
                padding: 1.5rem;
            }

            .grandtotal-card .grandtotal-amount {
                font-size: 2rem;
            }

            .summary-card {
                padding: 1.25rem;
            }
        }
    </style>
@section('navigasi')
    <span class="text-muted">Pembelian</span> / <span>Input Pembelian</span>
@endsection
<form action="{{ route('pembelianmarketing.store') }}" method="POST" id="formPembelian">
    @csrf
    <input type="hidden" name="limit_supplier" id="limit_supplier">
    <input type="hidden" name="sisa_piutang" id="sisa_piutang">
    <input type="hidden" name="siklus_pembayaran" id="siklus_pembayaran">
    <input type="hidden" name="max_kredit" id="max_kredit">

    <div class="row">
        <div class="col-lg-4 col-md-12 col-sm-12 mb-3">
            <div class="info-card">
                <x-input-with-icon label="No. Bukti" name="no_bukti" icon="ti ti-barcode" />
                <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" />
                <x-input-with-icon label="Supplier" name="nama_supplier" icon="ti ti-building-store" readonly="true" />
                <input type="hidden" id="kode_supplier" name="kode_supplier">
                <input type="hidden" id="kode_cabang_supplier" name="kode_cabang_supplier">
            </div>
        </div>
        <div class="col-lg-8 col-md-12 col-sm-12 mb-3">
            <div class="grandtotal-card">
                <div class="grandtotal-content">
                    <div class="grandtotal-label">Grand Total</div>
                    <h1 class="grandtotal-amount" id="grandtotal_text">Rp 0</h1>
                </div>
                <i class="ti ti-shopping-bag grandtotal-icon"></i>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card detail-card">
                    <div class="card-header">
                        <h5 class="card-title" style="color: white !important">
                            <i class="ti ti-list"></i>
                            Detail Pembelian
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="form-input-group">
                            <div class="row g-3">
                                <div class="col-lg-5 col-md-12 col-sm-12">
                                    <x-input-with-icon label="Produk" name="nama_produk" icon="ti ti-barcode" readonly="true" />
                                    <input type="hidden" id="kode_harga" name="kode_harga">
                                    <input type="hidden" id="kode_produk" name="kode_produk">
                                    <input type="hidden" id="isi_pcs_dus" name="isi_pcs_dus">
                                    <input type="hidden" id="isi_pcs_pack" name="isi_pcs_pack">
                                    <input type="hidden" id="kode_kategori_diskon" name="kode_kategori_diskon">
                                </div>
                                <div class="col-lg-3 col-md-12 col-sm-12">
                                    <x-input-with-icon label="Dus" name="jml_dus" icon="ti ti-box" align="right" money="true" />
                                </div>
                                <div class="col-lg-4 col-md-12 col-sm-12">
                                    <x-input-with-icon label="Harga / Dus" name="harga_dus" icon="ti ti-moneybag" align="right" money="true" />
                                    <input type="hidden" id="harga_dus_produk">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <a href="#" id="tambahproduk" class="btn modern-btn-primary w-100">
                                <i class="ti ti-plus"></i>Tambah Produk
                            </a>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="table-responsive">
                                    <table class="table modern-table" id="tabelproduk">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Kode</th>
                                                <th style="text-align: left !important">Nama Barang</th>
                                                <th>Dus</th>
                                                <th>Harga / Dus</th>
                                                <th>Subtotal</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="loadproduk"></tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" class="text-end fw-bold">SUBTOTAL</td>
                                                <td class="text-end fw-bold" id="subtotal">Rp 0</td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12 d-flex justify-content-end">
                                <div class="col-lg-5 col-md-6 col-sm-12">
                                    <div class="summary-card">
                                        <input type="hidden" id="potongan_aida" name="potongan_aida" value="0">
                                        <input type="hidden" id="potongan_swan" name="potongan_swan" value="0">
                                        <input type="hidden" id="potongan_stick" name="potongan_stick" value="0">
                                        <input type="hidden" id="potongan_sambal" name="potongan_sambal" value="0">
                                        <input type="hidden" id="potis_aida" name="potis_aida" value="0">
                                        <input type="hidden" id="potis_swan" name="potis_swan" value="0">
                                        <input type="hidden" id="potis_stick" name="potis_stick" value="0">
                                        <input type="hidden" id="peny_aida" name="peny_aida" value="0">
                                        <input type="hidden" id="peny_swan" name="peny_swan" value="0">
                                        <input type="hidden" id="peny_stick" name="peny_stick" value="0">
                                        <div class="form-group">
                                            <label class="form-label">Jenis Transaksi</label>
                                            <select name="jenis_transaksi" id="jenis_transaksi" class="form-select">
                                                <option value="">Pilih Jenis Transaksi</option>
                                                <option value="T">TUNAI</option>
                                                <option value="K">KREDIT</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <x-input-with-icon label="Grand Total" name="grandtotal" id="grandtotal" icon="ti ti-shopping-cart"
                                                align="right" disabled="true" />
                                        </div>
                                        <div id="jenis_bayar_tunai">
                                            <div class="form-group">
                                                <label class="form-label">Jenis Bayar</label>
                                                <select name="jenis_bayar" id="jenis_bayar" class="form-select">
                                                    <option value="">Pilih Jenis Bayar</option>
                                                    <option value="TN">CASH</option>
                                                    <option value="TR">TRANSFER</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="titipan">
                                            <div class="form-group">
                                                <x-input-with-icon icon="ti ti-moneybag" name="titipan" money="true" align="right"
                                                    label="Titipan" />
                                            </div>
                                        </div>
                                        <div id="voucher_tunai">
                                            <div class="form-group">
                                                <x-input-with-icon icon="ti ti-tag" name="voucher" money="true" align="right"
                                                    label="Voucher" />
                                            </div>
                                        </div>
                                        <div class="form-group mb-0">
                                            <button class="btn btn-submit" id="btnSimpan">
                                                <i class="ti ti-send"></i>Submit Pembelian
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</form>

<x-modal-form id="modal" size="modal-xl" show="loadmodal" title="" />
<x-modal-form id="modaleditProduk" size="" show="loadmodaleditProduk" title="" />
<div class="modal fade" id="modalSupplier" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel18">Data Supplier</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table" id="tabelsupplier" width="100%">
                        <thead class="table-dark">
                            <tr>
                                <th>No.</th>
                                <th>Kode</th>
                                <th>Nama Supplier</th>
                                <th>No. HP</th>
                                <th>Alamat</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot class="table-dark">
                            <tr>
                                <th>No.</th>
                                <th>Kode</th>
                                <th>Nama Supplier</th>
                                <th>No. HP</th>
                                <th>Alamat</th>
                                <th>#</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('myscript')
<script type="text/javascript">
    $(document).ready(function() {

        const kode_cabang_user = '{{ Auth::user()->kode_cabang }}';
        let kode_cabang_supplier = '';
        let kode_pel = '';
        // alert(kode_cabang_user);
        let jmlfakturbelumlunas = 0;
        let jmlfakturmax = 0;
        let saldo_voucher = 0;

        function convertToRupiah(number) {
            if (number) {
                var rupiah = "";
                var numberrev = number
                    .toString()
                    .split("")
                    .reverse()
                    .join("");
                for (var i = 0; i < numberrev.length; i++)
                    if (i % 3 == 0) rupiah += numberrev.substr(i, 3) + ".";
                return (
                    rupiah
                    .split("", rupiah.length - 1)
                    .reverse()
                    .join("")
                );
            } else {
                return number;
            }
        }
        $('#tabelsupplier').DataTable({
            processing: true,
            serverSide: true,
            order: [
                [2, 'asc']
            ],
            ajax: '{{ url()->current() }}',
            bAutoWidth: false,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    width: '5%'
                },
                {
                    data: 'kode_supplier',
                    name: 'kode_supplier',
                    orderable: false,
                    searchable: false,
                    width: '10%'
                },
                {
                    data: 'nama_supplier',
                    name: 'nama_supplier',
                    orderable: false,
                    searchable: true,
                    width: '30%'
                },
                {
                    data: 'no_hp_supplier',
                    name: 'no_hp_supplier',
                    orderable: false,
                    searchable: false,
                    width: '15%'
                },
                {
                    data: 'alamat_supplier',
                    name: 'alamat_supplier',
                    orderable: false,
                    searchable: false,
                    width: '40%'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    width: '5%'
                }
            ]
        });

        $("#nama_supplier").on('click focus', function(e) {
            e.preventDefault();
            $("#modalSupplier").modal("show");
        });



        //Cek file Foto Supplier
        function checkFileExistence(fileFoto) {
            // fungsi ini tidak digunakan lagi pada halaman pembelianmarketing
        }

        //GetPiutang

        function getPiutang(kode_supplier) {
            // fungsi ini tidak digunakan lagi pada halaman pembelianmarketing
        }


        function getFakturkredit(kode_supplier) {
            // fungsi ini tidak digunakan lagi pada halaman pembelianmarketing
        }


        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..
         `);
        }

        function buttonEnable() {
            $("#btnSimpan").prop('disabled', false);
            $("#btnSimpan").html(`<i class="ti ti-send me-1"></i>Submit`);
        }
        //Get Supplier
        function getSupplier(kode_supplier) {
            buttonDisable();
            $.ajax({
                url: `/supplier/${kode_supplier}/getSupplier`,
                type: "GET",
                cache: false,
                success: function(response) {
                    // isi data pokok supplier saja
                    const status_aktif_supplier = response.data.status_aktif_supplier;
                    if (status_aktif_supplier === '0') {
                        Swal.fire({
                            title: "Oops!",
                            text: "Supplier Tidak Dapat Bertransaksi, Silahkan Hubungi Admin Untuk Mengaktifkan Supplier !",
                            icon: "warning",
                            showConfirmButton: true,
                        });
                    } else {
                        $('#kode_supplier').val(response.data.kode_supplier);
                        kode_pel = response.data.kode_supplier;
                        $('#kode_cabang_supplier').val(response.data.kode_cabang);
                        kode_cabang_supplier = response.data.kode_cabang;
                        $('#nama_supplier').val(response.data.nama_supplier);

                        $('#modalSupplier').modal('hide');
                        buttonEnable();
                    }
                }
            });
        }
        //Pilih Supplier
        $('#tabelsupplier tbody').on('click', '.pilihsupplier', function(e) {
            e.preventDefault();
            let kode_supplier = $(this).attr('kode_supplier');
            getSupplier(kode_supplier);
            $("#loadproduk").html('');
            $("#potongan_swan").val(0);
            $("#potongan_aida").val(0);
            $("#potongan_sp").val(0);
            $("#potongan_stick").val(0);
            $("#potongan_sambal").val(0);
            loadsubtotal();

        });


        //GetProduk
        function getHarga() {
            buttonDisable();
            $.ajax({
                url: `/produk/getproduk`,
                type: 'GET',
                cache: false,
                success: function(response) {
                    buttonEnable();
                    $("#loadmodal").html(response);
                }
            });
        }
        //Pilih Produk
        $("#nama_produk").on('click', function(e) {
            e.preventDefault();
            let kode_supplier = $("#kode_supplier").val();
            if (kode_supplier == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih dulu Supplier !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#nama_supplier").focus();
                    },
                });
            } else {
                $("#modal").modal("show");
                $("#modal").find(".modal-title").text('Data Produk');
                getHarga();
            }
        });

        $(document).on('click', '.pilihProduk', function(e) {
            e.preventDefault();
            let kode_produk = $(this).attr('kode_produk');
            let nama_produk = $(this).attr('nama_produk');
            let isi_pcs_dus = $(this).attr('isi_pcs_dus');
            let isi_pcs_pack = $(this).attr('isi_pcs_pack');

            // Hanya set kode_produk dan nama_produk ke form
            $("#kode_produk").val(kode_produk);
            $("#nama_produk").val(nama_produk);

            // Set isi_pcs_dus dan isi_pcs_pack untuk perhitungan (hidden field)
            $("#isi_pcs_dus").val(isi_pcs_dus);
            $("#isi_pcs_pack").val(isi_pcs_pack);

            // Clear harga fields (user will input manually)
            $("#harga_dus").val('');
            $("#harga_pack").val('');
            $("#harga_pcs").val('');
            $("#harga_dus_produk").val('');
            $("#harga_pack_produk").val('');
            $("#harga_pcs_produk").val('');
            $("#kode_harga").val('');
            $("#kode_kategori_diskon").val('');

            // Enable harga fields untuk input manual
            $("#harga_dus").prop('disabled', false);
            if (isi_pcs_pack == "" || isi_pcs_pack === '0') {
                $("#harga_pack").prop('disabled', true);
                $("#jml_pack").prop('disabled', true);
            } else {
                $("#harga_pack").prop('disabled', false);
                $("#jml_pack").prop('disabled', false);
            }
            $("#harga_pcs").prop('disabled', false);

            $("#modal").modal("hide");
        });


        function convertoduspackpcs(isi_pcs_dus, isi_pcs_pack, jumlah) {
            let jml_dus = Math.floor(jumlah / isi_pcs_dus);
            let sisa_dus = jumlah % isi_pcs_dus;
            let jml_pack = 0;
            let sisa_pack = 0;
            if (isi_pcs_pack !== '0' && isi_pcs_pack != '') {
                jml_pack = Math.floor(sisa_dus / isi_pcs_pack);
                sisa_pack = sisa_dus % isi_pcs_pack;
            } else {
                jml_pack = 0;
                sisa_pack = sisa_dus;
            }
            let jml_pcs = sisa_pack;


            let data = {
                "dus": jml_dus,
                "pack": jml_pack,
                "pcs": jml_pcs
            };

            return data;
        }


        function addProduk() {
            var kode_produk = $("#kode_produk").val();
            var nama_produk = $("#nama_produk").val();
            var jml_dus = $("#jml_dus").val() || "";
            var jml_pack = $("#jml_pack").val() || "";
            var jml_pcs = $("#jml_pcs").val() || "";
            var harga_dus = $("#harga_dus").val() || "";
            var harga_pack = $("#harga_pack").val() || "";
            var harga_pcs = $("#harga_pcs").val() || "";
            var isi_pcs_dus = $("#isi_pcs_dus").val() || "0";
            var isi_pcs_pack = $("#isi_pcs_pack").val() || "0";
            var kode_kategori_diskon = $("#kode_kategori_diskon").val() || "";

            var jmldus = (jml_dus != "" && jml_dus != null) ? parseInt(jml_dus.toString().replace(/\./g, '')) : 0;
            var jmlpack = (jml_pack != "" && jml_pack != null) ? parseInt(jml_pack.toString().replace(/\./g, '')) : 0;
            var jmlpcs = (jml_pcs != "" && jml_pcs != null) ? parseInt(jml_pcs.toString().replace(/\./g, '')) : 0;

            var hargadus = (harga_dus != "" && harga_dus != null) ? parseInt(harga_dus.toString().replace(/\./g, '')) : 0;
            var hargapack = (harga_pack != "" && harga_pack != null) ? parseInt(harga_pack.toString().replace(/\./g, '')) : 0;
            var hargapcs = (harga_pcs != "" && harga_pcs != null) ? parseInt(harga_pcs.toString().replace(/\./g, '')) : 0;

            var isi_pcs_dus_num = parseInt(isi_pcs_dus) || 0;
            var isi_pcs_pack_num = parseInt(isi_pcs_pack) || 0;

            var jumlah = (jmldus * isi_pcs_dus_num) + (jmlpack * isi_pcs_pack_num) + jmlpcs;

            let data = convertoduspackpcs(isi_pcs_dus_num, isi_pcs_pack_num, jumlah);
            let dus = data.dus;
            let pack = data.pack;
            let pcs = data.pcs;

            let index = kode_produk;

            let subtotal = (parseInt(dus) * parseInt(hargadus));


            if (kode_produk == "" || kode_produk == null) {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih dulu Produk !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#nama_produk").focus();
                    },
                });
            } else if (harga_dus == "" || hargadus == 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Harga / Dus Tidak Boleh Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#harga_dus").focus();
                    },
                });
            } else if (jumlah == "" || jumlah === '0' || jumlah == 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Tidak Boleh Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#nama_produk").focus();
                    },
                });
            } else if ($('#tabelproduk').find('#index_' + index).length > 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Data Sudah Ada!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#nama_produk").focus();
                    },

                });
            } else {
                let produk = `
                    <tr id="index_${index}">
                        <td>
                            <input type="hidden" name="kode_produk[]" value="${kode_produk}" class="kode_produk"/>
                            <input type="hidden" name="status_promosi_produk[]" value="0"/>
                            <input type="hidden" name="kode_kategori_diskon[]" class="kode_kategori_diskon" value="${kode_kategori_diskon}"/>
                            <input type="hidden" name="jumlah_produk[]" value="${jumlah}"/>
                            <input type="hidden" name="isi_pcs_dus_produk[]" value="${isi_pcs_dus}"/>
                            <input type="hidden" name="isi_pcs_pack_produk[]" value="${isi_pcs_pack}"/>
                            ${kode_produk}
                        </td>
                        <td>${nama_produk}</td>
                        <td class="text-center">
                           ${dus===0 ? '' : dus}
                        </td>
                        <td class="text-end">
                           ${convertToRupiah(hargadus)}
                           <input type="hidden" name="harga_dus_produk[]" value="${hargadus}"/>
                        </td>
                        <td class="text-end">
                            ${convertToRupiah(subtotal)}
                            <input type="hidden" name="subtotal[]" class="subtotal" value="${subtotal}"/>
                        </td>
                        <td class="text-center">
                           <div class="d-flex">
                              <div>
                                 <a href="#" key="${index}" class="edit me-2"><i class="ti ti-edit text-success"></i></a>
                              </div>
                              <div>
                                 <a href="#" key="${index}" class="delete"><i class="ti ti-trash text-danger"></i></a>
                              </div>
                           </div>

                        </td>
                    </tr>
                `;

                //append to table
                $('#loadproduk').append(produk);
                $("#kode_produk").val("");
                $("#nama_produk").val("");
                $("#jml_dus").val("");
                $("#jml_pack").val("");
                $("#jml_pcs").val("");
                $("#harga_dus").val("");
                $("#harga_pack").val("");
                $("#harga_pcs").val("");

                $("#harga_dus_produk").val("");
                $("#harga_pack_produk").val("");
                $("#harga_pcs_produk").val("");
                $("#isi_pcs_dus").val("");
                $("#isi_pcs_pack").val("");
                $("#kode_kategori_diskon").val("");

                loadsubtotal();


            }

        }

        //Tambah Item Produk
        $("#tambahproduk").click(function(e) {
            e.preventDefault();
            addProduk();
        });


        $(document).on('click', '.delete', function(e) {
            e.preventDefault();
            let key = $(this).attr("key");
            event.preventDefault();
            Swal.fire({
                title: `Apakah Anda Yakin Ingin Menghapus Data Ini ?`,
                text: "Jika dihapus maka data akan hilang permanent.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                showCancelButton: true,
                confirmButtonColor: "#554bbb",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Hapus Saja!"
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $(`#index_${key}`).remove();
                    loadsubtotal();
                }
            });
        });



        let currentRow;
        $(document).on('click', '.edit', function(e) {
            e.preventDefault();
            // Dapatkan baris tabel yang sesuai
            currentRow = $(this).closest('tr');

            // Ambil data dari sel
            let kode_harga = currentRow.find('.kode_harga').val();
            let kode_produk = currentRow.find('.kode_produk').val();
            let nama_produk = currentRow.find('td:eq(1)').text();
            let jml_dus = currentRow.find('td:eq(2)').text();
            let harga_dus = currentRow.find('td:eq(3)').text();
            let subtotal = currentRow.find('td:eq(4)').text();
            let kode_supplier = $("#kode_supplier").val();
            let index_old = kode_harga;
            let dataProduk = {
                'kode_supplier': kode_supplier,
                'kode_harga': kode_harga,
                'kode_produk': kode_produk,
                'nama_produk': nama_produk,
                'jml_dus': jml_dus,
                'harga_dus': harga_dus,
                'jml_pack': '',
                'harga_pack': '',
                'jml_pcs': '',
                'harga_pcs': '',
                'status_promosi': 0,
                'index_old': index_old
            };
            $.ajax({
                type: 'POST',
                url: '/penjualan/editproduk',
                data: {
                    _token: "{{ csrf_token() }}",
                    dataproduk: dataProduk
                },
                cache: false,
                success: function(respond) {
                    $("#modaleditProduk").modal("show");
                    $("#modaleditProduk").find(".modal-title").text("Edit Produk");
                    $("#loadmodaleditProduk").html(respond);
                }
            });
        });

        $(document).on('submit', '#formEditproduk', function(event) {
            event.preventDefault();
            let kode_harga = $(this).find("#kode_harga").val();
            let kode_produk = $(this).find("#kode_produk").val();
            let nama_produk = $(this).find("#kode_harga").find(':selected').text();
            let jml_dus = $(this).find("#jml_dus").val();
            let jml_pack = $(this).find("#jml_pack").val();
            let jml_pcs = $(this).find("#jml_pcs").val();
            let harga_dus = $(this).find("#harga_dus").val();
            let harga_pack = $(this).find("#harga_pack").val();
            let harga_pcs = $(this).find("#harga_pcs").val();
            let isi_pcs_dus = $(this).find("#isi_pcs_dus").val();
            let isi_pcs_pack = $(this).find("#isi_pcs_pack").val();
            let kode_kategori_diskon = $(this).find("#kode_kategori_diskon").val();
            let index_old = $(this).find("#index_old").val();
            let status_promosi = 0;




            let jmldus = jml_dus != "" ? parseInt(jml_dus.replace(/\./g, '')) : 0;
            let jmlpack = jml_pack != "" ? parseInt(jml_pack.replace(/\./g, '')) : 0;
            let jmlpcs = jml_pcs != "" ? parseInt(jml_pcs.replace(/\./g, '')) : 0;

            let hargadus = harga_dus != "" ? parseInt(harga_dus.replace(/\./g, '')) : 0;
            let hargapack = harga_pack != "" ? parseInt(harga_pack.replace(/\./g, '')) : 0;
            let hargapcs = harga_pcs != "" ? parseInt(harga_pcs.replace(/\./g, '')) : 0;

            let jumlah = (jmldus * parseInt(isi_pcs_dus)) + (jmlpack * (parseInt(isi_pcs_pack))) +
                jmlpcs;

            let data = convertoduspackpcs(isi_pcs_dus, isi_pcs_pack, jumlah);
            let dus = data.dus;
            let pack = data.pack;
            let pcs = data.pcs;

            let index = kode_harga;
            let subtotal = (parseInt(dus) * parseInt(hargadus));

            let newRow = `
                    <tr id="index_${index}">
                        <td>
                            <input type="hidden" name="kode_produk[]" value="${kode_produk}" class="kode_produk"/>
                            <input type="hidden" name="status_promosi_produk[]" value="0"/>
                            <input type="hidden" name="kode_kategori_diskon[]" class="kode_kategori_diskon" value="${kode_kategori_diskon}"/>
                            <input type="hidden" name="jumlah_produk[]" value="${jumlah}"/>
                            <input type="hidden" name="isi_pcs_dus_produk[]" value="${isi_pcs_dus}"/>
                            <input type="hidden" name="isi_pcs_pack_produk[]" value="${isi_pcs_pack}"/>
                            ${kode_produk}
                        </td>
                        <td>${nama_produk}</td>
                        <td class="text-center">
                           ${dus===0 ? '' : dus}
                        </td>
                        <td class="text-end">
                           ${convertToRupiah(hargadus)}
                           <input type="hidden" name="harga_dus_produk[]" value="${hargadus}"/>
                        </td>
                        <td class="text-end">
                            <input type="hidden" name="subtotal[]" class="subtotal" value="${subtotal}"/>
                            ${convertToRupiah(subtotal)}
                        </td>
                        <td class="text-center">
                           <div class="d-flex">
                              <div>
                                 <a href="#" key="${index}" class="edit me-2"><i class="ti ti-edit text-success"></i></a>
                              </div>
                              <div>
                                 <a href="#" key="${index}" class="delete"><i class="ti ti-trash text-danger"></i></a>
                              </div>
                           </div>

                        </td>
                    </tr>
                `;
            if (kode_harga == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih dulu Produk !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#kode_harga").focus();
                    },
                });
            } else if (jumlah == "" || jumlah === '0') {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Tidak Boleh Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#jml_dus").focus();
                    },
                });
            } else if (index != index_old && $('#tabelproduk').find('#index_' + index).length > 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Data Sudah Ada !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#kode_harga").focus();
                    },
                });
            } else {
                currentRow.replaceWith(newRow);

                $("#modaleditProduk").modal("hide");
            }
            loadsubtotal();
        });


        function loadsubtotal() {
            let subtotal = 0;
            let valSubtotal = $("#tabelproduk").find(".subtotal");

            valSubtotal.each(function() {
                let val = parseInt($(this).val());
                subtotal += isNaN(val) ? 0 : val;
            });

            $("#subtotal").text(convertToRupiah(subtotal));
            // Diskon dinonaktifkan: set semua potongan ke 0
            $("#potongan_aida, #potongan_swan, #potongan_stick, #potongan_sp, #potongan_sambal, #potongan_istimewa").val(convertToRupiah(0));
            calculateGrandtotal();
        }



        // Function to calculate total quantity based on category
        function calculateTotalQuantityByCategory(category) {
            let totalQuantity = 0;
            // Loop through each row in the table
            $('#tabelproduk tbody tr').each(function() {
                // Check if the category matches
                if ($(this).find('.kode_kategori_diskon').val() === category) {
                    // Add quantity to total if category matches
                    if ($(this).find('.status_promosi').val() === '0') {
                        totalQuantity += parseInt($(this).find('td:eq(2)').text());
                    }
                }
            });

            return totalQuantity;
        }

        function calculateTotalQuantityByProduct(kode_produk) {
            let totalQuantity = 0;
            // Loop through each row in the table
            $('#tabelproduk tbody tr').each(function() {
                // Check if the category matches
                if ($(this).find('.kode_produk').val() === kode_produk) {
                    // Add quantity to total if category matches
                    if ($(this).find('.status_promosi').val() === '0') {
                        totalQuantity += parseInt($(this).find('td:eq(2)').text());
                    }
                }
            });
            console.log(kode_produk + ': ' + totalQuantity);
            // console.log(category + ': ' + totalQuantity);
            return totalQuantity || 0;
        }

        function calculateDiscount(totalQuantity, category) {
            return 0;
            let discount = 0;
            let discount_tunai = 0;
            let total_discount = 0;
            let nama_supplier = $("#nama_supplier").val();
            let jenis_transaksi = $("#jenis_transaksi").val();
            // Define discount rules based on quantity range and category
            const discountRules = [];

            // Find the applicable discount rule based on total quantity and category
            for (let i = 0; i < discountRules.length; i++) {
                if (totalQuantity >= discountRules[i].min_qty &&
                    totalQuantity <= discountRules[i].max_qty &&
                    category === discountRules[i].kode_kategori_diskon) {
                    if (jenis_transaksi === 'T') {
                        discount = totalQuantity * discountRules[i].diskon;
                        discount_tunai = totalQuantity * discountRules[i].diskon_tunai;
                        total_discount = discount + discount_tunai;
                    } else {
                        total_discount = totalQuantity * discountRules[i].diskon;
                    }

                    if (nama_supplier.includes('KPBN') || nama_supplier.includes('RSB')) {
                        total_discount = 0;
                    }
                    break;
                }
            }

            return total_discount;
        }

        $("#jenis_transaksi").change(function() {
            loadsubtotal();
            showhidetunai();
            showhidekredit();
        });

        function hitungdiskonAida() {
            let totalQuantity = calculateTotalQuantityByCategory('D002');
            let diskon = calculateDiscount(totalQuantity, 'D002');
            $("#potongan_aida").val(convertToRupiah(diskon));
            return diskon;
        }

        function hitungdiskonProductBP500() {
            let totalQuantity = calculateTotalQuantityByProduct('BP500');
            let diskon = totalQuantity * 2000;
            return diskon;

        }


        function hitungdiskonSPPP500() {
            let totalQuantity = calculateTotalQuantityByCategory('D008');
            let diskon = calculateDiscount(totalQuantity, 'D008');
            return diskon;

        }

        function hitungdiskonSPPP1000() {
            let blacklist_supplier = [
                'BGR-06675',
                'BGR-06827',
                'BGR-06854',
                'BKI-00068',
                'BKI-00122',
                'BKI-00633',
                'BKI-00841',
                'BKI-00869',
                'BKI-00959',
                'BKI-00982',
                'BKI-01041',
                'BKI-01108',
                'BKI-01163',
                'BKI-01164',
                'BKI-01196',
                'BKI-01198',
                'BKI-01199',
                'BKI-01222',
                'BKI-01223',
                'BKI-01234',
                'BKI-01277',
                'BKI-01282',
                'BKI-01283',
                'BKI-01292',
                'BKI-01299',
                'BKI-01383',
                'BKI-01387',
                'BGR-06648',
                'BKI-00052',
                'BKI-00100',
                'BKI-00110',
                'BKI-00113',
                'BKI-00116',
                'BKI-00140',
                'BKI-00268',
                'BKI-00293',
                'BKI-00295',
                'BKI-00315',
                'BKI-00341',
                'BKI-00350',
                'BKI-00351',
                'BKI-00353',
                'BKI-00370',
                'BKI-00381',
                'BKI-00384',
                'BKI-00450',
                'BKI-00552',
                'BKI-00604',
                'BKI-00615',
                'BKI-00671',
                'BKI-00800',
                'BKI-00813',
                'BKI-00821',
                'BKI-00831',
                'BKI-00858',
                'BKI-00871',
                'BKI-00872',
                'BKI-00879',
                'BKI-00884',
                'BKI-00985',
                'BKI-01006',
                'BKI-01026',
                'BKI-01052',
                'BKI-01081',
                'BKI-01111',
                'BKI-01112',
                'BKI-01119',
                'BKI-01151',
                'BKI-01171',
                'BKI-01172',
                'BKI-01202',
                'BKI-01203',
                'BKI-01278',
                'BKI-01287',
                'BKI-01291',
                'BKI-01295',
                'BKI-01296',
                'BKI-01297',
                'BKI-01375',
                'BKI-01376',
                'BKI-01377',
                'BKI-01386',
                'BKI-01388',
                'BKI-01390',
                'BKI-01392',
                'BKI-01394',
                'BKI-01400',
                'BGR-06669',
                'BGR-06759',
                'BKI-00007',
                'BKI-00008',
                'BKI-00029',
                'BKI-00121',
                'BKI-00258',
                'BKI-00367',
                'BKI-00518',
                'BKI-00585',
                'BKI-00686',
                'BKI-00735',
                'BKI-00773',
                'BKI-00777',
                'BKI-00902',
                'BKI-00911',
                'BKI-00912',
                'BKI-00917',
                'BKI-00936',
                'BKI-00973',
                'BKI-00979',
                'BKI-01023',
                'BKI-01040',
                'BKI-01049',
                'BKI-01187',
                'BKI-01193',
                'BKI-01194',
                'BKI-01201',
                'BKI-01236',
                'BKI-01238',
                'BKI-01240',
                'BKI-01242',
                'BKI-01261',
                'BKI-01266',
                'BKI-01285',
                'BKI-01294',
                'BGR-07636',
                'BRG-07591',
                'BRG-07480',
                'BRG-07465',
                'BRG-07650',
                'BRG-07982',
                'BRG-07984',
                'BRG-07751',
                'BRG-07638',
                'BRG-07597',
                'BRG-07643',
                'BRG-07966',
                'BRG-07809',
                'BRG-07693',
                'BRG-07784'
            ];
            let totalQuantity = calculateTotalQuantityByCategory('D009');
            let diskon = calculateDiscount(totalQuantity, 'D009');
            if (blacklist_supplier.includes(kode_pel)) {
                diskon = 0;
            }
            return diskon;

        }


        function hitungdskonSAOSME() {
            const kode_cabang_diskon_saosme = ['BTN', 'CRB'];
            let totalQuantity = calculateTotalQuantityByCategory('D010');
            let diskon = calculateDiscount(totalQuantity, 'D010');
            // Baris berikut memeriksa apakah kode_cabang_supplier termasuk dalam daftar kode_cabang_diskon_saosme.
            // Jika iya, maka nilai diskon tetap (tidak diubah). Sebenarnya, penugasan diskon = diskon; tidak melakukan perubahan apapun,
            // sehingga baris ini hanya sebagai placeholder atau untuk menandai bahwa diskon hanya berlaku untuk cabang tertentu.
            if (kode_cabang_diskon_saosme.includes(kode_cabang_supplier)) {
                diskon = diskon;
                //alert('YES');
            } else {
                diskon = 0;
                //alert('NO');
            }

            //alert(diskon);
            return diskon;

        }

        function hitungdiskonSwan() {
            let totalQuantity = calculateTotalQuantityByCategory('D001');
            let diskon = calculateDiscount(totalQuantity, 'D001');
            let diskonbp500 = hitungdiskonProductBP500();
            let diskonSPPP500 = hitungdiskonSPPP500();
            let diskonSPPP1000 = hitungdiskonSPPP1000();
            let diskonSAOSME = hitungdskonSAOSME();
            let totaldiskon = parseInt(diskon) + parseInt(diskonbp500) + parseInt(diskonSPPP500) + parseInt(
                diskonSPPP1000) + parseInt(
                diskonSAOSME);
            $("#potongan_swan").val(convertToRupiah(totaldiskon));
            return totaldiskon;
        }

        function hitungdiskonStick() {
            let blacklist_supplier = [];

            let totalQuantity = calculateTotalQuantityByCategory('D003');
            let diskon = calculateDiscount(totalQuantity, 'D003');

            if (blacklist_supplier.includes(kode_pel)) {
                diskon = 0;
            }

            $("#potongan_stick").val(convertToRupiah(diskon));
        }



        function hitungdiskonSP() {
            let totalQuantity = calculateTotalQuantityByCategory('D004');
            let diskon = calculateDiscount(totalQuantity, 'D004');
            $("#potongan_sp").val(convertToRupiah(diskon));
        }


        function hitungdiskonSC() {
            let totalQuantity = calculateTotalQuantityByCategory('D005');
            let diskon = calculateDiscount(totalQuantity, 'D005');
            $("#potongan_sambal").val(convertToRupiah(diskon));
        }


        function calculateGrandtotal() {
            const subtotalVal = $("#subtotal").text();
            const subtotal = subtotalVal != "" ? parseInt(subtotalVal.replace(/\./g, '')) : 0;
            const potonganSwanVal = $("#potongan_swan").val();
            const potongan_swan = potonganSwanVal != "" ? parseInt(potonganSwanVal.replace(/\./g, '')) : 0;

            const potonganAidaVal = $("#potongan_aida").val();
            const potongan_aida = potonganAidaVal != "" ? parseInt(potonganAidaVal.replace(/\./g, '')) : 0;

            const potonganStickVal = $("#potongan_stick").val();
            const potongan_stick = potonganStickVal != "" ? parseInt(potonganStickVal.replace(/\./g, '')) : 0;

            const potonganSambalVal = $("#potongan_sambal").val();
            const potongan_sambal = potonganSambalVal != "" ? parseInt(potonganSambalVal.replace(/\./g, '')) :
                0;

            const total_potongan = parseInt(potongan_swan) + parseInt(potongan_aida) + parseInt(
                potongan_stick) + parseInt(potongan_sambal);

            //Potongan Istimewa
            const potisAidaVal = $("#potis_aida").val();
            const potis_aida = potisAidaVal != "" ? parseInt(potisAidaVal.replace(/\./g, '')) : 0;

            const potisSwanVal = $("#potis_swan").val();
            const potis_swan = potisSwanVal != "" ? parseInt(potisSwanVal.replace(/\./g, '')) : 0;

            const potisStickVal = $("#potis_stick").val();
            const potis_stick = potisStickVal != "" ? parseInt(potisStickVal.replace(/\./g, '')) : 0;

            const total_potongan_istimewa = parseInt(potis_aida) + parseInt(potis_swan) + parseInt(potis_stick);

            //Penyesuaian dinonaktifkan
            const total_penyesuaian = 0;



            const grandtotal = parseInt(subtotal) - parseInt(total_potongan) - parseInt(
                total_potongan_istimewa) - parseInt(total_penyesuaian);
            $("#grandtotal_text").text(convertToRupiah(grandtotal));
            $("#grandtotal").val(convertToRupiah(grandtotal));
            console.log(grandtotal);
        }

        $("#potongan_aida, #potongan_swan, #potongan_stick, #potongan_sambal, #potis_aida, #potis_swan, #potis_stick, #peny_aida, #peny_swan, #peny_stick ")
            .on('keyup keydown', function() {
                calculateGrandtotal();
            });

        function showhidetunai() {
            const jenis_transaksi = $("#jenis_transaksi").val();
            if (jenis_transaksi == 'T') {
                $("#jenis_bayar_tunai").show();
                $("#voucher_tunai").show();
            } else {
                $("#jenis_bayar_tunai").hide();
                $("#voucher_tunai").hide();
            }
        }

        function showhidekredit() {
            const jenis_transaksi = $("#jenis_transaksi").val();
            if (jenis_transaksi == 'K') {
                $("#titipan").show();
            } else {
                $("#titipan").hide();
            }
        }

        showhidetunai();
        showhidekredit();



        $("#formPembelian").submit(function(e) {
            // e.preventDefault();
            const no_bukti = $("#no_bukti").val();
            const tanggal = $("#tanggal").val();
            const kode_supplier = $("#kode_supplier").val();
            const sisa_piutang = $("#sisa_piutang").val();
            const gt = $("#grandtotal").val();
            const grandtotal = gt != "" ? parseInt(gt.replace(/\./g, '')) : 0;
            const totalPiutang = parseInt(sisa_piutang) + parseInt(grandtotal);
            let limit_supplier = $("#limit_supplier").val() == "" ? 0 : $("#limit_supplier").val();
            // alert(limit_supplier);
            const siklus_pembayaran = $("#siklus_pembayaran").val();
            const max_kredit = $("#max_kredit").val();
            const jenis_transaksi = $("#jenis_transaksi").val();
            const jenis_bayar = $("#jenis_bayar").val();
            const voucher = $("#voucher").val().replace(/\./g, '');
            if (no_bukti == '') {
                SwalWarning('no_bukti', 'No. Bukti Tidak Boleh Kosong');
                return false;
            } else if (tanggal == '') {
                SwalWarning('tanggal', 'Tanggal Tidak Boleh Kosong');
                return false;
            } else if (kode_supplier == "") {
                SwalWarning('nama_supplier', 'Supplier Tidak Boleh Kosong');
                return false;
            } else if ($('#loadproduk tr').length == 0) {
                SwalWarning('nama_produk', 'Detail Produk Tidak Boleh Kosong');
                return false;
            } else if (jenis_transaksi == "") {
                SwalWarning('jenis_transaksi', 'Jenis Transaksi Tidak Boleh Kosong');
                return false;
            } else if (jenis_transaksi == "T" && jenis_bayar == "") {
                SwalWarning('jenis_bayar', 'Jenis Bayar Tidak Boleh Kosong');
                return false;
            } else if (jenis_transaksi == "K" && siklus_pembayaran === '0' && parseInt(totalPiutang) >
                parseInt(limit_supplier)) {
                SwalWarning('nama_produk', 'Melebihi Limit, Silahkan Ajukan Penambahan Limit !');
                return false;
            } else if (jenis_transaksi == "K" && siklus_pembayaran === '1' && parseInt(grandtotal) >
                parseInt(limit_supplier)) {
                SwalWarning('nama_produk', 'Melebihi Limit, Silahkan Ajukan Penambahan Limit !');
                return false;
            } else if (voucher > saldo_voucher) {
                SwalWarning('voucher', 'Melebihi Saldo Voucher !');
                return false;
            } else {
                buttonDisable();
            }
        });

    });
</script>
@endpush
