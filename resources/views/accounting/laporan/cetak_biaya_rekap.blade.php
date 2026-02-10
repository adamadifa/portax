<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Biaya Rekap {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <style>
        .datatable3 th {
            font-size: 11px !important;
        }
    </style>
</head>

<body>
    <div class="header">
        <h4 class="title">
            LAPORAN BIAYA (REKAP)<br>
        </h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        @if ($cabang != 'Semua Cabang')
            <h4>CABANG: {{ $cabang }}</h4>
        @endif
    </div>
    <div class="content">
        <table class="datatable3">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>AKUN</th>
                    <th>JUMLAH</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $grand_total = 0;
                    $no = 1;
                @endphp
                @foreach ($biaya as $d)
                    @php
                        $jumlah = $d['total'];
                        $grand_total += $jumlah;
                    @endphp
                    <tr>
                        <td class="center">{{ $no++ }}</td>
                        <td>{{ $d['kode_akun'] }} - {{ $d['nama_akun'] }}</td>
                        <td class="right">{{ formatAngka($jumlah) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background-color: #333; color: white; font-weight: bold;">
                    <td colspan="2" class="center">TOTAL</td>
                    <td class="right">{{ formatAngka($grand_total) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>
