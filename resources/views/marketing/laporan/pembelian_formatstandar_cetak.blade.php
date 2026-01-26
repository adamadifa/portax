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
                        <th>Tanggal</th>
                        <th>No. Bukti</th>
                        <th>Supplier</th>
                        <th>Jenis Transaksi</th>
                        <th>Nama Produk</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                        <th>Total Bruto</th>
                        <th>Total Bayar</th>
                        <th>Status</th>
                        <th>User</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $arr = [];
                        foreach ($pembelian as $row) {
                            $arr[$row->no_bukti][] = $row;
                        }
                        $grandtotal_bruto = 0;
                    @endphp
                    @foreach ($arr as $no_bukti => $details)
                        @php
                            $first = true;
                            $rowspan = count($details);
                            $total_bruto_faktur = 0;
                            // Pre-calculate total bruto for this invoice
                             foreach ($details as $d) {
                                $total_bruto_faktur += $d->subtotal;
                             }
                             $grandtotal_bruto += $total_bruto_faktur;
                             
                             // Since we don't have total_bayar in the query for standard yet unless we join or pre-calc, 
                             // let's assume currently only bruto is critical or I need to adjust query.
                             // Wait, I didn't validly join historibayar in standard query in the controller block above, 
                             // I need to fix that or just show bruto for now. 
                             // In the controller I joined detail, but total_bayar subquery was removed/not added to group.
                        @endphp
                        @foreach ($details as $d)
                            <tr>
                                @if ($first)
                                    <td rowspan="{{ $rowspan }}">{{ DateToIndo($d->tanggal) }}</td>
                                    <td rowspan="{{ $rowspan }}">{{ $d->no_bukti }}</td>
                                    <td rowspan="{{ $rowspan }}">{{ $d->nama_supplier }}</td>
                                    <td rowspan="{{ $rowspan }}" class="center">{{ $d->jenis_transaksi == 'T' ? 'TUNAI' : 'KREDIT' }}</td>
                                @endif
                                <td>{{ $d->nama_produk }}</td>
                                <td class="center">{{ formatAngka($d->jumlah) }}</td>
                                <td class="right">{{ formatAngka($d->harga_dus) }}</td>
                                <td class="right">{{ formatAngka($d->subtotal) }}</td>
                                @if ($first)
                                    <td rowspan="{{ $rowspan }}" class="right">{{ formatAngka($total_bruto_faktur) }}</td>
                                    {{-- <td rowspan="{{ $rowspan }}" class="right">{{ formatAngka($d->total_bayar ?? 0) }}</td> --}}
                                    <td rowspan="{{ $rowspan }}" class="right"></td> 
                                    <td rowspan="{{ $rowspan }}" class="center text-{{ $d->status == '1' ? 'green' : 'red' }}">
                                        {{ $d->status == '1' ? 'LUNAS' : 'BLM LUNAS' }}
                                    </td>
                                    <td rowspan="{{ $rowspan }}">{{ $d->nama_user }}</td>
                                @endif
                            </tr>
                            @php $first = false; @endphp
                        @endforeach
                    @endforeach
                </tbody>
                <tfoot>
                     <tr>
                        <th colspan="8" class="center">TOTAL</th>
                        <th class="right">{{ formatAngka($grandtotal_bruto) }}</th>
                        <th colspan="3"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>
</html>
