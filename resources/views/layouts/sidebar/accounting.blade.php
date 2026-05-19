@if (auth()->user()->hasAnyPermission([
            'coa_portax.index',
            'costratio.index',
            'jurnalumum.index',
            'hpp.index',
            'hargawalahpp.index',
            'akt.rekapbj',
            'akt.rekappersediaan',
            'akt.costratio',
            'akt.jurnalumum',
            'saldoawalbukubesar.index',
        ]))
    <li
        class="menu-item {{ request()->is(['coaportax', 'costratio', 'jurnalumum', 'hpp', 'hargaawalhpp', 'laporanaccounting', 'saldoawalbukubesar', 'saldoawalbukubesar/*']) ? 'open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon ti ti-scale"></i>
            <div>Accounting</div>
        </a>
        <ul class="menu-sub">
            @if (auth()->user()->hasAnyPermission(['coa_portax.index']))
                <li class="menu-item {{ request()->is(['coaportax']) ? 'active' : '' }}">
                    <a href="{{ route('coa_portax.index') }}" class="menu-link">
                        <div>Chart of Account (COA)</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['saldoawalbukubesar.index']))
                <li class="menu-item {{ request()->is(['saldoawalbukubesar', 'saldoawalbukubesar/*']) ? 'active' : '' }}">
                    <a href="{{ route('saldoawalbukubesar.index') }}" class="menu-link">
                        <div>Saldo Awal Buku Besar</div>
                    </a>
                </li>
            @endif
            <!-- @if (auth()->user()->hasAnyPermission(['costratio.index']))
                <li class="menu-item {{ request()->is(['costratio']) ? 'active' : '' }}">
                    <a href="{{ route('costratio.index') }}" class="menu-link">
                        <div>Cost Ratio</div>
                    </a>
                </li>
            @endif -->
            @if (auth()->user()->hasAnyPermission(['jurnalumum.index']))
                <li class="menu-item {{ request()->is(['jurnalumum']) ? 'active' : '' }}">
                    <a href="{{ route('jurnalumum.index') }}" class="menu-link">
                        <div>Jurnal Umum</div>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasAnyPermission(['hpp.index', 'hargawalahpp.index']))
                <li class="menu-item {{ request()->is(['hpp', 'hargaawalhpp', 'hargaawalhpp/*']) ? 'active' : '' }}">
                    <a href="{{ route('hpp.index') }}" class="menu-link">
                        <div>HPP</div>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasAnyPermission(['akt.rekapbj', 'akt.rekappersediaan', 'akt.costratio', 'akt.jurnalumum']))
                <li class="menu-item {{ request()->is(['laporanaccounting', 'laporanaccounting/*']) ? 'active' : '' }}">
                    <a href="{{ route('laporanaccounting.index') }}" class="menu-link">
                        <div>Laporan</div>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif
