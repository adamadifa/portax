<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Pembelian Standar {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/libs/freeze/js/freeze-table.min.js') }}"></script>
    <style>
        .text-red { background-color: red; color: white; }
        .text-green { background-color: green; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <h4 class="title">LAPORAN PEMBELIAN (STANDAR)</h4>
        <h4>PERIODE : {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3" style="width: 100%">
                <thead>
                    <tr>
                        <th rowspan="2">Tanggal</th>
                        <th rowspan="2">No. Bukti</th>
                        <th rowspan="2">Supplier</th>
                        <th rowspan="2">User</th>
                        <th rowspan="2">Nama Produk</th>
                        <th colspan="6">Subtotal</th>
                        <th rowspan="2">Total</th>
                        <th rowspan="2">Jenis Transaksi</th>
                        <th rowspan="2">Status</th>
                    </tr>
                    <tr>
                        <th>DUS</th>
                        <th>HARGA / DUS</th>
                        <th>DPP</th>
                        <th>DPP LAIN</th>
                        <th>PPN</th>
                        <th>JUMLAH</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $grand_total_dpp_global = 0;
                        $grand_total_dpp_lain_global = 0;
                        $grand_total_ppn_global = 0;
                        $grand_total_jumlah_global = 0;
                        $total = 0;
                        $grand_total_keseluruhan = 0;
                        $arr = [];
                        foreach ($pembelian as $row) {
                            $arr[$row->no_bukti][] = $row;
                        }
                    @endphp
                    @foreach ($arr as $no_bukti => $details)
                        @php
                            $first = true;
                            $rowspan = count($details);
                            $total_faktur = 0;
                            foreach ($details as $d) {
                                $d__dpp = $d->subtotal;
                                $d__dpp_lain = $d__dpp * (11/12);
                                $d__ppn = $d__dpp_lain * 0.12;
                                $d__jumlah = $d__dpp + $d__ppn;
                                $total_faktur += $d__jumlah;
                            }
                            $grand_total_keseluruhan += $total_faktur;
                        @endphp
                        @foreach ($details as $d)
                            @php
                                $dus = 0;
                                $pack = 0;
                                $pcs = 0;

                                if (!empty($d->isi_pcs_dus)) {
                                    $qty = convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->jumlah);
                                    $jml = explode('|', $qty);
                                    $dus = $jml[0];
                                    $pack = $jml[1];
                                    $pcs = $jml[2];
                                } else {
                                    // If no conversion info, fallback to display raw jumlah as dus
                                    $dus = $d->jumlah;
                                }

                                $total += $d->subtotal;
                                
                                // DPP = Qty * Harga (Subtotal is exactly Qty * Harga in the system)
                                $d__dpp = $d->subtotal;
                                $d__dpp_lain = $d__dpp * (11/12);
                                $d__ppn = $d__dpp_lain * 0.12;
                                $d__jumlah = $d__dpp + $d__ppn;

                                $grand_total_dpp_global += $d__dpp;
                                $grand_total_dpp_lain_global += $d__dpp_lain;
                                $grand_total_ppn_global += $d__ppn;
                                $grand_total_jumlah_global += $d__jumlah;
                            @endphp
                            <tr>
                                @if ($first)
                                    <td rowspan="{{ $rowspan }}">{{ DateToIndo($d->tanggal) }}</td>
                                    <td rowspan="{{ $rowspan }}">{{ $d->no_bukti }}</td>
                                    <td rowspan="{{ $rowspan }}">{{ $d->nama_supplier }}</td>
                                    <td rowspan="{{ $rowspan }}">{{ (!empty($d->nama_user)) ? $d->nama_user : '' }}</td>
                                @endif
                                <td>{{ $d->nama_produk }}</td>
                                <td class="center">{{ formatAngka($dus) }}</td>
                                <td class="right">{{ !empty($dus) ? formatAngka($d->harga_dus) : '' }}</td>
                                <td class="right">{{ formatAngka($d__dpp) }}</td>
                                <td class="right">{{ formatAngka($d__dpp_lain) }}</td>
                                <td class="right">{{ formatAngka($d__ppn) }}</td>
                                <td class="right">{{ formatAngka($d__jumlah) }}</td>

                                @if ($first)
                                    <td rowspan="{{ $rowspan }}" class="right font-bold">{{ formatAngka($total_faktur) }}</td>
                                    <td rowspan="{{ $rowspan }}" class="center">{{ $d->jenis_transaksi == 'T' ? 'TUNAI' : 'KREDIT' }}</td>
                                    <td rowspan="{{ $rowspan }}" class="center text-{{ $d->status == '1' ? 'green' : 'red' }}">
                                        {{ $d->status == '1' ? 'LUNAS' : 'BLM LUNAS' }}
                                    </td>
                                @endif
                            </tr>
                            @php $first = false; @endphp
                        @endforeach
                    @endforeach
                </tbody>
                <tfoot>
                     <tr>
                        <th colspan="7" class="center">TOTAL</th>
                        <th class="right">{{ formatAngka($grand_total_dpp_global) }}</th>
                        <th class="right">{{ formatAngka($grand_total_dpp_lain_global) }}</th>
                        <th class="right">{{ formatAngka($grand_total_ppn_global) }}</th>
                        <th class="right">{{ formatAngka($grand_total_jumlah_global) }}</th>
                        <th class="right">{{ formatAngka($grand_total_keseluruhan) }}</th>
                        <th colspan="2"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>
</html>
