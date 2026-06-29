<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Neraca Tahunan {{ $tahun }}</title>
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
        <h2 class="report-title">Neraca Tahunan</h2>
        <h4 class="period">Tahun {{ $tahun }} ({{ $nama_cabang }})</h4>
    </div>

    @php
        // Group coas by parent code (sub_akun)
        $nodesByParent = [];
        foreach ($neraca as $coa) {
            $node_monthly_balances = [];
            for ($m = 1; $m <= 12; $m++) {
                if ($coa->kode_akun == '33001') {
                    $node_monthly_balances[$m] = ($balances['33001'][$m] ?? 0.0) + ($net_profit_loss[$m] ?? 0.0);
                } else {
                    $node_monthly_balances[$m] = $balances[$coa->kode_akun][$m] ?? 0.0;
                }
            }
            $nodesByParent[$coa->sub_akun][] = [
                'kode_akun' => $coa->kode_akun,
                'nama_akun' => $coa->nama_akun,
                'sub_akun' => $coa->sub_akun,
                'level' => $coa->level,
                'monthly_balances' => $node_monthly_balances,
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
                $monthly_totals = array_fill(1, 12, 0.0);
                foreach ($nodes as &$node) {
                    if (count($node['children']) > 0) {
                        $node['monthly_balances'] = calculateTreeBalances($node['children']);
                    }
                    for ($m = 1; $m <= 12; $m++) {
                        $monthly_totals[$m] += $node['monthly_balances'][$m] ?? 0.0;
                    }
                }
                return $monthly_totals;
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
                    $indent = $level * 12;
                    $hasChildren = count($node['children']) > 0;

                    if ($hasChildren) {
                        // Category Header (Root or Sub-Root)
                        echo '<tr class="' . ($level == 0 ? 'section-header' : '') . '">';
                        echo '<td style="padding-left: ' . $indent . 'px; font-weight: bold;">' . $node['kode_akun'] . ' &nbsp; ' . $node['nama_akun'] . '</td>';
                        for ($m = 1; $m <= 12; $m++) {
                            echo '<td></td>';
                        }
                        echo '</tr>';

                        // Render children recursively
                        renderTree($node['children'], $level + 1);

                        // Render Subtotal/Jumlah row
                        echo '<tr class="subtotal-row">';
                        echo '<td style="padding-left: ' . $indent . 'px;">Jumlah ' . $node['nama_akun'] . '</td>';
                        for ($m = 1; $m <= 12; $m++) {
                            echo '<td class="text-right">' . formatNeracaValue($node['monthly_balances'][$m] ?? 0) . '</td>';
                        }
                        echo '</tr>';
                    } else {
                        // Leaf account
                        echo '<tr>';
                        echo '<td style="padding-left: ' . $indent . 'px;">' . $node['kode_akun'] . ' &nbsp; ' . $node['nama_akun'] . '</td>';
                        for ($m = 1; $m <= 12; $m++) {
                            echo '<td class="text-right">' . formatNeracaValue($node['monthly_balances'][$m] ?? 0) . '</td>';
                        }
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
                <!-- 1. Render AKTIVA Tree -->
                @if (!empty($aktivaTree))
                    @php renderTree($aktivaTree, 0); @endphp
                @endif

                <!-- Empty space separating Aktiva and Kewajiban & Ekuitas -->
                <tr>
                    <td colspan="13" style="height: 15px;"></td>
                </tr>

                <!-- 2. Section Heading: Kewajiban dan Ekuitas -->
                <tr class="section-header">
                    <td style="font-weight: bold; font-size: 11px; text-transform: uppercase;">Kewajiban dan Ekuitas</td>
                    @for ($m = 1; $m <= 12; $m++)
                        <td></td>
                    @endfor
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
                    @for ($m = 1; $m <= 12; $m++)
                        <td class="text-right" style="font-weight: bold;">
                            @php
                                $totalKewajiban = !empty($pasivaTree) ? ($pasivaTree[0]['monthly_balances'][$m] ?? 0) : 0;
                                $totalEkuitas = !empty($ekuitasTree) ? ($ekuitasTree[0]['monthly_balances'][$m] ?? 0) : 0;
                                $grandTotal = $totalKewajiban + $totalEkuitas;
                                echo formatNeracaValue($grandTotal);
                            @endphp
                        </td>
                    @endfor
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
