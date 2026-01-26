<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Pembelian {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/libs/freeze/js/freeze-table.min.js') }}"></script>
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
            LAPORAN PEMBELIAN <br>
        </h4>
        <h4>PERIODE : {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>

    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3" style="width: 100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tanggal</th>
                        <th>No. Bukti</th>
                        <th>Supplier</th>
                        <th>Jenis Transaksi</th>
                        <th>Status</th>
                        <th>Total Bruto</th>
                        <th>Total Bayar</th>
                        <th>Sisa Bayar</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $grandtotal_bruto = 0;
                        $grandtotal_bayar = 0;
                        $grandtotal_sisa = 0;
                    @endphp
                    @foreach ($pembelian as $d)
                        @php
                            $sisabayar = $d->total_bruto - $d->total_bayar;
                            $grandtotal_bruto += $d->total_bruto;
                            $grandtotal_bayar += $d->total_bayar;
                            $grandtotal_sisa += $sisabayar;
                            $ket_status = $d->status == '1' ? 'LUNAS' : 'BELUM LUNAS';
                            $color_status = $d->status == '1' ? 'green' : 'red';
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ DateToIndo($d->tanggal) }}</td>
                            <td>{{ $d->no_bukti }}</td>
                            <td>{{ $d->nama_supplier }}</td>
                            <td class="center">{{ $d->jenis_transaksi == 'T' ? 'TUNAI' : 'KREDIT' }}</td>
                            <td class="center" style="background-color: {{ $color_status }}; color: white">{{ $ket_status }}</td>
                            <td class="right">{{ formatAngka($d->total_bruto) }}</td>
                            <td class="right">{{ formatAngka($d->total_bayar) }}</td>
                            <td class="right">{{ formatAngka($sisabayar) }}</td>
                            <td>{{ $d->jenis_transaksi == 'T' && $d->status == '0' ? 'Belum Posting' : '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" class="center">TOTAL</th>
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
