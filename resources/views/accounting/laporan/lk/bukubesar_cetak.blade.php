<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Buku Besar {{ date('Y-m-d H:i:s') }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #111;
            margin: 30px;
            background-color: #fff;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header .company-name {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            color: #000;
        }

        .header .report-title {
            font-size: 22px;
            font-weight: bold;
            margin: 5px 0;
            color: #1e3a8a; /* Premium Dark Blue */
        }

        .header .period {
            font-size: 13px;
            margin: 5px 0 0 0;
            color: #333;
            font-weight: bold;
        }

        .content {
            margin: 0 auto;
            max-width: 100%;
        }

        .account-title {
            text-align: left;
            font-size: 15px;
            font-weight: bold;
            color: #1e3a8a;
            margin-top: 35px;
            margin-bottom: 8px;
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 4px;
        }

        .datatable-ledger {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .datatable-ledger th {
            border-top: 2px solid #1e3a8a;
            border-bottom: 2px solid #1e3a8a;
            padding: 8px 10px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            background-color: #f8fafc;
            color: #1e3a8a;
        }

        .datatable-ledger td {
            padding: 6px 10px;
            font-size: 11px;
            vertical-align: middle;
            border-bottom: 1px solid #e2e8f0;
        }

        .row-saldo-awal {
            font-weight: bold;
            background-color: #f1f5f9;
        }

        .row-saldo-awal th {
            text-align: left;
            padding: 6px 10px;
            font-size: 11px;
            border-bottom: 1px solid #cbd5e1;
        }

        .row-total {
            font-weight: bold;
            background-color: #f8fafc;
        }

        .row-total th, .row-total td {
            font-weight: bold !important;
            border-top: 1.5px solid #1e3a8a;
            border-bottom: 2.5px double #1e3a8a;
            padding: 8px 10px;
            font-size: 11px;
            color: #1e3a8a;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-red {
            color: #ef4444;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h4 class="company-name">
            {{ $nama_pt }}
        </h4>
        <h2 class="report-title">BUKU BESAR</h2>
        <h4 class="period">PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        @if(!empty($nama_cabang))
            <h5 style="margin: 5px 0 0 0; font-size: 12px; color: #475569; font-weight: normal;">Cabang: {{ $nama_cabang }}</h5>
        @endif
    </div>

    <div class="content">
        @php
            $kode_akun = '';
            $total_debet = 0;
            $total_kredit = 0;
            $saldo = 0;
            $saldo_awal_kredit = 0;
            $saldo_awal_debet = 0;
            $open_table = false;
        @endphp

        @foreach ($bukubesar as $key => $d)
            @php
                $mutasi_debet = optional($mutasiakunCollection->firstWhere('kode_akun', $d->kode_akun))->total_debet ?? 0;
                $mutasi_kredit = optional($mutasiakunCollection->firstWhere('kode_akun', $d->kode_akun))->total_kredit ?? 0;
                $next_akun = isset($bukubesar[$key + 1]) ? $bukubesar[$key + 1]->kode_akun : null;
            @endphp

            @if ($kode_akun != $d->kode_akun)
                @if ($open_table)
                    </tbody>
                    </table>
                @endif

                @php
                    $saldo = 0;
                    $open_table = true;
                @endphp

                <div class="account-title">
                    Akun: {{ $d->kode_akun }} - {{ $d->nama_akun }}
                </div>

                <table class="datatable-ledger">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 10%;">TGL</th>
                            <th class="text-center" style="width: 12%;">NO BUKTI</th>
                            <th class="text-center" style="width: 15%;">SUMBER</th>
                            <th style="width: 33%;">KETERANGAN</th>
                            <th class="text-right" style="width: 10%;">DEBET</th>
                            <th class="text-right" style="width: 10%;">KREDIT</th>
                            <th class="text-right" style="width: 10%;">SALDO</th>
                        </tr>
                    </thead>
                    <tbody>
            @endif

            @php
                if ($d->jenis_akun == '1') {
                    $saldo += $d->jml_kredit - $d->jml_debet;
                } else {
                    $saldo += $d->jml_debet - $d->jml_kredit;
                }
                $total_debet = $total_debet + $d->jml_debet;
                $total_kredit = $total_kredit + $d->jml_kredit;
            @endphp

            @if ($d->sumber == 'SALDO AWAL')
                @if ($d->jenis_akun == '1')
                    @php
                        $saldo_awal_kredit = $saldo;
                        $saldo_awal_debet = 0;
                    @endphp
                @else
                    @php
                        $saldo_awal_kredit = 0;
                        $saldo_awal_debet = $saldo;
                    @endphp
                @endif
                <tr class="row-saldo-awal">
                    <td colspan="6" style="font-weight: bold;">SALDO AWAL</td>
                    <td class="text-right" style="font-weight: bold;">{{ formatAngkaDesimal($saldo) }}</td>
                </tr>
            @else
                <tr>
                    <td class="text-center">{{ formatIndo($d->tanggal) }}</td>
                    <td class="text-center">{{ $d->no_bukti }}</td>
                    <td class="text-center">{{ textUpperCase($d->sumber) }}</td>
                    <td>{{ textCamelCase($d->keterangan) }}</td>
                    <td class="text-right">{{ $d->jml_debet > 0 ? formatAngkaDesimal($d->jml_debet) : '-' }}</td>
                    <td class="text-right">{{ $d->jml_kredit > 0 ? formatAngkaDesimal($d->jml_kredit) : '-' }}</td>
                    <td class="text-right">{{ formatAngkaDesimal($saldo) }}</td>
                </tr>
            @endif

            @if ($next_akun != $d->kode_akun)
                <tr class="row-total">
                    <th colspan="4" class="text-right" style="text-align: right !important;">TOTAL {{ $d->kode_akun }} - {{ $d->nama_akun }}</th>
                    <td class="text-right">{{ formatAngkaDesimal($total_debet - $saldo_awal_debet) }}</td>
                    <td class="text-right">{{ formatAngkaDesimal($total_kredit - $saldo_awal_kredit) }}</td>
                    <td class="text-right">{{ formatAngkaDesimal($saldo) }}</td>
                </tr>
                @php
                    $total_debet = 0;
                    $total_kredit = 0;
                @endphp
            @endif

            @php
                $kode_akun = $d->kode_akun;
            @endphp
        @endforeach

        @if ($open_table)
            </tbody>
            </table>
        @endif
    </div>
</body>
</html>
