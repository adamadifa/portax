<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Pembelian Satu Baris {{ date('Y-m-d H:i:s') }}</title>
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
        <h4 class="title">LAPORAN PEMBELIAN (SATU BARIS)</h4>
        <h4>PERIODE : {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3" style="width:{{ 100 + (count($produk) * 5) }}%">
                <thead>
                    <tr>
                        <th rowspan="2">No.</th>
                        <th rowspan="2">Tanggal</th>
                        <th rowspan="2">No. Bukti</th>
                        <th rowspan="2">Supplier</th>
                        <th colspan="{{ count($produk) }}">Detail Produk (Qty)</th>
                        <th rowspan="2">Total Bruto</th>
                        <th rowspan="2">Total Bayar</th>
                        <th rowspan="2">Sisa Bayar</th>
                        <th rowspan="2">Ket</th>
                    </tr>
                    <tr>
                        @foreach ($produk as $p)
                            <th>{{ $p->nama_produk }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        $arr = [];
                        foreach ($pembelian as $row) {
                            $arr[$row->no_bukti][] = $row;
                        }
                        $grandtotal_bruto = 0;
                        $grandtotal_bayar = 0;
                        $grandtotal_sisa = 0;
                    @endphp
                    @foreach ($arr as $no_bukti => $details)
                        @php
                            $d = $details[0]; // Header info
                            $total_bruto = 0;
                            // Map products
                            $row_products = [];
                            foreach ($details as $det) {
                                $row_products[$det->kode_produk] = $det->jumlah;
                            }
                            $grandtotal_bruto += $d->total_bruto;
                            $grandtotal_bayar += $d->total_bayar;
                            $sisabayar = $d->total_bruto - $d->total_bayar;
                            $grandtotal_sisa += $sisabayar;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ DateToIndo($d->tanggal) }}</td>
                            <td>{{ $d->no_bukti }}</td>
                            <td>{{ $d->nama_supplier }}</td>
                            @foreach ($produk as $p)
                                <td class="center">
                                    {{ isset($row_products[$p->kode_produk]) ? formatAngka($row_products[$p->kode_produk]) : '' }}
                                </td>
                            @endforeach
                            <td class="right">{{ formatAngka($d->total_bruto) }}</td>
                            <td class="right">{{ formatAngka($d->total_bayar) }}</td>
                            <td class="right">{{ formatAngka($sisabayar) }}</td>
                             <td class="center text-{{ $d->status == '1' ? 'green' : 'red' }}">
                                {{ $d->status == '1' ? 'LUNAS' : 'BELUM' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                     <tr>
                        <th colspan="4" class="center">TOTAL</th>
                        @foreach ($produk as $p)
                            <th></th>
                        @endforeach
                        <th class="right">{{ formatAngka($grandtotal_bruto) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_bayar) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_sisa) }}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>
</html>
