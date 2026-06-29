<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laba Rugi Tahunan {{ $tahun }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #111;
            margin: 15px;
            background-color: #fff;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header .company-name {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            color: #000;
        }

        .header .report-title {
            font-size: 20px;
            font-weight: bold;
            margin: 5px 0;
            color: #900000;
        }

        .header .period {
            font-size: 13px;
            margin: 5px 0 0 0;
            color: #333;
            font-weight: bold;
        }

        .content {
            margin: 0 auto;
            width: 100%;
            overflow-x: auto;
        }

        .datatable9 {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .datatable9 th {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 6px 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
        }

        .datatable9 td {
            padding: 4px 4px;
            font-size: 10.5px;
            vertical-align: middle;
            height: 18px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Subtotal/Total Rows */
        .subtotal-row td {
            font-weight: bold !important;
            border-top: 1px solid #000;
            border-bottom: 1.5px solid #000;
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .subtotal-row-grand td {
            font-weight: bold !important;
            border-top: 1.5px solid #000;
            border-bottom: 2px double #000;
            padding-top: 6px;
            padding-bottom: 6px;
            font-size: 11px;
        }

        .section-header td {
            font-weight: bold;
            font-size: 11px;
            padding-top: 8px;
        }

        @media print {
            body {
                margin: 5px;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <h4 class="company-name">{{ $nama_pt }}</h4>
        <h2 class="report-title">Laba Rugi Tahunan</h2>
        <h4 class="period">Tahun {{ $tahun }} ({{ $nama_cabang }})</h4>
    </div>

    @php
        // Map names and levels
        $names = [];
        $levels = [];
        $beban_penjualan_codes = [];
        $jasa_codes = [];
        $pendapatan_lain_codes = [];
        $biaya_lain_codes = [];

        foreach ($labarugi as $coa) {
            $names[$coa->kode_akun] = $coa->nama_akun;
            $levels[$coa->kode_akun] = (int)$coa->level;

            $code = $coa->kode_akun;
            if (str_starts_with($code, '61') && $coa->level > 1) {
                $beban_penjualan_codes[$code] = true;
            }
            if (str_starts_with($code, '6') && !str_starts_with($code, '61') && !str_starts_with($code, '62') && $coa->level > 1) {
                $jasa_codes[$code] = true;
            }
            if (in_array($code, ['63001', '63002', '63003'])) {
                $jasa_codes[$code] = true;
            }
            if (str_starts_with($code, '8') && $coa->level > 1) {
                $pendapatan_lain_codes[$code] = true;
            }
            if (str_starts_with($code, '9') && $coa->level > 1) {
                $biaya_lain_codes[$code] = true;
            }
        }

        $beban_penjualan_codes = array_keys($beban_penjualan_codes);
        sort($beban_penjualan_codes);
        $jasa_codes = array_keys($jasa_codes);
        sort($jasa_codes);
        $pendapatan_lain_codes = array_keys($pendapatan_lain_codes);
        sort($pendapatan_lain_codes);
        $biaya_lain_codes = array_keys($biaya_lain_codes);
        sort($biaya_lain_codes);

        // Precalculate for all months
        $penjualan = [];
        $total_pendapatan = [];
        $persediaan_awal = [];
        $pembelian = [];
        $persediaan_akhir = [];
        $total_hpp = [];
        $laba_kotor = [];
        $total_beban_penjualan = [];
        $gaji_tunjangan = [];
        $komisi = [];
        $total_gaji_komisi = [];
        $total_jasa = [];
        $total_umum_adm = [];
        $total_beban_operasi = [];
        $pendapatan_operasi = [];
        $pendapatan_lain = [];
        $biaya_lain = [];
        $laba_bersih = [];

        for ($m = 1; $m <= 12; $m++) {
            $penjualan[$m] = $balances['41001'][$m] ?? 0;
            $total_pendapatan[$m] = $penjualan[$m];

            $persediaan_awal[$m] = $balances['52001'][$m] ?? 0;
            $pembelian[$m] = $balances['51001'][$m] ?? 0;
            $persediaan_akhir[$m] = $balances['52002'][$m] ?? 0;
            $total_hpp[$m] = $persediaan_awal[$m] + $pembelian[$m] - abs($persediaan_akhir[$m]);

            $laba_kotor[$m] = $total_pendapatan[$m] - $total_hpp[$m];

            $tot_bp = 0;
            foreach ($beban_penjualan_codes as $code) {
                $tot_bp += $balances[$code][$m] ?? 0;
            }
            $total_beban_penjualan[$m] = $tot_bp;

            $gaji_tunjangan[$m] = $balances['62001'][$m] ?? 0;
            $komisi[$m] = $balances['62002'][$m] ?? 0;
            $total_gaji_komisi[$m] = $gaji_tunjangan[$m] + $komisi[$m];

            $tot_jasa = 0;
            foreach ($jasa_codes as $code) {
                $tot_jasa += $balances[$code][$m] ?? 0;
            }
            $total_jasa[$m] = $tot_jasa;

            $total_umum_adm[$m] = $total_gaji_komisi[$m] + $total_jasa[$m];
            $total_beban_operasi[$m] = $total_beban_penjualan[$m] + $total_umum_adm[$m];

            $pendapatan_operasi[$m] = $laba_kotor[$m] - $total_beban_operasi[$m];

            $tot_pl = 0;
            foreach ($pendapatan_lain_codes as $code) {
                $tot_pl += $balances[$code][$m] ?? 0;
            }
            $pendapatan_lain[$m] = $tot_pl;

            $tot_bl = 0;
            foreach ($biaya_lain_codes as $code) {
                $tot_bl += $balances[$code][$m] ?? 0;
            }
            $biaya_lain[$m] = $tot_bl;

            $laba_bersih[$m] = $pendapatan_operasi[$m] + $pendapatan_lain[$m] - $biaya_lain[$m];
        }

        // Helper function to format negative values with parentheses
        if (!function_exists('formatLRValue')) {
            function formatLRValue($value, $hideIfZero = false) {
                if ($value === null || $value == 0) {
                    return $hideIfZero ? '' : '-';
                }
                if ($value < 0) {
                    return '(' . formatAngkaDesimal(abs($value)) . ')';
                }
                return formatAngkaDesimal($value);
            }
        }

        if (!function_exists('renderTahunanRow')) {
            function renderTahunanRow($label, $monthly_values, $indent = 0, $isBold = false, $isHeader = false, $hideIfZero = false) {
                // Check if all zero
                $allZero = true;
                for ($m = 1; $m <= 12; $m++) {
                    if (($monthly_values[$m] ?? 0) != 0) {
                        $allZero = false;
                        break;
                    }
                }
                if ($hideIfZero && $allZero) {
                    return;
                }

                $indentStyle = $indent > 0 ? 'padding-left: ' . ($indent * 12) . 'px;' : '';
                $boldStyle = $isBold ? 'font-weight: bold;' : '';
                $class = $isHeader ? 'section-header' : '';

                echo '<tr class="' . $class . '">';
                echo '<td style="' . $indentStyle . $boldStyle . '">' . $label . '</td>';
                for ($m = 1; $m <= 12; $m++) {
                    echo '<td class="text-right" style="' . $boldStyle . '">' . formatLRValue($monthly_values[$m] ?? 0, $hideIfZero) . '</td>';
                }
                echo '</tr>';
            }
        }
    @endphp

    <div class="content">
        <table class="datatable9">
            <thead>
                <tr>
                    <th style="text-align: left; width: 16%;">Description</th>
                    <th style="width: 7%;">Jan</th>
                    <th style="width: 7%;">Feb</th>
                    <th style="width: 7%;">Mar</th>
                    <th style="width: 7%;">Apr</th>
                    <th style="width: 7%;">Mei</th>
                    <th style="width: 7%;">Jun</th>
                    <th style="width: 7%;">Jul</th>
                    <th style="width: 7%;">Ags</th>
                    <th style="width: 7%;">Sep</th>
                    <th style="width: 7%;">Okt</th>
                    <th style="width: 7%;">Nov</th>
                    <th style="width: 7%;">Des</th>
                </tr>
            </thead>
            <tbody>
                <!-- 1. Pendapatan -->
                @php renderTahunanRow('PENDAPATAN', array_fill(1, 12, null), 0, true, true); @endphp
                @php renderTahunanRow('41001 - Penjualan', $penjualan, 1); @endphp
                @php renderTahunanRow('TOTAL PENDAPATAN', $total_pendapatan, 0, true, false); @endphp

                <!-- Spacer -->
                <tr><td colspan="13" style="height: 10px;"></td></tr>

                <!-- 2. HPP -->
                @php renderTahunanRow('HARGA POKOK PENJUALAN', array_fill(1, 12, null), 0, true, true); @endphp
                @php renderTahunanRow('52001 - Persediaan Awal', $persediaan_awal, 1); @endphp
                @php renderTahunanRow('51001 - Pembelian', $pembelian, 1); @endphp
                @php renderTahunanRow('52002 - Persediaan Akhir', $persediaan_akhir, 1); @endphp
                @php renderTahunanRow('TOTAL HARGA POKOK PENJUALAN', $total_hpp, 0, true, false); @endphp

                <!-- Spacer -->
                <tr><td colspan="13" style="height: 10px;"></td></tr>

                <!-- 3. Laba Kotor -->
                @php renderTahunanRow('LABA KOTOR', $laba_kotor, 0, true, false); @endphp

                <!-- Spacer -->
                <tr><td colspan="13" style="height: 10px;"></td></tr>

                <!-- 4. Beban Operasi - Beban Penjualan -->
                @php renderTahunanRow('Beban Penjualan', array_fill(1, 12, null), 0, true, true); @endphp
                @foreach ($beban_penjualan_codes as $code)
                    @php
                        $m_vals = [];
                        for ($m = 1; $m <= 12; $m++) {
                            $m_vals[$m] = $balances[$code][$m] ?? 0;
                        }
                        renderTahunanRow($code . ' - ' . ($names[$code] ?? ''), $m_vals, 1, false, false, true);
                    @endphp
                @endforeach
                @php renderTahunanRow('Total Beban Penjualan', $total_beban_penjualan, 1, true, false); @endphp

                <!-- Spacer -->
                <tr><td colspan="13" style="height: 10px;"></td></tr>

                <!-- 5. Beban Operasi - Biaya Umum & Administrasi -->
                @php renderTahunanRow('Biaya Umum & Administrasi', array_fill(1, 12, null), 0, true, true); @endphp
                @php renderTahunanRow('Gaji & Tunjangan', $gaji_tunjangan, 1); @endphp
                @php renderTahunanRow('Komisi', $komisi, 1); @endphp
                @php renderTahunanRow('Total Gaji & Komisi', $total_gaji_komisi, 1, true); @endphp

                <!-- Spacer -->
                <tr><td colspan="13" style="height: 5px;"></td></tr>

                <!-- Jasa -->
                @php renderTahunanRow('Jasa', array_fill(1, 12, null), 1, true, true); @endphp
                @foreach ($jasa_codes as $code)
                    @php
                        $m_vals = [];
                        for ($m = 1; $m <= 12; $m++) {
                            $m_vals[$m] = $balances[$code][$m] ?? 0;
                        }
                        renderTahunanRow($code . ' - ' . ($names[$code] ?? ''), $m_vals, 2, false, false, true);
                    @endphp
                @endforeach
                @php renderTahunanRow('Total Jasa', $total_jasa, 1, true); @endphp

                @php renderTahunanRow('Total Umum & Adm', $total_umum_adm, 1, true); @endphp

                <!-- Spacer -->
                <tr><td colspan="13" style="height: 10px;"></td></tr>

                @php renderTahunanRow('TOTAL BEBAN OPERASIONAL', $total_beban_operasi, 0, true); @endphp

                <!-- Spacer -->
                <tr><td colspan="13" style="height: 10px;"></td></tr>

                <!-- 6. Pendapatan Operasi -->
                @php renderTahunanRow('LABA (RUGI) OPERASIONAL', $pendapatan_operasi, 0, true); @endphp

                <!-- Spacer -->
                <tr><td colspan="13" style="height: 10px;"></td></tr>

                <!-- 7. Pendapatan dan Beban Lain -->
                @php renderTahunanRow('Pendapatan Lain-lain', array_fill(1, 12, null), 0, true, true); @endphp
                @foreach ($pendapatan_lain_codes as $code)
                    @php
                        $m_vals = [];
                        for ($m = 1; $m <= 12; $m++) {
                            $m_vals[$m] = $balances[$code][$m] ?? 0;
                        }
                        renderTahunanRow($code . ' - ' . ($names[$code] ?? ''), $m_vals, 1, false, false, true);
                    @endphp
                @endforeach
                @php renderTahunanRow('Total Pendapatan Lain-lain', $pendapatan_lain, 0, true); @endphp

                <!-- Spacer -->
                <tr><td colspan="13" style="height: 10px;"></td></tr>

                @php renderTahunanRow('Biaya Lain-lain', array_fill(1, 12, null), 0, true, true); @endphp
                @foreach ($biaya_lain_codes as $code)
                    @php
                        $m_vals = [];
                        for ($m = 1; $m <= 12; $m++) {
                            $m_vals[$m] = $balances[$code][$m] ?? 0;
                        }
                        renderTahunanRow($code . ' - ' . ($names[$code] ?? ''), $m_vals, 1, false, false, true);
                    @endphp
                @endforeach
                @php renderTahunanRow('Total Biaya Lain-lain', $biaya_lain, 0, true); @endphp

                <!-- Spacer -->
                <tr><td colspan="13" style="height: 15px;"></td></tr>

                <!-- 8. Laba Bersih -->
                <tr class="subtotal-row-grand">
                    <td style="font-weight: bold;">LABA (RUGI) BERSIH</td>
                    @for ($m = 1; $m <= 12; $m++)
                        <td class="text-right" style="font-weight: bold;">
                            @php echo formatLRValue($laba_bersih[$m] ?? 0); @endphp
                        </td>
                    @endfor
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
