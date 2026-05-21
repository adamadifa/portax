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
        // Group coas by parent code (sub_akun)
        $nodesByParent = [];
        foreach ($labarugi as $coa) {
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

        // Separate Pendapatan, Biaya, and Ikhtisar Laba Rugi
        $pendapatanTree = [];
        $biayaTree = [];
        $ikhtisarTree = [];

        foreach ($tree as $rootNode) {
            if ($rootNode['kode_akun'] == '40000') {
                $pendapatanTree = [$rootNode];
            } elseif ($rootNode['kode_akun'] == '50000') {
                $biayaTree = [$rootNode];
            } elseif ($rootNode['kode_akun'] == '60000') {
                $ikhtisarTree = [$rootNode];
            }
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
        calculateTreeBalances($pendapatanTree);
        calculateTreeBalances($biayaTree);
        calculateTreeBalances($ikhtisarTree);

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
                        echo '<td class="text-right">' . ($node['saldo_akhir'] != 0 ? formatAngkaDesimal($node['saldo_akhir']) : '-') . '</td>';
                        echo '</tr>';
                    } else {
                        // Leaf account
                        echo '<tr>';
                        echo '<td style="padding-left: ' . $indent . 'px;">' . $node['kode_akun'] . ' &nbsp; ' . $node['nama_akun'] . '</td>';
                        echo '<td class="text-right">' . ($node['saldo_akhir'] != 0 ? formatAngkaDesimal($node['saldo_akhir']) : '-') . '</td>';
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
                <!-- 1. Render PENDAPATAN Tree -->
                @if (!empty($pendapatanTree))
                    @php renderTree($pendapatanTree, 0); @endphp
                @endif

                <!-- Empty space -->
                <tr>
                    <td colspan="2" style="height: 15px;"></td>
                </tr>

                <!-- 2. Render BIAYA Tree -->
                @if (!empty($biayaTree))
                    @php renderTree($biayaTree, 0); @endphp
                @endif

                <!-- Empty space -->
                <tr>
                    <td colspan="2" style="height: 15px;"></td>
                </tr>

                <!-- 3. Render IKHTISAR LABA RUGI Tree -->
                @if (!empty($ikhtisarTree))
                    @php renderTree($ikhtisarTree, 0); @endphp
                @endif

                <!-- Empty space -->
                <tr>
                    <td colspan="2" style="height: 25px;"></td>
                </tr>

                <!-- Grand Total Laba/Rugi Bersih -->
                <tr class="subtotal-row-grand">
                    <td style="font-weight: bold;">Laba (Rugi) Bersih</td>
                    <td class="text-right" style="font-weight: bold;">
                        {{ $net_profit_loss != 0 ? formatAngkaDesimal($net_profit_loss) : '-' }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
