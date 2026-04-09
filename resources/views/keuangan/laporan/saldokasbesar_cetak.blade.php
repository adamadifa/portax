<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Saldo Kas Besar {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <style>
        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 30px;
            color: #333;
        }
        .header {
            margin-bottom: 40px; /* Increased spacing */
            text-align: left;
        }
        .header h2 {
            margin: 0;
            text-transform: uppercase;
            font-size: 20px;
            letter-spacing: 1px;
            color: #111;
        }
        .header h4 {
            margin: 15px 0; /* More spacing between lines */
            font-weight: normal;
            font-size: 14px;
            color: #444;
            line-height: 1.6;
        }
        .datatable3 {
            border-collapse: collapse;
            width: 100%;
            font-size: 11px;
            margin-top: 20px;
        }
        .datatable3 th, .datatable3 td {
            border: 1px solid #000;
            padding: 8px 5px;
        }
        .datatable3 thead tr th {
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }
        /* Formal Color Palette */
        .bg-penerimaan {
            background-color: #198754 !important; /* Formal Success Green */
            color: #fff !important;
        }
        .bg-pengeluaran {
            background-color: #dc3545 !important; /* Formal Danger Red */
            color: #fff !important;
        }
        .bg-saldo {
            background-color: #0d6efd !important; /* Formal Blue */
            color: #fff !important;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-bold {
            font-weight: bold;
        }
        tfoot tr th {
            background-color: #212529; /* Dark Footer */
            color: #fff;
            padding: 10px 5px;
        }
        .total-row {
            background-color: #e9ecef;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>SALDO KAS BESAR</h2>
        <h4>
            PERIODE : {{ $nama_bulan }} {{ $tahun }} <br>
            @if ($cabang != null)
                CABANG : {{ textUpperCase($cabang->nama_cabang) }}
            @endif
        </h4>
    </div>

    <div class="content">
        <table class="datatable3">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 80px;">TGL</th>
                    <th colspan="4" class="bg-penerimaan">PENERIMAAN</th>
                    <th colspan="{{ count($list_bank_pengeluaran) > 0 ? count($list_bank_pengeluaran) : 1 }}" class="bg-pengeluaran">PENGELUARAN</th>
                    <th class="bg-saldo">SALDO</th>
                </tr>
                <tr>
                    <th class="bg-penerimaan" style="width: 100px;">TUNAI</th>
                    <th class="bg-penerimaan" style="width: 100px;">GIRO</th>
                    <th class="bg-penerimaan" style="width: 100px;">TRANSFER</th>
                    <th class="bg-penerimaan" style="width: 110px;">TOTAL</th>
                    
                    @if (count($list_bank_pengeluaran) > 0)
                        @foreach ($list_bank_pengeluaran as $bank)
                            <th class="bg-pengeluaran" style="width: 100px;">{{ $bank->nama_bank }}</th>
                        @endforeach
                    @else
                        <th class="bg-pengeluaran" style="width: 100px;">LAINNYA</th>
                    @endif

                    <th class="bg-saldo" style="width: 130px;">KAS BESAR</th>
                </tr>
            </thead>
            @php
                $saldo_awal_val = $saldo_awal ? $saldo_awal->jumlah_saldo : 0;
                $saldo = $saldo_awal_val;
            @endphp
            <tbody>
                @php
                    $total_penerimaan_tunai = 0;
                    $total_penerimaan_giro = 0;
                    $total_penerimaan_transfer = 0;
                    $total_penerimaan_all = 0;
                    
                    $total_bank_pengeluaran = [];
                    foreach($list_bank_pengeluaran as $bank) {
                        $total_bank_pengeluaran[$bank->kode_bank] = 0;
                    }
                @endphp
                {{-- Saldo Awal Row --}}
                <tr style="background-color: #f8fafc; font-weight: bold;">
                    <td class="text-center">#</td>
                    <td colspan="4" class="text-left">SALDO AWAL</td>
                    @if (count($list_bank_pengeluaran) > 0)
                        @foreach ($list_bank_pengeluaran as $bank)
                            <td></td>
                        @endforeach
                    @else
                        <td></td>
                    @endif
                    <td class="text-right">{{ formatAngka($saldo_awal_val) }}</td>
                </tr>
                @foreach ($saldokasbesar as $d)
                    @php
                        $total_harian_penerimaan = $d->penerimaan_tunai + $d->penerimaan_giro + $d->penerimaan_transfer;
                        
                        $total_harian_pengeluaran = 0;
                        foreach($d->pengeluaran_banks as $kb => $val) {
                            $total_harian_pengeluaran += $val;
                            $total_bank_pengeluaran[$kb] += $val;
                        }

                        $saldo += ($total_harian_penerimaan - $total_harian_pengeluaran);

                        $total_penerimaan_tunai += $d->penerimaan_tunai;
                        $total_penerimaan_giro += $d->penerimaan_giro;
                        $total_penerimaan_transfer += $d->penerimaan_transfer;
                        $total_penerimaan_all += $total_harian_penerimaan;
                    @endphp
                    <tr>
                        <td class="text-center">{{ formatIndo($d->tanggal) }}</td>
                        <td class="text-right">{{ formatAngka($d->penerimaan_tunai) }}</td>
                        <td class="text-right">{{ formatAngka($d->penerimaan_giro) }}</td>
                        <td class="text-right">{{ formatAngka($d->penerimaan_transfer) }}</td>
                        <td class="text-right font-bold">{{ formatAngka($total_harian_penerimaan) }}</td>
                        
                        @if (count($list_bank_pengeluaran) > 0)
                            @foreach ($list_bank_pengeluaran as $bank)
                                <td class="text-right">{{ formatAngka($d->pengeluaran_banks[$bank->kode_bank] ?? 0) }}</td>
                            @endforeach
                        @else
                            <td class="text-right">0</td>
                        @endif

                        <td class="text-right font-bold" style="color: #0d6efd;">{{ formatAngka($saldo) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-center">TOTAL</th>
                    <th class="text-right">{{ formatAngka($total_penerimaan_tunai) }}</th>
                    <th class="text-right">{{ formatAngka($total_penerimaan_giro) }}</th>
                    <th class="text-right">{{ formatAngka($total_penerimaan_transfer) }}</th>
                    <th class="text-right">{{ formatAngka($total_penerimaan_all) }}</th>
                    
                    @if (count($list_bank_pengeluaran) > 0)
                        @foreach ($list_bank_pengeluaran as $bank)
                            <th class="text-right">{{ formatAngka($total_bank_pengeluaran[$bank->kode_bank] ?? 0) }}</th>
                        @endforeach
                    @else
                        <th class="text-right">0</th>
                    @endif

                    <th class="text-right">{{ formatAngka($saldo) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>
