<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Neraca {{ date('Y-m-d H:i:s') }}</title>
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
            /* Dark red matching Neraca Multi Period standard */
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
        <h2 class="report-title">Neraca</h2>
        <h4 class="period">Period {{ DateToIndo($dari) }} to {{ DateToIndo($sampai) }}</h4>
    </div>

    @php
        // Group coas by parent code (sub_akun)
        $nodesByParent = [];
        foreach ($neraca as $coa) {
            $nodesByParent[$coa->sub_akun][] = [
                'kode_akun' => $coa->kode_akun,
                'nama_akun' => $coa->nama_akun,
                'sub_akun' => $coa->sub_akun,
                'level' => $coa->level,
                'saldo_akhir' => $coa->saldo_akhir ?? 0,
                'children' => []
            ];
        }

        // Recursive tree builder
        if (!function_exists('buildTree')) {
            function buildTree(&$nodesByParent, $parentId = '0')
            {
                $branch = [];
                if (isset($nodesByParent[$parentId])) {
                    foreach ($nodesByParent[$parentId] as $node) {
                        $node['children'] = buildTree($nodesByParent, $node['kode_akun']);
                        $branch[] = $node;
                    }
                }
                return $branch;
            }
        }

        // Build the top level trees
        $tree = buildTree($nodesByParent, '0');

        // Separate Aktiva, Pasiva (Kewajiban), and Ekuitas
        $aktivaTree = [];
        $pasivaTree = [];
        $ekuitasTree = [];

        foreach ($tree as $rootNode) {
            if ($rootNode['kode_akun'] == '10000') {
                $aktivaTree = [$rootNode];
            } elseif ($rootNode['kode_akun'] == '20000') {
                $pasivaTree = [$rootNode];
            } elseif ($rootNode['kode_akun'] == '30000') {
                $ekuitasTree = [$rootNode];
            }
        }

        // Rename PASIVA root node to "Kewajiban" as in the format
        if (!empty($pasivaTree)) {
            $pasivaTree[0]['nama_akun'] = 'Kewajiban';
        }

        // Recursive balance calculator
        if (!function_exists('calculateTreeBalances')) {
            function calculateTreeBalances(&$nodes)
            {
                $total = 0;
                foreach ($nodes as &$node) {
                    if (count($node['children']) > 0) {
                        $node['saldo_akhir'] = calculateTreeBalances($node['children']);
                    }
                    $total += $node['saldo_akhir'];
                }
                return $total;
            }
        }

        // Calculate recursive balances
        calculateTreeBalances($aktivaTree);
        calculateTreeBalances($pasivaTree);
        calculateTreeBalances($ekuitasTree);

        // Helper function to format negative values with parentheses
        if (!function_exists('formatNeracaValue')) {
            function formatNeracaValue($value) {
                if ($value === null || $value == 0) {
                    return '-';
                }
                if ($value < 0) {
                    return '(' . formatAngkaDesimal(abs($value)) . ')';
                }
                return formatAngkaDesimal($value);
            }
        }

        // Recursive tree rendering function
        if (!function_exists('renderTree')) {
            function renderTree($nodes, $level = 0)
            {
                foreach ($nodes as $node) {
                    $indent = $level * 20;
                    $hasChildren = count($node['children']) > 0;

                    if ($hasChildren) {
                        // Category Header (Root or Sub-Root)
                        echo '<tr class="' . ($level == 0 ? 'section-header' : '') . '">';
                        echo '<td style="padding-left: ' . $indent . 'px; font-weight: bold;">' . $node['kode_akun'] . ' &nbsp; ' . $node['nama_akun'] . '</td>';
                        echo '<td class="text-right"></td>';
                        echo '</tr>';

                        // Render children recursively
                        renderTree($node['children'], $level + 1);

                        // Render Subtotal/Jumlah row
                        echo '<tr class="subtotal-row">';
                        echo '<td style="padding-left: ' . $indent . 'px;">Jumlah ' . $node['nama_akun'] . '</td>';
                        echo '<td class="text-right">' . formatNeracaValue($node['saldo_akhir']) . '</td>';
                        echo '</tr>';
                    } else {
                        // Leaf account
                        echo '<tr>';
                        echo '<td style="padding-left: ' . $indent . 'px;">' . $node['kode_akun'] . ' &nbsp; ' . $node['nama_akun'] . '</td>';
                        echo '<td class="text-right">' . formatNeracaValue($node['saldo_akhir']) . '</td>';
                        echo '</tr>';
                    }
                }
            }
        }
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
                <!-- 1. Render AKTIVA Tree -->
                @if (!empty($aktivaTree))
                    @php renderTree($aktivaTree, 0); @endphp
                @endif

                <!-- Empty space separating Aktiva and Kewajiban & Ekuitas -->
                <tr>
                    <td colspan="2" style="height: 25px;"></td>
                </tr>

                <!-- 2. Section Heading: Kewajiban dan Ekuitas -->
                <tr class="section-header">
                    <td style="font-weight: bold; font-size: 14px; text-transform: uppercase;">Kewajiban dan Ekuitas
                    </td>
                    <td></td>
                </tr>

                <!-- Render Kewajiban (PASIVA) -->
                @if (!empty($pasivaTree))
                    @php renderTree($pasivaTree, 1); @endphp
                @endif

                <!-- Render Ekuitas -->
                @if (!empty($ekuitasTree))
                    @php renderTree($ekuitasTree, 1); @endphp
                @endif

                <!-- Grand Total Kewajiban dan Ekuitas -->
                <tr class="subtotal-row-grand">
                    <td style="font-weight: bold;">Jumlah Kewajiban dan Ekuitas</td>
                    <td class="text-right" style="font-weight: bold;">
                        @php
                            $totalKewajiban = !empty($pasivaTree) ? $pasivaTree[0]['saldo_akhir'] : 0;
                            $totalEkuitas = !empty($ekuitasTree) ? $ekuitasTree[0]['saldo_akhir'] : 0;
                            $grandTotal = $totalKewajiban + $totalEkuitas;
                            echo formatNeracaValue($grandTotal);
                        @endphp
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>