@extends('layouts.app')
@section('titlepage', 'Input Pembelian Marketing')
@section('content')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

    <style>
        .font-public-sans {
            font-family: 'Public Sans', sans-serif !important;
        }
        .nonaktif {
            background-color: red;
        }
    </style>

@section('navigasi')
    <span class="text-muted">Pembelian</span> / <span>Input Pembelian</span>
@endsection

<form action="{{ route('pembelianmarketing.store') }}" method="POST" id="formPembelian" class="mt-4 font-public-sans">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- LEFT COLUMN: Workspace (Input + Table) - span 9 -->
        <div class="col-span-12 lg:col-span-9 space-y-6">
            
             <!-- Grand Total -->
             <div class="bg-[#003d9e] rounded-xl shadow-lg p-6 relative overflow-hidden text-center group flex flex-col justify-center min-h-[140px]">
                <div class="absolute inset-0 bg-white/10 group-hover:bg-white/20 transition-colors"></div>
                 <div class="absolute -right-6 -top-6 text-white/10 rotate-12">
                    <i class="ti ti-shopping-bag text-[12rem]"></i>
                </div>
                 <p class="text-blue-100 text-sm uppercase tracking-wider mb-2 relative z-10">Grand Total</p>
                 <h1 class="text-5xl font-black text-white tracking-tight relative z-10" id="grandtotal_text">Rp 0</h1>
            </div>

            <!-- Input Produk -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-3 space-y-2">
                
                <!-- Row 1: Inputs -->
                <div class="flex flex-col lg:flex-row gap-2 items-start">
                    <!-- Product Selector (Left - Grow) -->
                    <div class="flex-1 w-full lg:w-auto">
                        <div class="relative h-full">
                            <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-[#003d9e] focus-within:border-[#003d9e] h-[45px]"> 
                                <span class="pl-3 text-slate-400"><i class="ti ti-scan"></i></span>
                                <input type="text" name="nama_produk" id="nama_produk" class="w-full px-3 py-2 text-sm border-0 focus:ring-0 bg-transparent placeholder-slate-400 cursor-pointer h-full" placeholder="Pilih Produk..." readonly>
                            </div>
                         </div>
                        <input type="hidden" id="kode_harga" name="kode_harga">
                        <input type="hidden" id="kode_produk" name="kode_produk">
                        <input type="hidden" id="isi_pcs_dus" name="isi_pcs_dus">
                        <input type="hidden" id="isi_pcs_pack" name="isi_pcs_pack">
                        <input type="hidden" id="kode_kategori_diskon" name="kode_kategori_diskon">
                    </div>

                    <!-- Unit Inputs (Right - Fixed Widths) -->
                    <div class="flex items-start gap-2 flex-wrap lg:flex-nowrap">
                        
                        <!-- Dus -->
                        <div class="w-[120px] space-y-2">
                             <!-- Qty -->
                            <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-[#003d9e] focus-within:border-[#003d9e] h-[45px]">
                                <span class="pl-2 text-slate-400 text-sm"><i class="ti ti-box"></i></span>
                                <input type="text" name="jml_dus" id="jml_dus" class="money w-full px-2 py-1 text-right text-sm border-0 focus:ring-0 placeholder-slate-300" placeholder="Dus">
                            </div>
                        </div>

                         <!-- Harga/Dus -->
                         <div class="w-[150px] space-y-2">
                             <!-- Price -->
                             <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-[#003d9e] focus-within:border-[#003d9e] h-[45px]">
                                <span class="pl-2 text-slate-400 text-sm"><i class="ti ti-tag"></i></span>
                                <input type="text" name="harga_dus" id="harga_dus" class="money w-full px-2 py-1 text-right text-sm border-0 focus:ring-0 placeholder-slate-300" placeholder="Harga / Dus">
                                <input type="hidden" id="harga_dus_produk">
                            </div>
                        </div>
                        
                         <!-- Button Add -->
                         <div class="w-auto h-[45px]">
                            <button type="button" id="tambahproduk" class="h-full px-4 bg-[#003d9e] hover:bg-blue-800 text-white text-sm font-bold rounded-lg shadow-sm transition-all active:scale-95 flex items-center gap-2">
                                <i class="ti ti-plus"></i>
                            </button>
                         </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-sm h-[calc(100vh-300px)] flex flex-col">
                 <div class="overflow-auto flex-1">
                    <table class="w-full text-sm text-left text-slate-600 relative" id="tabelproduk">
                        <thead class="text-xs text-white uppercase bg-[#003d9e] sticky top-0 z-10">
                             <tr>
                                <th class="px-4 py-2.5 font-semibold tracking-wider border-r border-blue-500 text-center">Kode</th>
                                <th class="px-4 py-2.5 font-semibold tracking-wider border-r border-blue-500 w-[35%]">Nama Barang</th>
                                <th class="px-4 py-2.5 font-semibold tracking-wider border-r border-blue-500 text-center">Dus</th>
                                <th class="px-4 py-2.5 font-semibold tracking-wider border-r border-blue-500 text-right">Harga / Dus</th>
                                <th class="px-4 py-2.5 font-semibold tracking-wider border-r border-blue-500 text-right">Subtotal</th>
                                <th class="px-4 py-2.5 font-semibold tracking-wider text-center">Aksi</th>
                             </tr>
                        </thead>
                        <tbody id="loadproduk" class="divide-y divide-slate-100 bg-white">
                             <!-- Rows by JS -->
                        </tbody>
                        <tfoot class="bg-slate-50 border-t border-slate-200 sticky bottom-0 z-10">
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-right font-bold text-slate-700 uppercase tracking-widest text-xs">SUBTOTAL</td>
                                <td class="px-4 py-3 text-right font-bold text-slate-800 text-base" id="subtotal">Rp 0</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                 </div>
            </div>

        </div>

        <!-- RIGHT COLUMN: Sidebar (Info + Total + Payment) - span 3 -->
        <div class="col-span-12 lg:col-span-3 space-y-4">
            
            <!-- Header Info -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
                <div class="space-y-4">
                    <!-- No Bukti -->
                    <div class="relative">
                        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">No. Bukti</label>
                        <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-[#003d9e] focus-within:border-[#003d9e]">
                            <span class="pl-3 text-slate-400"><i class="ti ti-barcode"></i></span>
                            <input type="text" name="no_bukti" id="no_bukti" class="w-full px-2 py-2.5 text-sm border-0 focus:ring-0 placeholder-slate-400" placeholder="No. Bukti">
                        </div>
                    </div>

                    <!-- Tanggal -->
                    <div class="relative">
                        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Tanggal</label>
                        <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-[#003d9e] focus-within:border-[#003d9e]">
                            <span class="pl-3 text-slate-400"><i class="ti ti-calendar"></i></span>
                            <input type="text" name="tanggal" id="tanggal" class="flatpickr-date w-full px-2 py-2.5 text-sm border-0 focus:ring-0 placeholder-slate-400" placeholder="Tanggal">
                        </div>
                    </div>

                    <!-- Supplier -->
                    <div class="relative">
                        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Supplier</label>
                        <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-[#003d9e] focus-within:border-[#003d9e] bg-slate-50 cursor-pointer hover:bg-slate-100 transition-colors">
                            <span class="pl-3 text-slate-400"><i class="ti ti-building-store"></i></span>
                            <input type="text" name="nama_supplier" id="nama_supplier" class="w-full px-2 py-2.5 text-sm border-0 focus:ring-0 bg-transparent placeholder-slate-400 cursor-pointer" placeholder="Pilih Supplier" readonly>
                        </div>
                        <input type="hidden" id="kode_supplier" name="kode_supplier">
                        <input type="hidden" id="kode_cabang_supplier" name="kode_cabang_supplier">
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
                 <h5 class="text-xs font-bold text-slate-700 uppercase mb-3">Pembayaran</h5>
                 
                 <div class="space-y-3">
                    <div class="relative">
                        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Jenis Transaksi</label>
                        <select name="jenis_transaksi" id="jenis_transaksi" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e]">
                            <option value="">Pilih Jenis Transaksi</option>
                            <option value="T">TUNAI</option>
                            <option value="K">KREDIT</option>
                        </select>
                    </div>

                    <div class="hidden">
                         <!-- Input Grand Total Real (Hidden Visual, used for post) -->
                         <input type="text" name="grandtotal" id="grandtotal" class="money">
                         <!-- Hidden Potongan Inputs -->
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
                    </div>


                    <div class="hidden" id="jenis_bayar_tunai">
                         <div class="relative mt-4">
                            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Jenis Bayar</label>
                            <select name="jenis_bayar" id="jenis_bayar" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e]">
                                <option value="">Pilih Jenis Bayar</option>
                                <option value="TN">CASH</option>
                                <option value="TR">TRANSFER</option>
                            </select>
                        </div>
                    </div>

                     <div class="hidden" id="titipan">
                        <div class="relative mt-4">
                            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Titipan</label>
                            <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden">
                                <span class="pl-3 text-slate-400"><i class="ti ti-moneybag"></i></span>
                                <input type="text" name="titipan" class="money w-full px-3 py-2 text-right text-sm border-0 focus:ring-0" placeholder="0">
                            </div>
                        </div>
                    </div>

                 </div>
                 
                 <button type="submit" id="btnSimpan" class="w-full px-4 py-3 text-sm font-bold text-white bg-[#003d9e] hover:bg-blue-800 rounded-lg shadow-lg shadow-blue-200 transition-all active:scale-95 flex items-center justify-center gap-2 mt-6">
                    <i class="ti ti-send"></i> Submit
                </button>
            </div>
        </div>

    </div>
</form>

<x-modal-form id="modal" size="modal-xl" show="loadmodal" title="" />
<x-modal-form id="modaleditProduk" size="" show="loadmodaleditProduk" title="" />
<!-- Modal Supplier preserved -->
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
            ajax: '{{ route("suppliermarketing.getjson") }}',
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
                url: `/suppliermarketing/${kode_supplier}/get-detail`,
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
            // Jika kode_supplier kosong, gunakan 'all' atau biarkan backend handle
            let supplierParam = kode_supplier || 'all';
            $.ajax({
                url: '{{ route("produk.getproduk") }}',

                type: 'GET',
                cache: false,
                success: function(response) {
                    buttonEnable();
                    $("#loadmodal").html(response);
                },
                error: function() {
                    buttonEnable();
                }
            });
        }
        //Pilih Produk
        $("#nama_produk").on('click', function(e) {
            e.preventDefault();
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text('Data Produk');
            buttonDisable();
            $.ajax({
                url: '{{ route("produk.getproduk") }}',
                type: 'GET',
                cache: false,
                success: function(response) {
                    buttonEnable();
                    $("#loadmodal").html(response);
                },
                error: function() {
                    buttonEnable();
                }
            });

        });

        $(document).on('click', '.pilihProduk', function(e) {
            e.preventDefault();
            let kode_produk = $(this).attr('kode_produk');
            let nama_supplier = $("#nama_supplier").val() || "";
            let nama_produk = $(this).attr('nama_produk');
            let isi_pcs_dus = $(this).attr('isi_pcs_dus');
            let isi_pcs_pack = $(this).attr('isi_pcs_pack');
            let harga_supplier = $(this).attr('harga_supplier');

            // Set nilai produk
            $("#kode_harga").val(""); 
            $("#kode_produk").val(kode_produk);
            $("#nama_produk").val(nama_produk);
            
            // Set default harga from supplier
            if(harga_supplier && harga_supplier != "" && harga_supplier != "0") {
                 let formattedHarga = new Intl.NumberFormat('id-ID').format(harga_supplier);
                 $("#harga_dus").val(formattedHarga);
                 $("#harga_dus_produk").val(harga_supplier);
            } else {
                $("#harga_dus").val("");
                $("#harga_dus_produk").val("");
            }

            $("#harga_pack").val("");
            $("#harga_pcs").val("");
            $("#harga_pack_produk").val("");
            $("#harga_pcs_produk").val("");

            // Set isi_pcs_dus dan isi_pcs_pack untuk perhitungan (hidden field)
            $("#isi_pcs_dus").val(isi_pcs_dus);
            $("#isi_pcs_pack").val(isi_pcs_pack);
            $("#kode_kategori_diskon").val(kode_kategori_diskon);

            //Disabled Harga
            if (isi_pcs_pack == "" || isi_pcs_pack === '0') {
                $("#harga_pack").prop('disabled', true);
                $("#jml_pack").prop('disabled', true);
            } else {
                $("#harga_pack").prop('disabled', false);
                $("#jml_pack").prop('disabled', false);
            }
            
            // Harga bisa diinput manual untuk semua supplier
            $("#harga_dus").prop('disabled', false);
            if (isi_pcs_pack == "" || isi_pcs_pack === '0') {
                $("#harga_pack").prop('disabled', true);
            } else {
                $("#harga_pack").prop('disabled', false);
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
            var kode_harga = $("#kode_harga").val() || "";
            var kode_produk = $("#kode_produk").val();
            var nama_produk = $("#nama_produk").val();
            var jml_dus = $("#jml_dus").val();
            var harga_dus = $("#harga_dus").val();
            var isi_pcs_dus = $("#isi_pcs_dus").val();
            var isi_pcs_pack = $("#isi_pcs_pack").val();
            var kode_kategori_diskon = $("#kode_kategori_diskon").val();

            var jmldus = jml_dus != "" ? parseInt(jml_dus.replace(/\./g, '')) : 0;
            var hargadus = harga_dus != "" ? parseInt(harga_dus.replace(/\./g, '')) : 0;

            // Hitung jumlah total dalam pcs
            var jumlah = jmldus * parseInt(isi_pcs_dus);

            // Gunakan kode_produk sebagai index jika kode_harga kosong
            let index = kode_harga || kode_produk;

            let subtotal = (parseInt(jmldus) * parseInt(hargadus));


            if (kode_produk == "") {

                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih dulu Produk !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#nama_produk").focus();
                    },
                });
            } else if (jmldus == 0 || jml_dus == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Dus Tidak Boleh Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#jml_dus").focus();
                    },
                });
            } else if (hargadus == 0 || harga_dus == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Harga Dus Tidak Boleh Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#harga_dus").focus();
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
                    <tr id="index_${index}" class="hover:bg-slate-50 transition-colors border-b border-slate-100">
                        <td class="px-4 py-2.5 text-slate-700 font-medium">
                            <input type="hidden" name="kode_produk[]" value="${kode_produk}" class="kode_produk"/>
                            <input type="hidden" name="status_promosi_produk[]" value="0"/>
                            <input type="hidden" name="kode_kategori_diskon[]" class="kode_kategori_diskon" value="${kode_kategori_diskon}"/>
                            <input type="hidden" name="jumlah_produk[]" value="${jumlah}"/>
                            <input type="hidden" name="isi_pcs_dus_produk[]" value="${isi_pcs_dus}"/>
                            <input type="hidden" name="isi_pcs_pack_produk[]" value="${isi_pcs_pack}"/>
                            ${kode_produk}
                        </td>
                        <td class="px-4 py-2.5 text-slate-700 font-medium text-wrap">${nama_produk}</td>
                        <td class="px-4 py-2.5 text-center text-slate-700 font-medium">
                           ${jmldus}
                        </td>
                        <td class="px-4 py-2.5 text-right text-slate-700 font-medium">
                           ${convertToRupiah(hargadus)}
                           <input type="hidden" name="harga_dus_produk[]" value="${hargadus}"/>
                        </td>
                        <td class="px-4 py-2.5 text-right text-slate-800 font-bold">
                            ${convertToRupiah(subtotal)}
                            <input type="hidden" name="subtotal[]" class="subtotal" value="${subtotal}"/>
                        </td>
                        <td class="px-4 py-2.5 text-center">
                           <div class="flex items-center justify-center gap-2">
                                 <a href="#" key="${index}" class="edit flex items-center justify-center w-8 h-8 rounded-full bg-green-50 text-green-600 hover:bg-green-100 transition-colors"><i class="ti ti-edit"></i></a>
                                 <a href="#" key="${index}" class="delete flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-600 hover:bg-red-100 transition-colors"><i class="ti ti-trash"></i></a>
                           </div>
                        </td>
                    </tr>
                `;

                //append to table
                $('#loadproduk').append(produk);
                // Reset form
                $("#kode_harga").val("");
                $("#kode_produk").val("");
                $("#nama_produk").val("");
                $("#jml_dus").val("");
                $("#harga_dus").val("");
                $("#harga_dus_produk").val("");
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
            let kode_harga = $(this).find("#kode_harga").val() || "";
            let kode_produk = $(this).find("#kode_produk").val();
            let nama_produk = $(this).find("#kode_produk").val() ? $(this).find("#kode_produk").find(':selected').text() : $(this).find("#nama_produk").val();
            let jml_dus = $(this).find("#jml_dus").val();
            let harga_dus = $(this).find("#harga_dus").val();
            let isi_pcs_dus = $(this).find("#isi_pcs_dus").val();
            let isi_pcs_pack = $(this).find("#isi_pcs_pack").val();
            let kode_kategori_diskon = $(this).find("#kode_kategori_diskon").val();
            let index_old = $(this).find("#index_old").val();

            let jmldus = jml_dus != "" ? parseInt(jml_dus.replace(/\./g, '')) : 0;
            let hargadus = harga_dus != "" ? parseInt(harga_dus.replace(/\./g, '')) : 0;

            // Hitung jumlah total dalam pcs
            let jumlah = jmldus * parseInt(isi_pcs_dus);

            // Gunakan kode_produk sebagai index jika kode_harga kosong
            let index = kode_harga || kode_produk;
            let subtotal = (parseInt(jmldus) * parseInt(hargadus));

            let newRow = `
                    <tr id="index_${index}" class="hover:bg-slate-50 transition-colors border-b border-slate-100">
                        <td class="px-4 py-2.5 text-slate-700 font-medium">
                            <input type="hidden" name="kode_produk[]" value="${kode_produk}" class="kode_produk"/>
                            <input type="hidden" name="status_promosi_produk[]" value="0"/>
                            <input type="hidden" name="kode_kategori_diskon[]" class="kode_kategori_diskon" value="${kode_kategori_diskon}"/>
                            <input type="hidden" name="jumlah_produk[]" value="${jumlah}"/>
                            <input type="hidden" name="isi_pcs_dus_produk[]" value="${isi_pcs_dus}"/>
                            <input type="hidden" name="isi_pcs_pack_produk[]" value="${isi_pcs_pack}"/>
                            ${kode_produk}
                        </td>
                        <td class="px-4 py-2.5 text-slate-700 font-medium text-wrap">${nama_produk}</td>
                        <td class="px-4 py-2.5 text-center text-slate-700 font-medium">
                           ${jmldus}
                        </td>
                        <td class="px-4 py-2.5 text-right text-slate-700 font-medium">
                           ${convertToRupiah(hargadus)}
                           <input type="hidden" name="harga_dus_produk[]" value="${hargadus}"/>
                        </td>
                        <td class="px-4 py-2.5 text-right text-slate-800 font-bold">
                            <input type="hidden" name="subtotal[]" class="subtotal" value="${subtotal}"/>
                            ${convertToRupiah(subtotal)}
                        </td>
                        <td class="px-4 py-2.5 text-center">
                           <div class="flex items-center justify-center gap-2">
                                 <a href="#" key="${index}" class="edit flex items-center justify-center w-8 h-8 rounded-full bg-green-50 text-green-600 hover:bg-green-100 transition-colors"><i class="ti ti-edit"></i></a>
                                 <a href="#" key="${index}" class="delete flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-600 hover:bg-red-100 transition-colors"><i class="ti ti-trash"></i></a>
                           </div>
                        </td>
                    </tr>
                `;
            if (kode_produk == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih dulu Produk !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#kode_produk").focus();
                    },
                });
            } else if (jmldus == 0 || jml_dus == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Dus Tidak Boleh Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#jml_dus").focus();
                    },
                });
            } else if (hargadus == 0 || harga_dus == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Harga Dus Tidak Boleh Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#harga_dus").focus();
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

            } else {
                $("#jenis_bayar_tunai").hide();

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
            const jenis_transaksi = $("#jenis_transaksi").val();
            const jenis_bayar = $("#jenis_bayar").val();
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
            } else {
                buttonDisable();
            }
        });

    });
</script>
@endpush
