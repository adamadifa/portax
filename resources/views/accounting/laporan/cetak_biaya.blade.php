<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Biaya {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/libs/freeze/js/freeze-table.min.js') }}"></script>
    <style>
        .freeze-table {
            height: auto;
            max-height: 795px;
            overflow: auto;
        }

        .datatable3 th {
            font-size: 11px !important;
        }
    </style>
</head>

<body>
    <div class="header">
        <h4 class="title">
            LAPORAN BIAYA<br>
        </h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        @if ($cabang != 'Semua Cabang')
            <h4>CABANG: {{ $cabang }}</h4>
        @endif
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th>TANGGAL</th>
                        <th>AKUN</th>
                        <th>SUMBER</th>
                        <th>KETERANGAN</th>
                        <th>JUMLAH</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $grand_total = 0;
                    @endphp
                    @foreach ($biaya as $d)
                        @php
                            $jumlah = $d->jml_debet; // Assuming Expense is Debit
                            $grand_total += $jumlah;
                        @endphp
                        <tr>
                            <td>{{ DateToIndo($d->tanggal) }}</td>
                            <td>{{ $d->kode_akun }} - {{ $d->nama_akun }}</td>
                            <td>{{ $d->sumber }}</td>
                            <td>{{ $d->keterangan }}</td>
                            <td class="right">{{ formatAngka($jumlah) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background-color: #333; color: white; font-weight: bold;">
                        <td colspan="4" class="center">TOTAL</td>
                        <td class="right">{{ formatAngka($grand_total) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>

</html>
