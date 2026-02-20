<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Penjualan {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/js/freeze-table.js') }}"></script>
    {{-- <style>
        .freeze-table {
            height: auto;
            max-height: 830px;
            overflow: auto;
        }
    </style> --}}

    <style>
        .text-red {
            background-color: red;
            color: white;
        }

        .bg-terimauang {
            background-color: #199291 !important;
            color: white !important;
        }
    </style>
</head>

<body>
    <div class="header">
        <h4 class="title">
            LAPORAN PENJUALAN <br>
        </h4>
        <h4>PERIODE : {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        @if ($cabang != null)
            <h4>
                {{ textUpperCase($cabang->nama_cabang) }}
            </h4>
        @endif
        @if ($salesman != null)
            <h4>
                {{ textUpperCase($salesman->nama_salesman) }}
            </h4>
        @endif
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3" style="width: 180%">
                <thead>
                    <tr>
                        {{-- <th>No.</th> --}}
                        <th rowspan="2">Tanggal</th>
                        <th rowspan="2">No. Faktur</th>
                        <th rowspan="2">Kode</th>
                        <th rowspan="2" style="width: 6%">Nama Pelanggan</th>
                        <th rowspan="2">Nama Salesman</th>
                        <th rowspan="2">Hari</th>
                        <th rowspan="2">Klasifikasi</th>
                        <th rowspan="2">Wilayah</th>
                        <th rowspan="2">Nama Produk</th>
                        <th colspan="15">Qty</th>
                        {{-- <th rowspan="2">Bruto</th>
                        <th rowspan="2">Peny</th>
                        <th colspan="6" class="red">Potongan</th>
                        <th rowspan="2">Pot. Istimewa</th>
                        <th rowspan="2">DPP</th>
                        <th rowspan="2">PPN</th>
                        <th rowspan="2">Retur</th>
                        <th rowspan="2" class="green">Netto</th> --}}
                        <th rowspan="2" class="green">T/K</th>
                        <th rowspan="2" class="green">Created</th>
                        <th rowspan="2" class="green">Updated</th>
                        <th rowspan="2" class="green">User</th>
                    </tr>
                    <tr>
                        <th>Dus</th>
                        <th>Harga</th>
                        <th>DPP</th>
                        <th>Pack</th>
                        <th>Harga</th>
                        <th>DPP</th>
                        <th>Pcs</th>
                        <th>Harga</th>
                        <th>DPP</th>
                        <th>Subtotal</th>
                        <th>Diskon</th>
                        <th>TOTAL DPP</th>
                        <th>DPP LAIN</th>
                        <th>PPN</th>
                        <th>JUMLAH</th>

                        {{-- <th class="red">AIDA</th>
                        <th class="red">SWAN</th>
                        <th class="red">STICK</th>
                        <th class="red">SP</th>
                        <th class="red">SC</th>
                        <th class="red">TOTAL</th> --}}
                    </tr>
                </thead>

                <tbody>
                    @php
                        $arr = [];
                        foreach ($penjualan as $row) {
                            $arr[$row->no_faktur][] = $row;
                        }
                        $grandtotal_bruto = 0;
                        $grandtotal_peny = 0;
                        $grandtotal_potongan_aida = 0;
                        $grandtotal_potongan_swan = 0;
                        $grandtotal_potongan_stick = 0;
                        $grandtotal_potongan_sp = 0;
                        $grandtotal_potongan_sc = 0;
                        $grandtotal_potongan = 0;
                        $grandtotal_potongan_istimewa = 0;
                        $grandtotal_dpp = 0;
                        $grandtotal_ppn = 0;
                        $grandtotal_retur = 0;
                        $grandtotal_netto = 0;
                        $grand_total_dpp_global = 0;
                        $grand_total_ppn_global = 0;
                        $grand_total_jumlah_global = 0;

                        $total = 0;
                    @endphp
                    @foreach ($arr as $key => $val)
                        @php
                             $jml_dus_aida = 0;
                             $jml_dus_swan = 0;
                             $jml_dus_stick = 0;
                             $jml_dus_sp = 0;
                             $jml_dus_sambal = 0;
                             foreach($val as $v){
                                 if($v->kode_kategori_diskon == "D002"){
                                     $jml_dus_aida += $v->jumlah / $v->isi_pcs_dus;
                                 }

                                 if($v->kode_kategori_diskon == "D001"){
                                     $jml_dus_swan += $v->jumlah / $v->isi_pcs_dus;
                                 }

                                 if($v->kode_kategori_diskon == "D003"){
                                     $jml_dus_stick += $v->jumlah / $v->isi_pcs_dus;
                                 }

                                 if($v->kode_kategori_diskon == "D004"){
                                     $jml_dus_sp += $v->jumlah / $v->isi_pcs_dus;
                                 }

                                 if($v->kode_kategori_diskon == "D005"){
                                     $jml_dus_sambal += $v->jumlah / $v->isi_pcs_dus;
                                 }
                             }

                             // Hitung rasio untuk D001 (SWAN) dan D004 (SP)
                             $diskon_normal_swan = 0;
                             if ($jml_dus_swan >= 500) $diskon_normal_swan = 2250;
                             elseif ($jml_dus_swan >= 300) $diskon_normal_swan = 2000;
                             elseif ($jml_dus_swan >= 100) $diskon_normal_swan = 1750;
                             elseif ($jml_dus_swan >= 50) $diskon_normal_swan = 1500;
                             elseif ($jml_dus_swan >= 30) $diskon_normal_swan = 1250;
                             elseif ($jml_dus_swan >= 5) $diskon_normal_swan = 1000;
                             $teoritis_swan = $jml_dus_swan * $diskon_normal_swan;

                             $diskon_normal_sp = 0;
                             if ($jml_dus_sp >= 500) $diskon_normal_sp = 2500;
                             elseif ($jml_dus_sp >= 300) $diskon_normal_sp = 2250;
                             elseif ($jml_dus_sp >= 100) $diskon_normal_sp = 2000;
                             elseif ($jml_dus_sp >= 50) $diskon_normal_sp = 1500;
                             elseif ($jml_dus_sp >= 30) $diskon_normal_sp = 1000;
                             elseif ($jml_dus_sp >= 5) $diskon_normal_sp = 500;
                             $teoritis_sp = $jml_dus_sp * $diskon_normal_sp;
                             
                             $total_teoritis = $teoritis_swan + $teoritis_sp;

                        @endphp
                        @foreach ($val as $k => $d)
                            @php
                                $diskon = 0;
                                $qty_dus_this_row = $d->jumlah / $d->isi_pcs_dus;
                                if ($d->kode_kategori_diskon == "D002" && $jml_dus_aida > 0) {
                                    $diskon = (($qty_dus_this_row / $jml_dus_aida) * $d->potongan_aida) * (100/111);
                                }

                                if ($d->kode_kategori_diskon == "D001" && $jml_dus_swan > 0) {
                                    $proporsi_swan = $total_teoritis > 0 ? ($teoritis_swan / $total_teoritis) : ($jml_dus_swan / ($jml_dus_swan + $jml_dus_sp));
                                    $diskon = (($qty_dus_this_row / $jml_dus_swan) * ($d->potongan_swan * $proporsi_swan)) * (100/111);
                                }

                                if ($d->kode_kategori_diskon == "D003" && $jml_dus_stick > 0) {
                                    $diskon = (($qty_dus_this_row / $jml_dus_stick) * $d->potongan_stick) * (100/111);
                                }

                                if ($d->kode_kategori_diskon == "D004" && $jml_dus_sp > 0) {
                                    $proporsi_sp = $total_teoritis > 0 ? ($teoritis_sp / $total_teoritis) : ($jml_dus_sp / ($jml_dus_swan + $jml_dus_sp));
                                    $diskon = (($qty_dus_this_row / $jml_dus_sp) * ($d->potongan_swan * $proporsi_sp)) * (100/111);
                                }

                                if ($d->kode_kategori_diskon == "D005" && $jml_dus_sambal > 0) {
                                    $diskon = (($qty_dus_this_row / $jml_dus_sambal) * $d->potongan_sambal) * (100/111);
                                }

                                if ($k == 0) {
                                    $diskon += ($d->potongan_istimewa * (100/111));
                                }
                            @endphp
                            @php
                                if (!empty($d->isi_pcs_dus) && $d->status_batal == 0) {
                                    $qty = convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->jumlah);
                                    $jml = explode('|', $qty);
                                    $dus = $jml[0];
                                    $pack = $jml[1];
                                    $pcs = $jml[2];
                                    $total += $d->subtotal;
                                    
                                    $d__dpp = ($d->subtotal * (100/111)) - $diskon;
                                    $d__ppn = ($d__dpp * (11/12) * 0.12);
                                    $d__jumlah = $d__dpp + $d__ppn;

                                    $grand_total_dpp_global += $d__dpp;
                                    $grand_total_ppn_global += $d__ppn;
                                    $grand_total_jumlah_global += $d__jumlah;
                                    if ($d->status_promosi == '1') {
                                        $bgcolorpromosi = 'yellow';
                                    } else {
                                        $bgcolorpromosi = '';
                                    }
                                    $bgcolor = '';
                                } else {
                                    $dus = 0;
                                    $pack = 0;
                                    $pcs = 0;
                                    $bgcolor = 'red';
                                    $bgcolorpromosi = '';
                                }

                            @endphp
                            <tr style="background-color: {{ $bgcolor }}">
                                @if ($k == 0)
                                    <td rowspan="{{ count($val) }}">{{ formatIndo($d->tanggal) }}</td>
                                    <!-- <td rowspan="{{ count($val) }}">{{ !empty($d->no_fak_new) ? $d->no_fak_new : $d->no_faktur }}</td> -->
                                    <td rowspan="{{ count($val) }}">{{ $d->no_faktur }}</td>
                                    <td rowspan="{{ count($val) }}">{{ $d->kode_pelanggan }}</td>
                                    <td rowspan="{{ count($val) }}">{{ $d->nama_pelanggan }}</td>
                                    <td rowspan="{{ count($val) }}">{{ $d->nama_salesman }}</td>
                                    <td rowspan="{{ count($val) }}">{{ $d->hari }}</td>
                                    <td rowspan="{{ count($val) }}">{{ $d->klasifikasi }}</td>
                                    <td rowspan="{{ count($val) }}">{{ $d->nama_wilayah }}</td>
                                @endif
                                <td style="background-color: {{ !empty($bgcolorpromosi) ? $bgcolorpromosi : $bgcolor }}">{{ $d->nama_produk }}</td>
                                <td class="center" style="background-color:  {{ !empty($bgcolorpromosi) ? $bgcolorpromosi : $bgcolor }}">
                                    {{ formatAngka($dus) }}</td>
                                <td class="right" style="background-color:  {{ !empty($bgcolorpromosi) ? $bgcolorpromosi : $bgcolor }}">
                                    {{ !empty($dus) ? formatAngka($d->harga_dus) : '' }}</td>
                                <td class="right" style="background-color:  {{ !empty($bgcolorpromosi) ? $bgcolorpromosi : $bgcolor }}">
                                    {{ !empty($dus) ? formatAngka($d->harga_dus * (100/111)) : '' }}</td>
                                <td class="center" style="background-color:  {{ !empty($bgcolorpromosi) ? $bgcolorpromosi : $bgcolor }}">
                                    {{ formatAngka($pack) }}</td>
                                <td class="right" style="background-color:  {{ !empty($bgcolorpromosi) ? $bgcolorpromosi : $bgcolor }}">
                                    {{ !empty($pack) ? formatAngka($d->harga_pack) : '' }}</td>
                                <td class="right" style="background-color:  {{ !empty($bgcolorpromosi) ? $bgcolorpromosi : $bgcolor }}">
                                    {{ !empty($pack) ? formatAngka($d->harga_pack * (100/111)) : '' }}</td>
                                <td class="center" style="background-color:  {{ !empty($bgcolorpromosi) ? $bgcolorpromosi : $bgcolor }}">
                                    {{ formatAngka($pcs) }}</td>
                                <td class="right" style="background-color:  {{ !empty($bgcolorpromosi) ? $bgcolorpromosi : $bgcolor }}">
                                    {{ !empty($pcs) ? formatAngka($d->harga_pcs) : '' }}</td>
                                <td class="right" style="background-color:  {{ !empty($bgcolorpromosi) ? $bgcolorpromosi : $bgcolor }}">
                                    {{ !empty($pcs) ? formatAngka($d->harga_pcs * (100/111)) : '' }}</td>
                                <td class="right" style="background-color:  {{ !empty($bgcolorpromosi) ? $bgcolorpromosi : $bgcolor }}">
                                    {{ formatAngka($d->subtotal) }}</td>
                                <td class="right" style="background-color:  {{ !empty($bgcolorpromosi) ? $bgcolorpromosi : $bgcolor }}">
                                    {{ formatAngka($diskon) }}</td>
                                <td class="right" style="background-color:  {{ !empty($bgcolorpromosi) ? $bgcolorpromosi : $bgcolor }}">
                                    {{ formatAngka(($d->subtotal * (100/111)) - $diskon) }}</td>
                                <td class="right" style="background-color:  {{ !empty($bgcolorpromosi) ? $bgcolorpromosi : $bgcolor }}">
                                    {{ formatAngka((($d->subtotal * (100/111)) - $diskon) * (11/12)) }}</td>
                                <td class="right" style="background-color:  {{ !empty($bgcolorpromosi) ? $bgcolorpromosi : $bgcolor }}">
                                    {{ formatAngka((($d->subtotal * (100/111)) - $diskon) * (11/12) * 0.12) }}</td>
                                <td class="right" style="background-color:  {{ !empty($bgcolorpromosi) ? $bgcolorpromosi : $bgcolor }}">
                                    {{ formatAngka( (($d->subtotal * (100/111)) - $diskon) + ((($d->subtotal * (100/111)) - $diskon) * (11/12) * 0.12) ) }}</td>


                                @if ($k == 0)
                                    <!-- Untuk Menjumlahkan Subtotal Per faktur-->
                                    @php
                                        $dpp = $d->total_bruto - $d->potongan - $d->penyesuaian - $d->potongan_istimewa;
                                        $netto = $dpp - $d->total_retur + $d->ppn;
                                        $grandtotal_bruto += $d->total_bruto;
                                        $grandtotal_peny += $d->penyesuaian;
                                        $grandtotal_potongan_aida += $d->potongan_aida;
                                        $grandtotal_potongan_swan += $d->potongan_swan;
                                        $grandtotal_potongan_stick += $d->potongan_stick;
                                        $grandtotal_potongan_sp += $d->potongan_sp;
                                        $grandtotal_potongan_sc += $d->potongan_sambal;
                                        $grandtotal_potongan += $d->potongan;
                                        $grandtotal_dpp += $dpp;
                                        $grandtotal_ppn += $d->ppn;
                                        $grandtotal_retur += $d->total_retur;
                                        $grandtotal_netto += $netto;
                                    @endphp
                                    {{-- <td rowspan="{{ count($val) }}" class="right">{{ formatAngka($d->total_bruto) }}</td>
                                    <td rowspan="{{ count($val) }}" class="right">{{ formatAngka($d->penyesuaian) }}</td>
                                    <td rowspan="{{ count($val) }}" class="right">{{ formatAngka($d->potongan_aida) }}</td>
                                    <td rowspan="{{ count($val) }}" class="right">{{ formatAngka($d->potongan_swan) }}</td>
                                    <td rowspan="{{ count($val) }}" class="right">{{ formatAngka($d->potongan_stick) }}</td>
                                    <td rowspan="{{ count($val) }}" class="right">{{ formatAngka($d->potongan_sp) }}</td>
                                    <td rowspan="{{ count($val) }}" class="right">{{ formatAngka($d->potongan_sambal) }}</td>
                                    <td rowspan="{{ count($val) }}" class="right">{{ formatAngka($d->potongan) }}</td>
                                    <td rowspan="{{ count($val) }}" class="right">{{ formatAngka($d->potongan_istimewa) }}</td>
                                    <td rowspan="{{ count($val) }}" class="right">{{ formatAngka($dpp) }}</td>
                                    <td rowspan="{{ count($val) }}" class="right">{{ formatAngka($d->ppn) }}</td>
                                    <td rowspan="{{ count($val) }}" class="right">{{ formatAngka($d->total_retur) }}</td>
                                    <td rowspan="{{ count($val) }}" class="right">{{ formatAngka($netto) }}</td> --}}
                                    <td rowspan="{{ count($val) }}" class="center">
                                        @if ($d->jenis_transaksi == 'K')
                                            KREDIT
                                        @else
                                            TUNAI
                                        @endif
                                    </td>
                                    <td rowspan="{{ count($val) }}">{{ date('d-m-Y H:i:s', strtotime($d->created_at)) }}</td>
                                    <td rowspan="{{ count($val) }}">{{ date('d-m-Y H:i:s', strtotime($d->updated_at)) }}</td>
                                    <td rowspan="{{ count($val) }}"> {{ $d->nama_user }}</td>
                                @endif
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="20">TOTAL</th>
                        <th class="right">{{ formatAngka($grand_total_dpp_global) }}</th>
                        <th class="right"></th>
                        <th class="right">{{ formatAngka($grand_total_ppn_global) }}</th>
                        <th class="right">{{ formatAngka($grand_total_jumlah_global) }}</th>
                        <th colspan="4"></th>

                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>

</html>
{{-- <script>
    $(".freeze-table").freezeTable({
        'scrollable': true,
        'columnNum': 5,
        'shadow': true,
    });
</script> --}}
