<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme custom-sidebar">
    <style>
        .custom-sidebar {
            background: #1e3a8a !important;
            width: 280px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: white;
        }

        .sidebar-header {
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logo-shapes {
            display: flex;
            gap: 4px;
        }

        .logo-shape {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
        }

        .logo-shape-1 {
            background: #ec4899;
            color: white;
        }

        .logo-shape-2 {
            background: #3b82f6;
            color: white;
        }

        .logo-shape-3 {
            background: #8b5cf6;
            color: white;
        }

        .sidebar-title {
            font-size: 20px;
            font-weight: 700;
            color: white;
            margin: 0;
        }

        .team-card {
            margin: 20px;
            padding: 16px;
            background: rgba(59, 130, 246, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .team-card:hover {
            background: rgba(59, 130, 246, 0.3);
        }

        .team-icon {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .team-icon-dot {
            width: 20px;
            height: 20px;
            background: #1e3a8a;
            border-radius: 50%;
            position: relative;
        }

        .team-icon-dot::before,
        .team-icon-dot::after {
            content: '';
            position: absolute;
            width: 4px;
            height: 4px;
            background: white;
            border-radius: 50%;
        }

        .team-icon-dot::before {
            top: -8px;
            left: 50%;
            transform: translateX(-50%);
        }

        .team-icon-dot::after {
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
        }

        .team-info {
            flex: 1;
        }

        .team-name {
            font-weight: 600;
            font-size: 15px;
            color: white;
            margin: 0 0 4px 0;
        }

        .team-subtitle {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
            margin: 0;
        }

        .team-toggle {
            color: rgba(255, 255, 255, 0.7);
            font-size: 18px;
            cursor: pointer;
        }

        .sidebar-nav {
            flex: 1;
            padding: 0 12px;
            overflow-y: auto;
            position: relative;
        }

        /* Removed general .nav-link styles that affect tabs outside sidebar */


        .usage-card {
            margin: 20px;
            padding: 16px;
            background: rgba(59, 130, 246, 0.2);
            border-radius: 12px;
        }

        .usage-title {
            font-weight: 600;
            font-size: 14px;
            color: white;
            margin: 0 0 8px 0;
        }

        .usage-info {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
            margin: 0 0 12px 0;
        }

        .usage-progress {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .progress-bar-wrapper {
            flex: 1;
            height: 6px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            background: white;
            border-radius: 3px;
            transition: width 0.3s;
        }

        .progress-percentage {
            font-size: 12px;
            font-weight: 600;
            color: white;
            min-width: 40px;
            text-align: right;
        }

        .user-profile {
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .user-info {
            flex: 1;
        }

        .user-name {
            font-weight: 600;
            font-size: 14px;
            color: white;
            margin: 0;
        }

        .user-menu-toggle {
            color: rgba(255, 255, 255, 0.7);
            font-size: 18px;
            cursor: pointer;
            padding: 4px;
        }

        .user-menu-toggle:hover {
            color: white;
        }

        /* Scrollbar styling */
        .sidebar-nav::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* Hide default menu styles */
        .custom-sidebar .menu-inner-shadow {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 50px;
            pointer-events: none;
            background: linear-gradient(to bottom, rgba(30, 58, 138, 0.8), transparent);
            z-index: 1;
        }

        .custom-sidebar .app-brand {
            display: none;
        }

        /* Menu header styling */
        .custom-sidebar .menu-header {
            padding: 12px 16px 8px 16px;
            font-size: 11px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 8px;
        }

        /* Style for included menu items - keep original structure for dropdown */
        .custom-sidebar .menu-item {
            margin-bottom: 8px;
        }

        .custom-sidebar .menu-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 8px;
            color: rgba(255, 255, 255, 0.9) !important;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .custom-sidebar .menu-link:hover {
            background: rgba(255, 255, 255, 0.1) !important;
            color: white !important;
        }

        .custom-sidebar .menu-item.active .menu-link,
        .custom-sidebar .menu-item.open > .menu-link {
            background: rgba(59, 130, 246, 0.2) !important;
            color: white !important;
        }

        .custom-sidebar .menu-icon {
            font-size: 20px;
            width: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: inherit;
        }

        .custom-sidebar .menu-link div {
            font-size: 15px;
            font-weight: 500;
            color: inherit;
        }

        /* Submenu styling - remove all bullets */
        .custom-sidebar .menu-sub {
            list-style: none !important;
            list-style-type: none !important;
            padding: 8px 0 !important;
            margin: 8px 0 0 0 !important;
            padding-left: 36px !important;
            background: rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .custom-sidebar .menu-sub * {
            list-style: none !important;
            list-style-type: none !important;
        }

        .custom-sidebar .menu-sub .menu-item {
            margin-bottom: 2px;
            list-style: none !important;
            list-style-type: none !important;
            padding-left: 0 !important;
            margin-left: 0 !important;
        }

        .custom-sidebar .menu-sub .menu-item::before,
        .custom-sidebar .menu-sub .menu-item::after {
            display: none !important;
            content: none !important;
        }

        .custom-sidebar .menu-sub .menu-link {
            padding: 10px 8px !important;
            font-size: 14px;
            margin-left: 0 !important;
            padding-left: 8px !important;
        }

        .custom-sidebar .menu-sub .menu-link::before,
        .custom-sidebar .menu-sub .menu-link::after {
            display: none !important;
            content: none !important;
        }

        .custom-sidebar .menu-sub .menu-link div {
            font-size: 14px;
            padding-left: 0 !important;
            margin-left: 0 !important;
        }

        /* Remove any default list styling */
        .custom-sidebar .menu-sub li {
            list-style: none !important;
            list-style-type: none !important;
            padding-left: 0 !important;
        }

        .custom-sidebar .menu-sub li::marker,
        .custom-sidebar .menu-sub li::before {
            display: none !important;
            content: none !important;
        }

        /* Ensure no bullet points from any source */
        .custom-sidebar .menu-sub ul,
        .custom-sidebar .menu-sub ol {
            list-style: none !important;
            list-style-type: none !important;
            padding-left: 0 !important;
        }

        /* Ensure menu-toggle works */
        .custom-sidebar .menu-link.menu-toggle::after {
            content: '';
            margin-left: auto;
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid rgba(255, 255, 255, 0.7);
            transition: transform 0.3s;
        }

        .custom-sidebar .menu-item.open > .menu-link.menu-toggle::after {
            transform: rotate(180deg);
        }

        /* Adjust layout page for sidebar - add padding for spacing */
        .layout-menu-fixed .layout-page {
            margin-left: 0;
        }

        /* Add spacing between sidebar and content */
        .layout-menu-fixed .content-wrapper {
            padding-left: 24px;
        }

        @media (max-width: 991px) {
            .layout-menu-fixed .content-wrapper {
                padding-left: 16px;
            }
        }

        /* Mobile responsive */
        @media (max-width: 991px) {
            .custom-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }

            .custom-sidebar.menu-open {
                transform: translateX(0);
            }
        }
    </style>

    <!-- Header -->
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <div class="logo-shapes">
                <div class="logo-shape logo-shape-1">C</div>
                <div class="logo-shape logo-shape-2">C</div>
                <div class="logo-shape logo-shape-3">C</div>
            </div>
            <h1 class="sidebar-title">Integration</h1>
        </div>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto" style="color: white;">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>

    <!-- Navigation -->
    <div class="sidebar-nav">
        <div class="menu-inner-shadow"></div>
        <ul class="menu-inner py-1" style="list-style: none; padding: 0; margin: 0;">
            <li class="menu-item {{ request()->is(['dashboard', 'dashboard/*']) ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-chart-pie"></i>
                    <div>Dashboard</div>
                </a>
            </li>

            <!-- Additional Menu Items from Original -->
            @if (in_array($level_user, [
                    'super admin',
                    'direktur',
                    'gm administrasi',
                    'manager keuangan',
                    'regional operation manager',
                    'spv accounting',
                ]))
                <li class="menu-item {{ request()->is(['dashboard/owner']) ? 'active' : '' }}">
                    <a href="{{ route('dashboard.owner') }}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-home"></i>
                        <div>Dashboard Owner</div>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasAnyPermission(['dashboard.sfa']))
                <li class="menu-item {{ request()->is(['sfa/dashboard', 'sfa/dashboard/*']) ? 'active' : '' }}">
                    <a href="{{ route('dashboard.sfa') }}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-activity"></i>
                        <div>Dashboard SFA</div>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasAnyPermission(['sfa.trackingsalesman']))
                <li class="menu-item {{ request()->is(['sfa/trackingsalesman']) ? 'active' : '' }}">
                    <a href="{{ route('sfa.trackingsalesman') }}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-location"></i>
                        <div>Tracking Salesman</div>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasAnyPermission(['sfa.pelanggan']))
                <li class="menu-item {{ request()->is(['sfa/pelanggan']) ? 'active' : '' }}">
                    <a href="{{ route('sfa.pelanggan') }}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-users"></i>
                        <div>Pelanggan</div>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasAnyPermission(['sfa.penjualan']))
                <li class="menu-item {{ request()->is(['sfa/penjualan']) ? 'active' : '' }}">
                    <a href="{{ route('sfa.penjualan') }}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-shopping-bag"></i>
                        <div>Penjualan</div>
                    </a>
                </li>
            @endif

            @include('layouts.sidebar.datamaster')
            @include('layouts.sidebar.produksi')
            @include('layouts.sidebar.gudang_bahan')
            @include('layouts.sidebar.gudang_logistik')
            @include('layouts.sidebar.gudang_jadi')
            @include('layouts.sidebar.gudang_cabang')
            @include('layouts.sidebar.marketing')
            @include('layouts.sidebar.pembelian')
            @include('layouts.sidebar.keuangan')
            @include('layouts.sidebar.accounting')
            @include('layouts.sidebar.maintenance')
            @include('layouts.sidebar.generalaffair')
            @include('layouts.sidebar.hrd')
            @include('layouts.sidebar.worksheetom')


            <li class="menu-item {{ request()->is(['ticket', 'ticket/*']) ? 'active' : '' }}">
                <a href="{{ route('ticket.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-ticket"></i>
                    <div>Ticket</div>
                </a>
            </li>
        </ul>
    </div>

    <!-- User Profile -->
    <div class="user-profile">
        @php
            $user = Auth::user();
            $userPhoto = asset('assets/img/avatars/1.png');
            if (!empty($user->foto) && Storage::disk('public')->exists('users/' . $user->foto)) {
                $userPhoto = Storage::url('users/' . $user->foto);
            }
        @endphp
        <img src="{{ $userPhoto }}" alt="{{ $user->name }}" class="user-avatar">
        <div class="user-info">
            <p class="user-name">{{ $user->name }}</p>
        </div>
        <div class="user-menu-toggle">
            <i class="ti ti-dots-vertical"></i>
        </div>
    </div>
</aside>
<!-- / Menu -->