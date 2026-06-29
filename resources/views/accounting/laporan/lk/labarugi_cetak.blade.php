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
    @if(!isset($_POST['exportButton']))
    <div class="lock-bar" style="background: #f8f9fa; border-bottom: 1px solid #dee2e6; padding: 10px 20px; display: flex; align-items: center; justify-content: space-between; font-family: sans-serif; margin-bottom: 20px;">
        <div style="font-size: 13px;">
            Status Laporan: 
            @if($is_locked)
                <span style="color: #d9534f; font-weight: bold;">🔒 TERKUNCI</span>
                <span style="font-size: 12px; color: #777;">(Oleh User ID: {{ $lock_info->user_id }} pada {{ $lock_info->created_at }})</span>
            @else
                <span style="color: #5cb85c; font-weight: bold;">🔓 TERBUKA (DINAMIS)</span>
            @endif
        </div>
        <div>
            @if($is_locked)
                <button onclick="bukaKunci()" style="background: #d9534f; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 12px;">Buka Kunci Laporan</button>
            @else
                <button onclick="kunciLaporan()" style="background: #5cb85c; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 12px;">Kunci Laporan</button>
            @endif
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function kunciLaporan() {
            if(confirm('Apakah Anda yakin ingin mengunci laporan keuangan periode ini? Nilai akhir saldo akan disimpan tetap.')) {
                $.post('{{ route("laporanaccounting.kuncilaporan") }}', {
                    _token: '{{ csrf_token() }}',
                    bulan: '{{ $bulan_angka }}',
                    tahun: '{{ $tahun_angka }}',
                    kode_cabang: '{{ $kode_cabang_param }}'
                }, function(res) {
                    alert(res.message);
                    location.reload();
                }).fail(function(xhr) {
                    alert(xhr.responseJSON?.message || 'Gagal mengunci laporan.');
                });
            }
        }
        function bukaKunci() {
            if(confirm('Apakah Anda yakin ingin membuka kunci laporan keuangan periode ini? Laporan akan kembali dihitung dinamis.')) {
                $.post('{{ route("laporanaccounting.bukakuncilaporan") }}', {
                    _token: '{{ csrf_token() }}',
                    bulan: '{{ $bulan_angka }}',
                    tahun: '{{ $tahun_angka }}',
                    kode_cabang: '{{ $kode_cabang_param }}'
                }, function(res) {
                    alert(res.message);
                    location.reload();
                }).fail(function(xhr) {
                    alert(xhr.responseJSON?.message || 'Gagal membuka kunci laporan.');
                });
            }
        }
    </script>
    <style>
        @media print {
            .lock-bar {
                display: none !important;
            }
        }
    </style>
    @endif

    <div class="header">
        <h4 class="company-name">{{ $nama_pt }}</h4>
        <h2 class="report-title">Laba Rugi</h2>
        <h4 class="period">Period {{ DateToIndo($dari) }} to {{ DateToIndo($sampai) }}</h4>
    </div>

    @php
        // Map balances by code for easy retrieval
        $balances = [];
        $names = [];
        $levels = [];
        foreach ($labarugi as $coa) {
            $balances[$coa->kode_akun] = (float)($coa->saldo_akhir ?? 0);
            $names[$coa->kode_akun] = $coa->nama_akun;
            $levels[$coa->kode_akun] = (int)$coa->level;
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
            if (str_starts_with($code, '61') && ($levels[$code] ?? 0) > 1) {
                $beban_penjualan_list[$code] = [
                    'nama' => $code . ' - ' . ($names[$code] ?? ''),
                    'val' => $val
                ];
                $total_beban_penjualan += $val;
            }
        }

        // Sort by code keys
        ksort($beban_penjualan_list);

        // 5. Beban Operasi - Biaya Umum & Administrasi
        $gaji_tunjangan = $balances['62001'] ?? 0;
        $komisi = $balances['62002'] ?? 0;
        $total_gaji_komisi = $gaji_tunjangan + $komisi;

        // Jasa
        $sewa_bangunan = $balances['63001'] ?? 0;
        $sewa_angkutan = $balances['63002'] ?? 0;
        $sewa_mesin_fc = $balances['63003'] ?? 0;
        
        $jasa_list = [
            '63001' => [
                'nama' => '63001 - Sewa BANGUNAN',
                'val' => $sewa_bangunan
            ],
            '63002' => [
                'nama' => '63002 - Sewa Angkutan',
                'val' => $sewa_angkutan
            ],
            '63003' => [
                'nama' => '63003 - SEWA MESIN FC',
                'val' => $sewa_mesin_fc
            ]
        ];
        // Gather any other remaining 6xxxx codes where level > 1
        foreach ($balances as $code => $val) {
            if (str_starts_with($code, '6') && 
                !str_starts_with($code, '61') && 
                !str_starts_with($code, '62') && 
                $code !== '63001' && 
                $code !== '63002' && 
                $code !== '63003' && 
                ($levels[$code] ?? 0) > 1) {
                $jasa_list[$code] = [
                    'nama' => $code . ' - ' . strtoupper($names[$code] ?? ''),
                    'val' => $val
                ];
            }
        }
        ksort($jasa_list);

        $total_jasa = array_sum(array_column($jasa_list, 'val'));

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
                @php renderLabaRugiRow('41001 - Penjualan', $penjualan, 2, false, false, true); @endphp
                @php renderLabaRugiRow('Jumlah Pendapatan', $total_pendapatan, 0, true, false, false); @endphp

                <!-- Empty space -->
                <tr><td colspan="2" style="height: 10px;"></td></tr>

                <!-- 2. Harga Pokok Penjualan -->
                @php renderLabaRugiRow('Harga Pokok Penjualan', null, 0, true, true, false); @endphp
                @php renderLabaRugiRow('52001 - Persediaan Awal', $persediaan_awal, 1, false, false, true); @endphp
                @php renderLabaRugiRow('51001 - Pembelian', $pembelian, 1, false, false, true); @endphp
                @php renderLabaRugiRow('52002 - Persediaan Akhir', $abs_persediaan_akhir != 0 ? -$abs_persediaan_akhir : 0, 1, false, false, true); @endphp
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
                @php renderLabaRugiRow('Jumlah Beban Penjualan', $total_beban_penjualan, 1, true, false, false); @endphp

                <!-- Biaya Umum & Administrasi -->
                @php renderLabaRugiRow('Biaya Umum & Administrasi', null, 1, true, false, false); @endphp
                @php renderLabaRugiRow('Gaji & Tunjangan Karyawan', null, 2, true, false, false); @endphp
                @php renderLabaRugiRow('62001 - GAJI, TUNJANGAN, DLL', $gaji_tunjangan, 3, false, false, true); @endphp
                @php renderLabaRugiRow('62002 - Komisi', $komisi, 3, false, false, true); @endphp
                @php renderLabaRugiRow('Jumlah Gaji & Tunjangan Karyawan', $total_gaji_komisi, 2, true, false, false); @endphp
                
                <!-- Jasa -->
                @php renderLabaRugiRow('JASA', null, 2, true, false, false); @endphp
                @foreach ($jasa_list as $item)
                    @php renderLabaRugiRow($item['nama'], $item['val'], 3, false, false, true); @endphp
                @endforeach
                @php renderLabaRugiRow('Jumlah JASA', $total_jasa, 2, true, false, false); @endphp

                @php renderLabaRugiRow('Jumlah Biaya Umum & Administrasi', $total_umum_adm, 1, true, false, false); @endphp
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

