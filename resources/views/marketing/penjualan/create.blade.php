@extends('layouts.app')
@section('titlepage', 'Input Penjualan')
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
    <span class="text-muted">Penjualan</span> / <span>Input Penjualan</span>
@endsection
<form action="{{ route('penjualan.store') }}" method="POST" id="formPenjualan" class="mt-4 font-public-sans">
    @csrf
    <input type="hidden" name="limit_pelanggan" id="limit_pelanggan">
    <input type="hidden" name="sisa_piutang" id="sisa_piutang">
    <input type="hidden" name="siklus_pembayaran" id="siklus_pembayaran">
    <input type="hidden" name="max_kredit" id="max_kredit">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- BLOCK 1: Header Info (Top Left - 4 Cols) -->
        <div class="col-span-12 lg:col-span-4">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 h-full">
                <div class="space-y-4">
                    <!-- No Faktur -->
                    <div class="relative">
                        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">No. Faktur</label>
                        <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-[#003d9e] focus-within:border-[#003d9e]">
                            <span class="pl-3 text-slate-400"><i class="ti ti-barcode"></i></span>
                            <input type="text" name="no_faktur" id="no_faktur" class="w-full px-2 py-2.5 text-sm border-0 focus:ring-0 placeholder-slate-400" placeholder="No. Faktur">
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

                    <!-- Pelanggan -->
                    <div class="relative">
                        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Pelanggan</label>
                        <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-[#003d9e] focus-within:border-[#003d9e] bg-slate-50 cursor-pointer hover:bg-slate-100 transition-colors">
                            <span class="pl-3 text-slate-400"><i class="ti ti-user"></i></span>
                            <input type="text" name="nama_pelanggan" id="nama_pelanggan" class="w-full px-2 py-2.5 text-sm border-0 focus:ring-0 bg-transparent placeholder-slate-400 cursor-pointer" placeholder="Pilih Pelanggan" readonly>
                        </div>
                        <input type="hidden" id="kode_pelanggan" name="kode_pelanggan">
                        <input type="hidden" id="kode_cabang_pelanggan" name="kode_cabang_pelanggan">
                    </div>

                    <!-- Salesman -->
                    <div class="relative">
                        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Salesman</label>
                        <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden bg-slate-50">
                            <span class="pl-3 text-slate-400"><i class="ti ti-user-check"></i></span>
                            <input type="text" name="nama_salesman" id="nama_salesman" class="w-full px-2 py-2.5 text-sm border-0 focus:ring-0 bg-transparent placeholder-slate-400" placeholder="Salesman" readonly>
                        </div>
                        <input type="hidden" name="kode_salesman" id="kode_salesman">
                    </div>

                    <!-- Keterangan -->
                    <div class="relative">
                        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="1" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] placeholder-slate-400 resize-none" placeholder="Keterangan Transaksi"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- BLOCK 2: Grand Total & Status Pelanggan (Top Right - 8 Cols) -->
        <div class="col-span-12 lg:col-span-8 flex flex-col gap-4">
             <!-- Top Half: Grand Total -->
             <div class="bg-[#003d9e] rounded-xl shadow-lg p-4 relative overflow-hidden text-center group flex-1 flex flex-col justify-center">
                <div class="absolute inset-0 bg-white/10 group-hover:bg-white/20 transition-colors"></div>
                <div class="absolute -right-6 -top-6 text-white/10 rotate-12">
                    <i class="ti ti-shopping-cart text-[10rem]"></i>
                </div>
                 <p class="text-blue-100 text-xs uppercase tracking-wider mb-1 relative z-10">Total Bayar</p>
                 <h1 class="text-5xl md:text-6xl font-black text-white tracking-tight relative z-10" id="grandtotal_text">0</h1>
            </div>

            <!-- Bottom Half: Status Pelanggan (Compact Horizontal) -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden flex-1 flex flex-row">
                <!-- Foto & Alamat (Left) -->
                <div class="w-1/3 relative bg-slate-900 overflow-hidden group">
                    <!-- Full Background Image -->
                    <img src="{{ asset('assets/img/elements/2.jpg') }}" alt="" class="absolute inset-0 w-full h-full object-cover opacity-90 transition-transform duration-700 group-hover:scale-110" id="foto">
                    
                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent"></div>

                    <!-- Content -->
                    <div class="absolute bottom-0 left-0 w-full p-4">
                        <p class="text-[10px] uppercase font-bold text-blue-400 mb-1 flex items-center gap-1.5"><i class="ti ti-map-pin"></i> Lokasi</p>
                        <p class="text-xs font-medium text-white line-clamp-2 leading-relaxed" id="alamat_pelanggan">-</p>
                    </div>
                     
                    <!-- Hidden Data -->
                    <div class="hidden">
                        <span id="no_hp_pelanggan"></span>
                        <span id="latitude"></span>
                        <span id="longitude"></span>
                        <span id="saldo_voucher_text"></span>
                    </div>
                </div>

                <!-- Stats (Right) -->
                 <div class="w-2/3 p-4 flex flex-col justify-center">
                    <div class="grid grid-cols-2 gap-x-4 gap-y-3">
                         <div>
                            <p class="text-[10px] font-bold uppercase text-slate-400">Faktur Kredit</p>
                            <p class="text-base font-bold text-slate-700"><span id="jmlfaktur_kredit">0</span> Fkt</p>
                        </div>
                        <div>
                             <p class="text-[10px] font-bold uppercase text-slate-400">Jatuh Tempo</p>
                             <p class="text-base font-bold text-slate-700" id="jatuh_tempo">-</p>
                        </div>
                    </div>
                 </div>
            </div>
        </div>
        
        <!-- BLOCK 3: Workspace (Bottom - Full Width) -->
        <div class="col-span-12 space-y-6">
            
            <!-- Input Produk -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-3 space-y-2">
                
                <!-- Row 1: Inputs -->
                <div class="flex flex-col lg:flex-row gap-2 items-start">
                    <!-- Product Selector (Left - Grow) -->
                    <div class="flex-1 w-full lg:w-auto">
                        <div class="relative h-full">
                            <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-[#003d9e] focus-within:border-[#003d9e] h-[78px]"> <!-- Reduced height to match tighter inputs -->
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
                        <div class="w-[130px] space-y-2">
                             <!-- Qty -->
                            <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-[#003d9e] focus-within:border-[#003d9e] h-[35px]">
                                <span class="pl-2 text-slate-400 text-sm"><i class="ti ti-box"></i></span>
                                <input type="text" name="jml_dus" id="jml_dus" class="money w-full px-2 py-1 text-right text-sm border-0 focus:ring-0 placeholder-slate-300" placeholder="Dus">
                            </div>
                            <!-- Price -->
                             <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-[#003d9e] focus-within:border-[#003d9e] h-[35px]">
                                <span class="pl-2 text-slate-400 text-sm"><i class="ti ti-tag"></i></span>
                                <input type="text" name="harga_dus" id="harga_dus" class="money w-full px-2 py-1 text-right text-xs border-0 focus:ring-0 placeholder-slate-300 bg-slate-50" placeholder="Harga / Dus" readonly>
                                <input type="hidden" id="harga_dus_produk">
                            </div>
                        </div>

                         <!-- Pack -->
                        <div class="w-[130px] space-y-2">
                             <!-- Qty -->
                            <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-[#003d9e] focus-within:border-[#003d9e] h-[35px]">
                                <span class="pl-2 text-slate-400 text-sm"><i class="ti ti-box"></i></span>
                                <input type="text" name="jml_pack" id="jml_pack" class="money w-full px-2 py-1 text-right text-sm border-0 focus:ring-0 placeholder-slate-300" placeholder="Pack">
                            </div>
                            <!-- Price -->
                             <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-[#003d9e] focus-within:border-[#003d9e] h-[35px]">
                                <span class="pl-2 text-slate-400 text-sm"><i class="ti ti-tag"></i></span>
                                <input type="text" name="harga_pack" id="harga_pack" class="money w-full px-2 py-1 text-right text-xs border-0 focus:ring-0 placeholder-slate-300 bg-slate-50" placeholder="Harga / Pack" readonly>
                                <input type="hidden" id="harga_pack_produk">
                            </div>
                        </div>

                         <!-- Pcs -->
                        <div class="w-[130px] space-y-2">
                             <!-- Qty -->
                            <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-[#003d9e] focus-within:border-[#003d9e] h-[35px]">
                                <span class="pl-2 text-slate-400 text-sm"><i class="ti ti-box"></i></span>
                                <input type="text" name="jml_pcs" id="jml_pcs" class="money w-full px-2 py-1 text-right text-sm border-0 focus:ring-0 placeholder-slate-300" placeholder="Pcs">
                            </div>
                            <!-- Price -->
                             <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-[#003d9e] focus-within:border-[#003d9e] h-[35px]">
                                <span class="pl-2 text-slate-400 text-sm"><i class="ti ti-tag"></i></span>
                                <input type="text" name="harga_pcs" id="harga_pcs" class="money w-full px-2 py-1 text-right text-xs border-0 focus:ring-0 placeholder-slate-300 bg-slate-50" placeholder="Harga / Pcs" readonly>
                                <input type="hidden" id="harga_pcs_produk">
                            </div>
                        </div>

                        <!-- Promosi Checkbox -->
                        <div class="flex items-center h-[78px] pl-2">
                             <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input class="form-checkbox h-4 w-4 text-[#003d9e] rounded border-slate-300 focus:ring-[#003d9e] status_promosi" name="status_promosi" type="checkbox" value="1" id="status_promosi">
                                <span class="text-xs font-bold text-slate-600">Promosi</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Row 2: Button -->
                <button type="button" id="tambahproduk" class="w-full bg-[#003d9e] hover:bg-blue-800 text-white text-sm font-bold py-2 px-4 rounded-lg shadow-sm transition-all active:scale-95 flex items-center justify-center gap-2">
                    <i class="ti ti-plus"></i> Tambah Produk
                </button>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-sm">
                 <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-600" id="tabelproduk">
                        <thead class="text-xs text-white uppercase bg-[#003d9e]">
                             <tr>
                                <th rowspan="2" class="px-3 py-3 font-medium border-r border-blue-500">Kode</th>
                                <th rowspan="2" class="px-3 py-3 font-medium border-r border-blue-500 w-[30%]">Nama Barang</th>
                                <th colspan="6" class="px-3 py-2 text-center border-b border-r border-blue-500">Qty & Harga</th>
                                <th rowspan="2" class="px-3 py-3 font-medium border-r border-blue-500 text-right">Subtotal</th>
                                <th rowspan="2" class="px-3 py-3 text-center">Aksi</th>
                             </tr>
                             <tr>
                                <th class="px-3 py-2 text-center font-normal border-r border-blue-500 bg-[#003d9e]">Dus</th>
                                <th class="px-3 py-2 text-center font-normal border-r border-blue-500 bg-[#003d9e]">Harga</th>
                                <th class="px-3 py-2 text-center font-normal border-r border-blue-500 bg-[#003d9e]">Pack</th>
                                <th class="px-3 py-2 text-center font-normal border-r border-blue-500 bg-[#003d9e]">Harga</th>
                                <th class="px-3 py-2 text-center font-normal border-r border-blue-500 bg-[#003d9e]">Pcs</th>
                                <th class="px-3 py-2 text-center font-normal border-r border-blue-500 bg-[#003d9e]">Harga</th>
                            </tr>
                        </thead>
                        <tbody id="loadproduk" class="divide-y divide-slate-100 bg-white">
                             <!-- Rows by JS -->
                        </tbody>
                        <tfoot class="bg-slate-50 border-t border-slate-200">
                            <tr>
                                <td colspan="8" class="px-4 py-3 text-right font-bold text-slate-700">SUBTOTAL</td>
                                <td class="px-4 py-3 text-right font-bold text-slate-800" id="subtotal">0</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                 </div>
            </div>

        </div>

        <!-- BLOCK 4: Financial Summary (Bottom - 4 Cols) -->
        <div class="col-span-12">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Col 1: Potongan -->
                 <div class="bg-white border border-slate-200 rounded-lg overflow-hidden h-full">
                    <div class="p-3 bg-slate-50 border-b border-slate-100">
                        <h6 class="text-xs font-bold uppercase text-slate-500">Potongan</h6>
                    </div>
                    <div class="p-3 space-y-2">
                         @foreach(['aida'=>'AIDA', 'swan'=>'SWAN', 'stick'=>'STICK', 'sambal'=>'SAMBAL'] as $key => $label)
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-medium text-slate-600 w-16">{{ $label }}</span>
                            <input type="text" name="potongan_{{ $key }}" id="potongan_{{ $key }}" class="money w-full px-2 py-1 text-right border-b border-dashed border-slate-300 text-xs focus:outline-none focus:border-blue-500 bg-transparent" readonly placeholder="0">
                        </div>
                        @endforeach
                    </div>
                 </div>

                 <!-- Col 2: Potongan Istimewa -->
                <div class="bg-white border border-slate-200 rounded-lg overflow-hidden h-full">
                    <div class="p-3 bg-slate-50 border-b border-slate-100">
                        <h6 class="text-xs font-bold uppercase text-slate-500">Potongan Istimewa</h6>
                    </div>
                     <div class="p-3 space-y-2">
                        @foreach(['aida'=>'AIDA', 'swan'=>'SWAN', 'stick'=>'STICK'] as $key => $label)
                        <div class="relative">
                            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">{{ $label }}</label>
                            <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-[#003d9e] focus-within:border-[#003d9e]">
                                <span class="pl-3 text-slate-400"><i class="ti ti-discount-2"></i></span>
                                <input type="text" name="potis_{{ $key }}" id="potis_{{ $key }}" class="money w-full px-2 py-2.5 text-right text-sm border-0 focus:ring-0 placeholder-slate-400 font-bold" placeholder="0">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Col 3: Penyesuaian -->
                 <div class="bg-white border border-slate-200 rounded-lg overflow-hidden h-full">
                    <div class="p-3 bg-slate-50 border-b border-slate-100">
                        <h6 class="text-xs font-bold uppercase text-slate-500">Penyesuaian</h6>
                    </div>
                    <div class="p-3 space-y-2">
                         @foreach(['aida'=>'AIDA', 'swan'=>'SWAN', 'stick'=>'STICK'] as $key => $label)
                        <div class="relative">
                            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">{{ $label }}</label>
                            <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-[#003d9e] focus-within:border-[#003d9e]">
                                <span class="pl-3 text-slate-400"><i class="ti ti-adjustments-alt"></i></span>
                                <input type="text" name="peny_{{ $key }}" id="peny_{{ $key }}" class="money w-full px-2 py-2.5 text-right text-sm border-0 focus:ring-0 placeholder-slate-400 font-bold" placeholder="0">
                            </div>
                        </div>
                        @endforeach
                    </div>
                 </div>

                <!-- Col 4: Pembayaran -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 h-full flex flex-col justify-between">
                     <div>
                         <h5 class="text-xs font-bold text-slate-700 uppercase mb-3">Pembayaran</h5>
                         
                         <div class="space-y-3">
                            <div>
                                <select name="jenis_transaksi" id="jenis_transaksi" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e]">
                                    <option value="">Jenis Transaksi</option>
                                    <option value="T">TUNAI</option>
                                    <option value="K">KREDIT</option>
                                </select>
                            </div>

                            <div class="hidden" id="jenis_bayar_tunai">
                                 <select name="jenis_bayar" id="jenis_bayar" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e]">
                                    <option value="">Metode Bayar</option>
                                    <option value="TN">CASH</option>
                                    <option value="TR">TRANSFER</option>
                                </select>
                            </div>

                            <!-- Input Grand Total Real (Hidden Visual, used for post) -->
                            <input type="hidden" name="grandtotal" id="grandtotal" class="money">

                             <div class="hidden" id="titipan">
                                <div class="relative">
                                    <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Titipan</label>
                                    <input type="text" name="titipan" class="money w-full px-3 py-2 text-right border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e]" placeholder="0">
                                </div>
                            </div>

                            <div class="hidden" id="voucher_tunai">
                                 <div class="relative">
                                    <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Voucher</label>
                                    <input type="text" name="voucher" class="money w-full px-3 py-2 text-right border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e]" placeholder="0">
                                </div>
                            </div>
                         </div>
                     </div>
                     
                     <button type="submit" id="btnSimpan" class="w-full px-4 py-3 text-sm font-bold text-white bg-[#003d9e] hover:bg-blue-800 rounded-lg shadow-lg shadow-blue-200 transition-all active:scale-95 flex items-center justify-center gap-2 mt-4">
                        <i class="ti ti-send"></i> Simpan
                    </button>
                </div>
            </div>
        </div>

    </div>
</form>

<x-modal-form id="modal" size="modal-xl" show="loadmodal" title="" />
<x-modal-form id="modaleditProduk" size="" show="loadmodaleditProduk" title="" />
<div class="modal fade" id="modalPelanggan" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel18">Data Pelanggan</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table" id="tabelpelanggan" width="100%">
                        <thead class="table-dark">
                            <tr>
                                <th>No.</th>
                                <th>Kode</th>
                                <th>Nama Pelanggan</th>
                                <th>Salesman</th>
                                <th>Wilayah</th>
                                <th>Status</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot class="table-dark">
                            <tr>
                                <th>No.</th>
                                <th>Kode</th>
                                <th>Nama Pelanggan</th>
                                <th>Salesman</th>
                                <th>Wilayah</th>
                                <th>Status</th>
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
        let kode_cabang_pelanggan = '';
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
        $('#tabelpelanggan').DataTable({
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
                    data: 'kode_pelanggan',
                    name: 'kode_pelanggan',
                    orderable: false,
                    searchable: false,
                    width: '10%'
                },
                {
                    data: 'nama_pelanggan',
                    name: 'nama_pelanggan',
                    orderable: false,
                    searchable: true,
                    width: '30%'
                },
                {
                    data: 'nama_salesman',
                    name: 'nama_salesman',
                    orderable: false,
                    searchable: true,
                    width: '20%'
                },

                {
                    data: 'nama_wilayah',
                    name: 'nama_wilayah',
                    orderable: false,
                    searchable: false,
                    width: '30%'
                },
                {
                    data: 'status_pelanggan',
                    name: 'status_pelanggan',
                    orderable: false,
                    searchable: false,
                    width: '30%'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    width: '5%'
                }
            ],
            initComplete: function() {
                this.api().columns().every(function(index) {
                    if (index == 2 || index ==
                        3
                    ) { // Only add search inputs for nama_pelanggan and nama_salesman columns
                        var column = this;
                        var input = document.createElement("input");
                        $(input).appendTo($(column.header()))
                            .on('keyup change', function() {
                                column.search($(this).val(), false, false, true).draw();
                            })
                            .addClass('form-control form-control-sm mt-2');
                    }
                });
            },
            rowCallback: function(row, data, index) {
                if (data.status_pelanggan == "NonAktif") {
                    $("td", row).addClass("bg-danger text-white");
                }
            }
        });

        $("#nama_pelanggan").on('click focus', function(e) {
            e.preventDefault();
            $("#modalPelanggan").modal("show");
        });



        //Cek file Foto Pelanggan
        function checkFileExistence(fileFoto) {
            var xhr = new XMLHttpRequest();
            var filePath = '/pelanggan/' + fileFoto;
            var foto = "{{ url(Storage::url('pelanggan')) }}/" + fileFoto;
            var fotoDefault = "{{ asset('assets/img/elements/2.jpg') }}";
            console.log(foto);
            xhr.open('GET', '/pelanggan/cekfotopelanggan?file=' + filePath, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.exists) {
                            console.log('File exists');
                            $("#foto").attr("src", foto);
                        } else {
                            console.log('File does not exist');
                            $("#foto").attr("src", fotoDefault);
                        }
                    } else {
                        console.error('Error checking file existence:', xhr.statusText);
                    }
                }
            };
            xhr.send();
        }

        //GetPiutang

        function getPiutang(kode_pelanggan) {
            buttonDisable();
            $.ajax({
                url: `/pelanggan/${kode_pelanggan}/getPiutangpelanggan`,
                type: 'GET',
                cache: false,
                success: function(response) {
                    console.log(response);
                    $("#sisa_piutang_text").text(convertToRupiah(response.data));
                    $("#sisa_piutang").val(response.data);
                    buttonEnable();
                }
            });
        }


        function getFakturkredit(kode_pelanggan) {
            buttonDisable();
            $.ajax({
                url: `/pelanggan/${kode_pelanggan}/getFakturkredit`,
                type: 'GET',
                cache: false,
                success: function(response) {
                    console.log(response);
                    const unpaid_faktur = response.data.unpaid_faktur;
                    const max_faktur = response.data.jml_faktur;
                    const siklus_pembayaran = response.data.siklus_pembayaran;
                    jmlfakturbelumlunas = unpaid_faktur;
                    jmlfakturmax = max_faktur;

                    console.log(jmlfakturbelumlunas);
                    console.log(jmlfakturmax);

                    // if (unpaid_faktur >= max_faktur && siklus_pembayaran === '0') {
                    //     SwalWarning("nama_pelanggan", "Melebihi Maksimal Faktur Kredit");
                    //     $("#no_faktur").val("");
                    //     $("#tanggal").val("");
                    //     $("#nama_pelanggan").val("");
                    //     $("#kode_pelanggan").val("");
                    //     $("#kode_salesman").val("");
                    //     $("#nama_salesman").val("");
                    //     $('#latitude').text("");
                    //     $('#longitude').text("");
                    //     $('#no_hp_pelanggan').text("");
                    //     $('#limit_pelanggan_text').text("");
                    //     $('#limit_pelanggan').val("");
                    //     $('#alamat_pelanggan').text("");
                    //     $('#sisa_piutang_text').text("");
                    //     $("#jmlfaktur_kredit").text("");
                    //     let fileFoto = "notfound.jpg";
                    //     checkFileExistence(fileFoto);
                    //     //Data Salesman
                    // } else {
                    //     $("#jmlfaktur_kredit").text(response.data.unpaid_faktur);
                    //     $("#siklus_pembayaran").val(response.data.siklus_pembayaran);
                    //     $("#max_kredit").val(response.data.jml_faktur);
                    // }

                    $("#jmlfaktur_kredit").text(response.data.unpaid_faktur);
                    $("#siklus_pembayaran").val(response.data.siklus_pembayaran);
                    $("#max_kredit").val(response.data.jml_faktur);
                    buttonEnable();
                }
            });
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
        //Get Pelanggan
        function getPelanggan(kode_pelanggan) {
            buttonDisable();
            $.ajax({
                url: `/pelanggan/${kode_pelanggan}/getPelanggan`,
                type: "GET",
                cache: false,
                success: function(response) {
                    //fill data to form
                    const status_aktif_pelanggan = response.data.status_aktif_pelanggan;
                    if (status_aktif_pelanggan === '0') {
                        Swal.fire({
                            title: "Oops!",
                            text: "Pelanggan Tidak Dapat Bertransaksi, Silahkan Hubungi Admin Untuk Mengaktifkan Pelanggan !",
                            icon: "warning",
                            showConfirmButton: true,
                        });
                    } else {
                        $('#kode_pelanggan').val(response.data.kode_pelanggan);
                        kode_pel = response.data.kode_pelanggan;
                        $('#kode_cabang_pelanggan').val(response.data.kode_cabang);
                        kode_cabang_pelanggan = response.data.kode_cabang;
                        //alert(kode_cabang_pelanggan);
                        $('#nama_pelanggan').val(response.data.nama_pelanggan);
                        $('#latitude').text(response.data.latitude);
                        $('#longitude').text(response.data.longitude);
                        $('#no_hp_pelanggan').text(response.data.no_hp_pelanggan);
                        $('#limit_pelanggan_text').text(convertToRupiah(response.data
                            .limit_pelanggan));
                        $('#limit_pelanggan').val(response.data.limit_pelanggan);
                        $('#alamat_pelanggan').text(response.data.alamat_pelanggan);
                        let fileFoto = response.data.foto;
                        checkFileExistence(fileFoto);
                        //Data Salesman
                        $('#kode_salesman').val(response.data.kode_salesman);
                        $('#nama_salesman').val(response.data.nama_salesman);
                        $("#saldo_voucher_text").text(response.saldo_voucher);
                        saldo_voucher = response.saldo_voucher
                        //Get Piutang
                        getPiutang(kode_pelanggan);
                        //Get FaktuR Kredit
                        getFakturkredit(kode_pelanggan);
                        generatenofaktur();
                        //open modal
                        $('#modalPelanggan').modal('hide');
                        buttonEnable();
                    }

                }
            });
        }
        //Pilih Pelanggan
        $('#tabelpelanggan tbody').on('click', '.pilihpelanggan', function(e) {
            e.preventDefault();
            let kode_pelanggan = $(this).attr('kode_pelanggan');
            getPelanggan(kode_pelanggan);
            $("#loadproduk").html('');
            $("#potongan_swan").val(0);
            $("#potongan_aida").val(0);
            $("#potongan_sp").val(0);
            $("#potongan_stick").val(0);
            $("#potongan_sambal").val(0);
            loadsubtotal();

        });


        //GetProduk
        function getHarga(kode_pelanggan) {
            buttonDisable();
            $.ajax({
                url: `/harga/${kode_pelanggan}/gethargabypelanggan`,
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
            let kode_pelanggan = $("#kode_pelanggan").val();
            if (kode_pelanggan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih dulu Pelanggan !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#nama_pelanggan").focus();
                    },
                });
            } else {
                $("#modal").modal("show");
                $("#modal").find(".modal-title").text('Data Produk');
                getHarga(kode_pelanggan);
            }
        });

        $(document).on('click', '.pilihProduk', function(e) {
            e.preventDefault();
            let kode_harga = $(this).attr('kode_harga');
            let kode_produk = $(this).attr('kode_produk');
            let nama_pelanggan = $("#nama_pelanggan").val();
            let nama_produk = $(this).attr('nama_produk');
            let harga_dus = $(this).attr('harga_dus');
            let harga_pack = $(this).attr('harga_pack');
            let harga_pcs = $(this).attr('harga_pcs');

            let harga_dus_produk = $(this).attr('harga_dus');
            let harga_pack_produk = $(this).attr('harga_pack');
            let harga_pcs_produk = $(this).attr('harga_pcs');

            let isi_pcs_dus = $(this).attr('isi_pcs_dus');
            let isi_pcs_pack = $(this).attr('isi_pcs_pack');

            let kode_kategori_diskon = $(this).attr('kode_kategori_diskon');
            if ($('#status_promosi').is(":checked")) {
                harga_dus = 0;
                harga_pack = 0;
                harga_pcs = 0;
            }
            $("#kode_harga").val(kode_harga);
            $("#kode_produk").val(kode_produk);
            $("#nama_produk").val(nama_produk);
            $("#harga_dus").val(harga_dus);
            $("#harga_pack").val(harga_pack);
            $("#harga_pcs").val(harga_pcs);

            $("#harga_dus_produk").val(harga_dus_produk);
            $("#harga_pack_produk").val(harga_pack_produk);
            $("#harga_pcs_produk").val(harga_pcs_produk);


            $("#isi_pcs_dus").val(isi_pcs_dus);
            $("#isi_pcs_pack").val(isi_pcs_pack);

            $("#kode_kategori_diskon").val(kode_kategori_diskon);


            //Disabled Harga
            if (isi_pcs_pack == "" || isi_pcs_pack === '0') {
                $("#jml_pack").prop('disabled', true);
            } else {
                $("#jml_pack").prop('disabled', false);
            }
            if (nama_pelanggan.includes('KPBN') || nama_pelanggan.includes('RSB') || kode_cabang_user ==
                'PST') {
                $("#harga_dus").prop('disabled', false);
                if (isi_pcs_pack == "" || isi_pcs_pack === '0') {
                    $("#harga_pack").prop('disabled', true);
                } else {
                    $("#harga_pack").prop('disabled', false);
                }
                $("#harga_pcs").prop('disabled', false);
            } else {
                $("#harga_dus").prop('disabled', true);
                $("#harga_pack").prop('disabled', true);
                $("#harga_pcs").prop('disabled', true);
            }

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
            var kode_harga = $("#kode_harga").val();
            var kode_produk = $("#kode_produk").val();
            var nama_produk = $("#nama_produk").val();
            var jml_dus = $("#jml_dus").val();
            var jml_pack = $("#jml_pack").val();
            var jml_pcs = $("#jml_pcs").val();
            var harga_dus = $("#harga_dus").val();
            var harga_pack = $("#harga_pack").val();
            var harga_pcs = $("#harga_pcs").val();
            var isi_pcs_dus = $("#isi_pcs_dus").val();
            var isi_pcs_pack = $("#isi_pcs_pack").val();
            var kode_kategori_diskon = $("#kode_kategori_diskon").val();


            if ($('#status_promosi').is(":checked")) {
                var status_promosi = $("#status_promosi").val();
            } else {
                var status_promosi = 0;
            }

            var jmldus = jml_dus != "" ? parseInt(jml_dus.replace(/\./g, '')) : 0;
            var jmlpack = jml_pack != "" ? parseInt(jml_pack.replace(/\./g, '')) : 0;
            var jmlpcs = jml_pcs != "" ? parseInt(jml_pcs.replace(/\./g, '')) : 0;

            var hargadus = harga_dus != "" ? parseInt(harga_dus.replace(/\./g, '')) : 0;
            var hargapack = harga_pack != "" ? parseInt(harga_pack.replace(/\./g, '')) : 0;
            var hargapcs = harga_pcs != "" ? parseInt(harga_pcs.replace(/\./g, '')) : 0;

            var jumlah = (jmldus * parseInt(isi_pcs_dus)) + (jmlpack * (parseInt(isi_pcs_pack))) + jmlpcs;

            let data = convertoduspackpcs(isi_pcs_dus, isi_pcs_pack, jumlah);
            let dus = data.dus;
            let pack = data.pack;
            let pcs = data.pcs;

            let index = kode_harga + status_promosi;

            let bgcolor = "";
            if (status_promosi == '1') {
                bgcolor = "bg-warning text-white";
                var hargadus = 0;
                var hargapack = 0;
                var hargapcs = 0;
                var harga_dus = 0;
                var harga_pack = 0;
                var harga_pcs = 0;
            } else {
                bgcolor = bgcolor;
            }
            let subtotal = (parseInt(dus) * parseInt(hargadus)) + (parseInt(pack) * parseInt(hargapack)) + (
                parseInt(pcs) * parseInt(hargapcs));


            if (kode_harga == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih dulu Produk !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#nama_produk").focus();
                    },
                });
            } else if (jumlah == "" || jumlah === '0') {
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
                    <tr id="index_${index}" class="${bgcolor}">
                        <td class="px-4 py-3">
                            <input type="hidden" name="kode_harga_produk[]" value="${kode_harga}" class="kode_harga"/>
                            <input type="hidden" name="kode_produk[]" value="${kode_produk}" class="kode_produk"/>
                            <input type="hidden" name="status_promosi_produk[]" class="status_promosi" value="${status_promosi}"/>
                            <input type="hidden" name="kode_kategori_diskon[]" class="kode_kategori_diskon" value="${kode_kategori_diskon}"/>
                            <input type="hidden" name="jumlah_produk[]" value="${jumlah}"/>
                            <input type="hidden" name="isi_pcs_dus_produk[]" value="${isi_pcs_dus}"/>
                            <input type="hidden" name="isi_pcs_pack_produk[]" value="${isi_pcs_pack}"/>
                            ${kode_harga}
                        </td>
                        <td class="px-4 py-3">${nama_produk}</td>
                        <td class="px-4 py-3 text-center">
                           ${dus===0 ? '' : dus}
                        </td>
                        <td class="px-4 py-3 text-end">
                           ${harga_dus}
                           <input type="hidden" name="harga_dus_produk[]" value="${harga_dus}"/>
                        </td>
                        <td class="px-4 py-3 text-center">${pack===0 ? '' :pack}</td>
                        <td class="px-4 py-3 text-end">
                           ${harga_pack}
                           <input type="hidden" name="harga_pack_produk[]" value="${harga_pack}"/>
                        </td>
                        <td class="px-4 py-3 text-center">${pcs===0 ? '' :pcs}</td>
                        <td class="px-4 py-3 text-end">
                           ${harga_pcs}
                           <input type="hidden" name="harga_pcs_produk[]" value="${harga_pcs}"/>
                        </td>
                        <td class="px-4 py-3 text-end">
                            ${convertToRupiah(subtotal)}
                            <input type="hidden" name="subtotal[]" class="subtotal" value="${subtotal}"/>
                        </td>
                        <td class="px-4 py-3 text-center">
                           <div class="d-flex justify-content-center">
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
                $("#kode_harga").val("");
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
                $("#status_promosi").prop('checked', false);

                loadsubtotal();


            }

        }

        $("#status_promosi").change(function() {
            let harga_dus = $("#harga_dus_produk").val();
            let harga_pack = $("#harga_pack_produk").val();
            let harga_pcs = $("#harga_pcs_produk").val();
            if (this.checked) {
                $("#harga_dus").val(0);
                $("#harga_pack").val(0);
                $("#harga_pcs").val(0);
            } else {
                $("#harga_dus").val(harga_dus);
                $("#harga_pack").val(harga_pack);
                $("#harga_pcs").val(harga_pcs);
            }
        });
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
            let jml_pack = currentRow.find('td:eq(4)').text();
            let harga_pack = currentRow.find('td:eq(5)').text();
            let jml_pcs = currentRow.find('td:eq(6)').text();
            let harga_pcs = currentRow.find('td:eq(7)').text();
            let subtotal = currentRow.find('td:eq(8)').text();
            let kode_pelanggan = $("#kode_pelanggan").val();
            let status_promosi = currentRow.find('.status_promosi').val();
            let index_old = kode_harga + "" + status_promosi;
            console.log(kode_harga);
            console.log(status_promosi);
            console.log(index_old);
            //alert(status_promosi);
            let dataProduk = {
                'kode_pelanggan': kode_pelanggan,
                'kode_harga': kode_harga,
                'kode_produk': kode_produk,
                'nama_produk': nama_produk,
                'jml_dus': jml_dus,
                'harga_dus': harga_dus,
                'jml_pack': jml_pack,
                'harga_pack': harga_pack,
                'jml_pcs': jml_pcs,
                'harga_pcs': harga_pcs,
                'status_promosi': status_promosi,
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
            let status_promosi;
            // if ($(this).find('#status_promosi_edit').is(":checked")) {
            //     let status_promosi =
            // } else {
            //     let status_promosi = 0;
            // }
            if ($(this).find('#status_promosi_edit').is(':checked')) {
                status_promosi = 1;
            } else {
                status_promosi = 0;
            }




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


            let index = kode_harga + status_promosi;
            console.log(index_old);
            let bgcolor = "";
            if (status_promosi == '1') {
                bgcolor = "bg-warning text-white";
                hargadus = 0;
                hargapack = 0;
                hargapcs = 0;
                harga_dus = 0;
                harga_pack = 0;
                harga_pcs = 0;
            } else {
                bgcolor = bgcolor;
            }
            let subtotal = (parseInt(dus) * parseInt(hargadus)) + (parseInt(pack) * parseInt(
                hargapack)) + (
                parseInt(pcs) * parseInt(hargapcs));

            let newRow = `
                    <tr id="index_${index}" class="${bgcolor}">
                        <td class="px-4 py-3">
                            <input type="hidden" name="kode_harga_produk[]" value="${kode_harga}" class="kode_harga"/>
                            <input type="hidden" name="kode_produk[]" value="${kode_produk}" class="kode_produk"/>
                            <input type="hidden" name="status_promosi_produk[]" value="${status_promosi}" class="status_promosi"/>
                            <input type="hidden" name="kode_kategori_diskon[]" class="kode_kategori_diskon" value="${kode_kategori_diskon}"/>
                            <input type="hidden" name="jumlah_produk[]" value="${jumlah}"/>
                            <input type="hidden" name="isi_pcs_dus_produk[]" value="${isi_pcs_dus}"/>
                            <input type="hidden" name="isi_pcs_pack_produk[]" value="${isi_pcs_pack}"/>
                            ${kode_harga}
                        </td>
                        <td class="px-4 py-3">${nama_produk}</td>
                        <td class="px-4 py-3 text-center">
                           ${dus===0 ? '' : dus}
                        </td>
                        <td class="px-4 py-3 text-end">
                           ${harga_dus}
                           <input type="hidden" name="harga_dus_produk[]" value="${harga_dus}"/>
                        </td>
                        <td class="px-4 py-3 text-center">${pack===0 ? '' :pack}</td>
                        <td class="px-4 py-3 text-end">
                           ${harga_pack}
                           <input type="hidden" name="harga_pack_produk[]" value="${harga_pack}"/>
                        </td>
                        <td class="px-4 py-3 text-center">${pcs===0 ? '' :pcs}</td>
                        <td class="px-4 py-3 text-end">
                           ${harga_pcs}
                           <input type="hidden" name="harga_pcs_produk[]" value="${harga_pcs}"/>
                        </td>
                        <td class="px-4 py-3 text-end">
                            <input type="hidden" name="subtotal[]" class="subtotal" value="${subtotal}"/>
                            ${convertToRupiah(subtotal)}
                        </td>
                        <td class="px-4 py-3 text-center">
                           <div class="d-flex justify-content-center">
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
            hitungdiskonAida();
            hitungdiskonSwan();
            hitungdiskonStick();
            hitungdiskonSC();
            hitungdiskonSP();
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
            let discount = 0;
            let discount_tunai = 0;
            let total_discount = 0;
            let nama_pelanggan = $("#nama_pelanggan").val();
            let jenis_transaksi = $("#jenis_transaksi").val();
            // Define discount rules based on quantity range and category
            const discountRules = <?php echo $diskon; ?>;

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

                    if (nama_pelanggan.includes('KPBN') || nama_pelanggan.includes('RSB')) {
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
            let blacklist_pelanggan = [
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
            if (blacklist_pelanggan.includes(kode_pel)) {
                diskon = 0;
            }
            return diskon;

        }


        function hitungdskonSAOSME() {
            const kode_cabang_diskon_saosme = ['BTN', 'CRB'];
            let totalQuantity = calculateTotalQuantityByCategory('D010');
            let diskon = calculateDiscount(totalQuantity, 'D010');
            // Baris berikut memeriksa apakah kode_cabang_pelanggan termasuk dalam daftar kode_cabang_diskon_saosme.
            // Jika iya, maka nilai diskon tetap (tidak diubah). Sebenarnya, penugasan diskon = diskon; tidak melakukan perubahan apapun,
            // sehingga baris ini hanya sebagai placeholder atau untuk menandai bahwa diskon hanya berlaku untuk cabang tertentu.
            if (kode_cabang_diskon_saosme.includes(kode_cabang_pelanggan)) {
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
            let blacklist_pelanggan = [];

            let totalQuantity = calculateTotalQuantityByCategory('D003');
            let diskon = calculateDiscount(totalQuantity, 'D003');

            if (blacklist_pelanggan.includes(kode_pel)) {
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

            //Penyesuaian
            const penyAidaVal = $("#peny_aida").val();
            const peny_aida = penyAidaVal != "" ? parseInt(penyAidaVal.replace(/\./g, '')) : 0;

            const penySwanVal = $("#peny_swan").val();
            const peny_swan = penySwanVal != "" ? parseInt(penySwanVal.replace(/\./g, '')) : 0;

            const penyStickVal = $("#peny_stick").val();
            const peny_stick = penyStickVal != "" ? parseInt(penyStickVal.replace(/\./g, '')) : 0;

            const total_penyesuaian = parseInt(peny_aida) + parseInt(peny_swan) + parseInt(peny_stick);



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



        $("#formPenjualan").submit(function(e) {
            const no_faktur = $("#no_faktur").val();
            const tanggal = $("#tanggal").val();
            const kode_pelanggan = $("#kode_pelanggan").val();
            const kode_salesman = $("#kode_salesman").val();
            const sisa_piutang = $("#sisa_piutang").val();
            const gt = $("#grandtotal").val();
            const grandtotal = gt != "" ? parseInt(gt.replace(/\./g, '')) : 0;
            const totalPiutang = parseInt(sisa_piutang) + parseInt(grandtotal);
            let limit_pelanggan = $("#limit_pelanggan").val() == "" ? 0 : $("#limit_pelanggan").val();
            // alert(limit_pelanggan);
            const siklus_pembayaran = $("#siklus_pembayaran").val();
            const max_kredit = $("#max_kredit").val();
            const jenis_transaksi = $("#jenis_transaksi").val();
            const jenis_bayar = $("#jenis_bayar").val();
            const keterangan = $("#keterangan").val();
            const voucher = $("#voucher").val();
            const voucherVal = voucher ? parseInt(voucher.replace(/\./g, '')) : 0;

            if (no_faktur == '') {
                e.preventDefault();
                SwalWarning('no_faktur', 'No. Faktur Tidak Boleh Kosong');
                return false;
            } else if (tanggal == '') {
                e.preventDefault();
                SwalWarning('tanggal', 'Tanggal Tidak Boleh Kosong');
                return false;
            } else if (kode_pelanggan == "") {
                e.preventDefault();
                SwalWarning('nama_pelanggan', 'Pelanggan Tidak Boleh Kosong');
                return false;
            } else if (kode_salesman == "") {
                e.preventDefault();
                SwalWarning('nama_salesman', 'Salesman Tidak Boleh Kosong');
                return false;
            } else if ($('#loadproduk tr').length == 0) {
                e.preventDefault();
                SwalWarning('nama_produk', 'Detail Produk Tidak Boleh Kosong');
                return false;
            } else if (jenis_transaksi == "") {
                e.preventDefault();
                SwalWarning('jenis_transaksi', 'Jenis Transaksi Tidak Boleh Kosong');
                return false;
            } else if (jenis_transaksi == "T" && jenis_bayar == "") {
                e.preventDefault();
                SwalWarning('jenis_bayar', 'Jenis Bayar Tidak Boleh Kosong');
                return false;
            // } else if (jenis_transaksi == "K" && siklus_pembayaran === '0' && parseInt(totalPiutang) >
            //     parseInt(limit_pelanggan)) {
            //     e.preventDefault();
            //     SwalWarning('nama_produk', 'Melebihi Limit, Silahkan Ajukan Penambahan Limit !');
            //     return false;
            // } else if (jenis_transaksi == "K" && siklus_pembayaran === '1' && parseInt(grandtotal) >
            //     parseInt(limit_pelanggan)) {
            //     e.preventDefault();
            //     SwalWarning('nama_produk', 'Melebihi Limit, Silahkan Ajukan Penambahan Limit !');
            //     return false;
            // } else if (jenis_transaksi == "K" && jmlfakturbelumlunas >= jmlfakturmax) {
            //     e.preventDefault();
            //     SwalWarning('keterangan', 'Melebihi Batas Jumlah Faktur Kredit !');
            //     return false;
            } else if (voucherVal > saldo_voucher) {
                e.preventDefault();
                SwalWarning('voucher', 'Melebihi Saldo Voucher !');
                return false;
            } else if (jenis_transaksi == "K" && sisa_piutang > 0 && keterangan == "") {
                e.preventDefault();
                SwalWarning('keterangan', 'Keterangan Harus Diisi !');
                return false;
            } else {
                buttonDisable();
            }
        });

        function generatenofaktur() {
            var tanggal = $("#tanggal").val();
            var kode_salesman = $("#kode_salesman").val();
            buttonDisable();
            $.ajax({
                type: 'POST',
                url: '/penjualan/generatenofaktur',
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: tanggal,
                    kode_salesman: kode_salesman
                },
                cache: false,
                success: function(respond) {
                    buttonEnable();
                    if (respond !== '0') {
                        $("#no_faktur").val(respond);
                        // $("#no_faktur").prop('readonly', true);
                        console.log(respond);
                    }

                }
            });
        }

        $("#tanggal,#kode_salesman").change(function() {
            generatenofaktur();
        });
    });
</script>
@endpush
