<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laba Rugi {{ date('Y-m-d H:i:s') }}</title>
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
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            color: #000;
        }

        .header .report-title {
            font-size: 22px;
            font-weight: bold;
            margin: 5px 0;
            color: #900000;
        }

        .header .period {
            font-size: 14px;
            margin: 5px 0 0 0;
            color: #333;
            font-weight: bold;
        }

        .content {
            margin: 0 auto;
            max-width: 900px;
        }

        .datatable9 {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .datatable9 th {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 8px 12px;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .datatable9 td {
            padding: 5px 12px;
            font-size: 12px;
            vertical-align: middle;
            height: 20px;
        }

        /* Subtotal/Total Rows */
        .subtotal-row td {
            font-weight: bold !important;
            border-top: 1px solid #000;
            border-bottom: 2px double #000;
            padding-top: 6px;
            padding-bottom: 6px;
        }

        .subtotal-row-grand td {
            font-weight: bold !important;
            border-top: 1.5px solid #000;
            border-bottom: 2px double #000;
            padding-top: 8px;
            padding-bottom: 8px;
            font-size: 13px;
        }

        .section-header td {
            font-weight: bold;
            font-size: 13px;
            padding-top: 10px;
            padding-bottom: 4px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h4 class="company-name">{{ $nama_pt }}</h4>
        <h2 class="report-title">Laba Rugi</h2>
        <h4 class="period">Period {{ DateToIndo($dari) }} to {{ DateToIndo($sampai) }}</h4>
    </div>

    @php
        // Map balances by code for easy retrieval
        $balances = [];
        $names = [];
        foreach ($labarugi as $coa) {
            $balances[$coa->kode_akun] = (float)($coa->saldo_akhir ?? 0);
            $names[$coa->kode_akun] = $coa->nama_akun;
        }

        // Helper function to render a row
        if (!function_exists('renderLabaRugiRow')) {
            function renderLabaRugiRow($label, $value, $indent = 0, $isBold = false, $isHeader = false, $hideIfZero = true) {
                if ($hideIfZero && $value !== null && $value == 0) {
                    return;
                }
                $indentStyle = $indent > 0 ? 'padding-left: ' . ($indent * 20) . 'px;' : '';
                $boldStyle = $isBold ? 'font-weight: bold;' : '';
                $class = $isHeader ? 'section-header' : '';
                
                echo '<tr class="' . $class . '">';
                echo '<td style="' . $indentStyle . $boldStyle . '">' . $label . '</td>';
                echo '<td class="text-right" style="' . $boldStyle . '">';
                if ($value !== null) {
                    if ($value < 0) {
                        echo '(' . formatAngkaDesimal(abs($value)) . ')';
                    } elseif ($value > 0) {
                        echo formatAngkaDesimal($value);
                    } else {
                        echo '-';
                    }
                }
                echo '</td>';
                echo '</tr>';
            }
        }

        // 1. Pendapatan
        $penjualan = $balances['41001'] ?? 0;
        $total_pendapatan = $penjualan;

        // 2. HPP
        $persediaan_awal = $balances['52001'] ?? 0;
        $pembelian = $balances['51001'] ?? 0;
        $persediaan_akhir = $balances['52002'] ?? 0;
        // Persediaan akhir reduces HPP, handle as subtraction
        $abs_persediaan_akhir = abs($persediaan_akhir);
        $total_hpp = $persediaan_awal + $pembelian - $abs_persediaan_akhir;

        // 3. Laba Kotor
        $laba_kotor = $total_pendapatan - $total_hpp;

        // 4. Beban Operasi - Beban Penjualan
        $beban_penjualan_list = [];
        $total_beban_penjualan = 0;
        foreach ($balances as $code => $val) {
            if (str_starts_with($code, '61') && $code !== '61000') {
                $beban_penjualan_list[$code] = [
                    'nama' => $names[$code] ?? '',
                    'val' => $val
                ];
                $total_beban_penjualan += $val;
            }
        }
        // Add Sewa Bangunan (63001) and Sewa Angkutan (63002) to Beban Penjualan list
        $sewa_bangunan = $balances['63001'] ?? 0;
        $sewa_angkutan = $balances['63002'] ?? 0;
        
        $beban_penjualan_list['63001'] = [
            'nama' => 'Sewa BANGUNAN',
            'val' => $sewa_bangunan
        ];
        $beban_penjualan_list['63002'] = [
            'nama' => 'Sewa Angkutan',
            'val' => $sewa_angkutan
        ];
        $total_beban_penjualan += $sewa_bangunan + $sewa_angkutan;

        // Sort by code keys
        ksort($beban_penjualan_list);

        // 5. Beban Operasi - Biaya Umum & Administrasi
        $gaji_tunjangan = $balances['62001'] ?? 0;
        $komisi = $balances['62002'] ?? 0;
        $total_gaji_komisi = $gaji_tunjangan + $komisi;

        // Jasa
        $sewa_mesin_fc = $balances['63003'] ?? 0;
        $total_jasa = $sewa_mesin_fc;
        
        $jasa_list = [
            '63003' => [
                'nama' => 'SEWA MESIN FC',
                'val' => $sewa_mesin_fc
            ]
        ];
        // Gather any other remaining 6xxxx codes
        foreach ($balances as $code => $val) {
            if (str_starts_with($code, '6') && 
                !str_starts_with($code, '61') && 
                !str_starts_with($code, '62') && 
                $code !== '63001' && 
                $code !== '63002' && 
                $code !== '63003' && 
                $code !== '60000') {
                $jasa_list[$code] = [
                    'nama' => strtoupper($names[$code] ?? ''),
                    'val' => $val
                ];
                $total_jasa += $val;
            }
        }
        ksort($jasa_list);

        $total_umum_adm = $total_gaji_komisi + $total_jasa;
        $total_beban_operasi = $total_beban_penjualan + $total_umum_adm;

        // 6. Pendapatan Operasi
        $pendapatan_operasi = $laba_kotor - $total_beban_operasi;

        // 7. Pendapatan dan Beban Lain
        $pendapatan_lain = $balances['42001'] ?? 0;
        foreach ($balances as $code => $val) {
            if (str_starts_with($code, '42') && $code !== '42001' && $code !== '42000') {
                $ppnkeluaran_val = $val; // Dummy variable to represent values
                $pendapatan_lain += $val;
            }
        }
        
        $beban_lain = 0;
        foreach ($balances as $code => $val) {
            if (str_starts_with($code, '9')) {
                $beban_lain += $val;
            }
        }
        $total_lain_lain = $pendapatan_lain - $beban_lain;

        // 8. Laba Rugi Bersih Before Tax
        $laba_bersih_before_tax = $pendapatan_operasi + $total_lain_lain;

        // PPh Expenses
        $pph_terutang = 0;
        $pph_25 = 0;
        $pph_29 = 0;
        $laba_bersih_after_tax = $laba_bersih_before_tax - $pph_terutang - $pph_25 - $pph_29;
    @endphp

    <div class="content">
        <table class="datatable9">
            <thead>
                <tr>
                    <th style="text-align: left; width: 80%;">Description</th>
                    <th class="text-right" style="width: 20%;">
                        {{ !empty($sampai) ? date('M-y', strtotime($sampai)) : date('M-y') }}</th>
                </tr>
            </thead>
            <tbody>
                <!-- 1. Pendapatan -->
                @php renderLabaRugiRow('Pendapatan', null, 0, true, true, false); @endphp
                @php renderLabaRugiRow('Pendapatan', null, 1, true, false, false); @endphp
                @php renderLabaRugiRow('Penjualan', $penjualan, 2, false, false, true); @endphp
                @php renderLabaRugiRow('Jumlah Pendapatan', $total_pendapatan, 0, true, false, false); @endphp

                <!-- Empty space -->
                <tr><td colspan="2" style="height: 10px;"></td></tr>

                <!-- 2. Harga Pokok Penjualan -->
                @php renderLabaRugiRow('Harga Pokok Penjualan', null, 0, true, true, false); @endphp
                @php renderLabaRugiRow('Persediaan Awal', $persediaan_awal, 1, false, false, true); @endphp
                @php renderLabaRugiRow('Pembelian', $pembelian, 1, false, false, true); @endphp
                @php renderLabaRugiRow('Persediaan Akhir', $abs_persediaan_akhir != 0 ? -$abs_persediaan_akhir : 0, 1, false, false, true); @endphp
                @php renderLabaRugiRow('Jumlah Harga Pokok Penjualan', $total_hpp, 0, true, false, false); @endphp

                <!-- Empty space -->
                <tr><td colspan="2" style="height: 10px;"></td></tr>

                <!-- 3. Laba Kotor -->
                @php renderLabaRugiRow('LABA KOTOR', $laba_kotor, 0, true, true, false); @endphp

                <!-- Empty space -->
                <tr><td colspan="2" style="height: 10px;"></td></tr>

                <!-- 4. Beban Operasi -->
                @php renderLabaRugiRow('Beban Operasi', null, 0, true, true, false); @endphp
                
                <!-- Beban Penjualan -->
                @php renderLabaRugiRow('BEBAN PENJUALAN', null, 1, true, false, false); @endphp
                @foreach ($beban_penjualan_list as $item)
                    @php renderLabaRugiRow($item['nama'], $item['val'], 2, false, false, true); @endphp
                @endforeach

                <!-- Biaya Umum & Administrasi -->
                @php renderLabaRugiRow('Biaya Umum & Administrasi', null, 1, true, false, false); @endphp
                @php renderLabaRugiRow('Gaji & Tunjangan Karyawan', null, 2, true, false, false); @endphp
                @php renderLabaRugiRow('GAJI, TUNJANGAN, DLL', $gaji_tunjangan, 3, false, false, true); @endphp
                @php renderLabaRugiRow('Komisi', $komisi, 3, false, false, true); @endphp
                
                <!-- Jasa -->
                @php renderLabaRugiRow('JASA', null, 2, true, false, false); @endphp
                @foreach ($jasa_list as $item)
                    @php renderLabaRugiRow($item['nama'], $item['val'], 3, false, false, true); @endphp
                @endforeach

                @php renderLabaRugiRow('Jumlah Beban Operasi', $total_beban_operasi, 0, true, false, false); @endphp

                <!-- Empty space -->
                <tr><td colspan="2" style="height: 10px;"></td></tr>

                <!-- 5. Pendapatan Operasi -->
                @php renderLabaRugiRow('PENDAPATAN OPERASI', $pendapatan_operasi, 0, true, true, false); @endphp

                <!-- Empty space -->
                <tr><td colspan="2" style="height: 10px;"></td></tr>

                <!-- 6. Pendapatan dan Beban Lain -->
                @php renderLabaRugiRow('Pendapatan dan Beban Lain', null, 0, true, true, false); @endphp
                @php renderLabaRugiRow('Pendapatan lain', $pendapatan_lain, 1, true, false, false); @endphp
                @php renderLabaRugiRow('Jumlah Pendapatan lain', $pendapatan_lain, 1, true, false, false); @endphp
                @php renderLabaRugiRow('Beban lain-lain', $beban_lain, 1, true, false, false); @endphp
                @php renderLabaRugiRow('Jumlah Beban lain-lain', $beban_lain, 1, true, false, false); @endphp
                @php renderLabaRugiRow('Jumlah Pendapatan dan Beban Lain', $total_lain_lain, 0, true, false, false); @endphp

                <!-- Empty space -->
                <tr><td colspan="2" style="height: 10px;"></td></tr>

                <!-- 7. Laba Bersih Before Tax -->
                @php renderLabaRugiRow('LABA(RUGI) BERSIH (Before Tax)', $laba_bersih_before_tax, 0, true, true, false); @endphp

                <!-- Empty space -->
                <tr><td colspan="2" style="height: 10px;"></td></tr>

                <!-- PPh -->
                @php renderLabaRugiRow('PPh terutang', $pph_terutang, 0, false, false, true); @endphp
                @php renderLabaRugiRow('PPh 25', $pph_25, 0, false, false, true); @endphp
                @php renderLabaRugiRow('PPh 29', $pph_29, 0, false, false, true); @endphp

                <!-- Empty space -->
                <tr><td colspan="2" style="height: 15px;"></td></tr>

                <!-- Grand Total Laba/Rugi Bersih After Tax -->
                <tr class="subtotal-row-grand">
                    <td style="font-weight: bold;">LABA(RUGI) BERSIH (After Tax)</td>
                    <td class="text-right" style="font-weight: bold;">
                        @if ($laba_bersih_after_tax < 0)
                            ({{ formatAngkaDesimal(abs($laba_bersih_after_tax)) }})
                        @elseif ($laba_bersih_after_tax > 0)
                            {{ formatAngkaDesimal($laba_bersih_after_tax) }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>

