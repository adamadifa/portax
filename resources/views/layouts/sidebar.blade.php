<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme custom-sidebar">
    <style>
        .custom-sidebar {
            background: linear-gradient(180deg, #03204f 0%, #021a3d 100%) !important;
            width: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: white;
            box-shadow: 2px 0 20px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .custom-sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.5), transparent);
        }

        .sidebar-header {
            padding: 16px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            min-height: 64px;
            position: relative;
            background: rgba(0, 0, 0, 0.1);
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
            min-width: 0;
        }

        .logo-shapes {
            display: flex;
            gap: 3px;
            flex-shrink: 0;
        }

        .logo-shape {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .logo-shape-1 {
            background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);
            color: white;
        }

        .logo-shape-2 {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        .logo-shape-3 {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
        }

        .sidebar-title {
            font-size: 16px;
            font-weight: 700;
            color: white;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.2;
            letter-spacing: -0.3px;
        }

        .layout-menu-toggle {
            color: rgba(255, 255, 255, 0.8) !important;
            padding: 6px;
            border-radius: 6px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 32px;
            height: 32px;
        }

        .layout-menu-toggle:hover {
            background: rgba(255, 255, 255, 0.12);
            color: white !important;
            transform: scale(1.05);
        }

        .sidebar-nav {
            flex: 1;
            padding: 8px 6px;
            overflow-y: auto;
            overflow-x: hidden;
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
            padding: 14px 12px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(0, 0, 0, 0.1);
            position: relative;
            cursor: pointer;
        }

        .user-profile:hover {
            background: rgba(0, 0, 0, 0.15);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.15);
            flex-shrink: 0;
        }

        .user-info {
            flex: 1;
            min-width: 0;
        }

        .user-name {
            font-weight: 600;
            font-size: 13px;
            color: white;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-menu-toggle {
            color: rgba(255, 255, 255, 0.6);
            font-size: 16px;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .user-menu-toggle:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }

        /* User dropdown menu */
        .user-dropdown {
            position: absolute;
            bottom: 100%;
            left: 0;
            right: 0;
            margin-bottom: 8px;
            background: rgba(3, 32, 79, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            padding: 8px 0;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.2s ease;
            z-index: 1000;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .user-profile.show .user-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            font-size: 13px;
            transition: all 0.2s ease;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .user-dropdown-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .user-dropdown-item.logout {
            color: #ff6b6b;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 4px;
            padding-top: 12px;
        }

        .user-dropdown-item.logout:hover {
            background: rgba(255, 107, 107, 0.1);
            color: #ff5252;
        }

        .user-dropdown-item i {
            font-size: 16px;
            width: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Scrollbar styling */
        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        /* Hide default menu styles */
        .custom-sidebar .menu-inner-shadow {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 50px;
            pointer-events: none;
            background: linear-gradient(to bottom, rgba(3, 32, 79, 0.8), transparent);
            z-index: 1;
        }

        .custom-sidebar .app-brand {
            display: none;
        }

        /* Menu header styling */
        .custom-sidebar .menu-header {
            padding: 10px 6px 6px 6px;
            font-size: 10px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 4px;
        }

        /* Style for included menu items - keep original structure for dropdown */
        .custom-sidebar .menu-item {
            margin-bottom: 4px;
        }

        .custom-sidebar .menu-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 6px;
            color: rgba(255, 255, 255, 0.85) !important;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
            position: relative;
            font-size: 14px;
        }

        .custom-sidebar .menu-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 0;
            background: linear-gradient(180deg, #3b82f6, #8b5cf6);
            border-radius: 0 3px 3px 0;
            transition: height 0.2s ease;
        }

        .custom-sidebar .menu-link:hover {
            background: rgba(255, 255, 255, 0.08) !important;
            color: white !important;
            padding-left: 8px;
            transform: translateX(2px);
        }

        .custom-sidebar .menu-link:hover::before {
            height: 60%;
        }

        .custom-sidebar .menu-item.active .menu-link,
        .custom-sidebar .menu-item.open>.menu-link {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.15), rgba(59, 130, 246, 0.05)) !important;
            color: white !important;
            font-weight: 600;
        }

        .custom-sidebar .menu-item.active .menu-link::before,
        .custom-sidebar .menu-item.open>.menu-link::before {
            height: 70%;
        }

        .custom-sidebar .menu-icon {
            font-size: 18px;
            width: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: inherit;
            flex-shrink: 0;
        }

        .custom-sidebar .menu-link div {
            font-size: 14px;
            font-weight: 500;
            color: inherit;
            line-height: 1.4;
        }

        /* Submenu styling - remove all bullets */
        .custom-sidebar .menu-sub {
            list-style: none !important;
            list-style-type: none !important;
            padding: 6px 0 !important;
            margin: 6px 0 0 0 !important;
            padding-left: 24px !important;
            background: rgba(0, 0, 0, 0.15);
            border-radius: 6px;
            overflow: hidden;
            border-left: none !important;
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
            position: relative;
        }

        .custom-sidebar .menu-sub .menu-item::before,
        .custom-sidebar .menu-sub .menu-item::after {
            display: none !important;
            content: none !important;
        }

        .custom-sidebar .menu-sub .menu-link {
            padding: 8px 8px !important;
            font-size: 13px;
            margin-left: 0 !important;
            padding-left: 8px !important;
            position: relative;
        }

        .custom-sidebar .menu-sub .menu-link::before {
            display: none !important;
            content: none !important;
            width: 0 !important;
            height: 0 !important;
        }

        .custom-sidebar .menu-sub .menu-link::after {
            display: none !important;
            content: none !important;
        }

        .custom-sidebar .menu-sub .menu-link div {
            font-size: 13px;
            padding-left: 0 !important;
            margin-left: 0 !important;
            font-weight: 400;
        }

        .custom-sidebar .menu-sub .menu-icon {
            font-size: 16px;
            width: 18px;
        }

        /* Remove any default list styling */
        .custom-sidebar .menu-sub li {
            list-style: none !important;
            list-style-type: none !important;
            padding-left: 0 !important;
            position: relative;
        }

        .custom-sidebar .menu-sub li::marker,
        .custom-sidebar .menu-sub li::before,
        .custom-sidebar .menu-sub li::after {
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

        /* Remove all default styling from submenu elements */
        .custom-sidebar .menu-sub *::marker,
        .custom-sidebar .menu-sub *::before,
        .custom-sidebar .menu-sub *::after {
            display: none !important;
            content: none !important;
            width: 0 !important;
            height: 0 !important;
        }

        /* Remove any circle or dot indicators from submenu */
        .custom-sidebar .menu-sub .menu-link i::before,
        .custom-sidebar .menu-sub .menu-link i::after {
            display: none !important;
            content: none !important;
        }

        /* Ensure submenu links have no background images or pseudo-elements */
        .custom-sidebar .menu-sub .menu-link {
            background-image: none !important;
            background-position: initial !important;
            background-repeat: no-repeat !important;
        }

        /* Remove any default list markers or bullets completely */
        .custom-sidebar .menu-sub .menu-item:before,
        .custom-sidebar .menu-sub .menu-item:after,
        .custom-sidebar .menu-sub .menu-item:marker {
            display: none !important;
            content: '' !important;
            visibility: hidden !important;
        }

        /* Make sure submenu text is clean */
        .custom-sidebar .menu-sub .menu-link div {
            position: relative;
            z-index: 1;
            padding-left: 0 !important;
            margin-left: 0 !important;
        }

        /* Override any framework default styling that adds circles or lines */
        .custom-sidebar .menu-sub .menu-link {
            position: relative;
        }

        .custom-sidebar .menu-sub .menu-link:before,
        .custom-sidebar .menu-sub .menu-link:after,
        .custom-sidebar .menu-sub .menu-link::before,
        .custom-sidebar .menu-sub .menu-link::after {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            content: none !important;
            width: 0 !important;
            height: 0 !important;
            background: none !important;
            border: none !important;
        }

        /* Ensure no icon wrapper creates visual elements */
        .custom-sidebar .menu-sub .menu-link .menu-icon,
        .custom-sidebar .menu-sub .menu-link i {
            display: none !important;
        }

        /* Ensure menu-toggle works - only for main menu items, not submenu */
        .custom-sidebar .menu-link.menu-toggle::after {
            content: '';
            margin-left: auto;
            width: 0;
            height: 0;
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-top: 4px solid rgba(255, 255, 255, 0.7);
            transition: transform 0.3s;
            flex-shrink: 0;
        }

        .custom-sidebar .menu-item.open>.menu-link.menu-toggle::after {
            transform: rotate(180deg);
        }

        /* Hide toggle arrow in submenu */
        .custom-sidebar .menu-sub .menu-link.menu-toggle::after {
            display: none !important;
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

        /* Collapsed state styling */
        html.layout-menu-collapsed:not(.layout-menu-hover) .custom-sidebar {
            width: 72px !important;
            transition: width 0.3s ease;
        }

        html.layout-menu-collapsed:not(.layout-menu-hover) .sidebar-header {
            padding: 16px 10px;
            justify-content: center;
            min-height: 64px;
        }

        html.layout-menu-collapsed:not(.layout-menu-hover) .sidebar-logo {
            justify-content: center;
            width: 100%;
        }

        html.layout-menu-collapsed:not(.layout-menu-hover) .sidebar-title {
            display: none;
        }

        html.layout-menu-collapsed:not(.layout-menu-hover) .logo-shape {
            width: 32px;
            height: 32px;
            font-size: 16px;
        }

        /* Sembunyikan logo-shape tambahan saat collapsed */
        html.layout-menu-collapsed:not(.layout-menu-hover) .logo-shape-2,
        html.layout-menu-collapsed:not(.layout-menu-hover) .logo-shape-3 {
            display: none !important;
        }

        /* Pastikan tidak ada border atau outline pada logo saat collapsed */
        html.layout-menu-collapsed:not(.layout-menu-hover) .logo-shape {
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
        }

        /* Pastikan logo-shapes container tidak menampilkan circle tambahan */
        html.layout-menu-collapsed:not(.layout-menu-hover) .logo-shapes {
            border: none !important;
            outline: none !important;
            background: transparent !important;
            box-shadow: none !important;
        }

        /* Hanya tampilkan logo-shape pertama saat collapsed */
        html.layout-menu-collapsed:not(.layout-menu-hover) .logo-shapes>*:not(.logo-shape-1) {
            display: none !important;
        }

        /* Pastikan tidak ada pseudo-element yang membuat circle tambahan */
        html.layout-menu-collapsed:not(.layout-menu-hover) .logo-shape::before,
        html.layout-menu-collapsed:not(.layout-menu-hover) .logo-shape::after,
        html.layout-menu-collapsed:not(.layout-menu-hover) .logo-shapes::before,
        html.layout-menu-collapsed:not(.layout-menu-hover) .logo-shapes::after {
            display: none !important;
            content: none !important;
        }

        /* Hanya berlaku untuk toggle di sidebar, bukan di navbar */
        html.layout-menu-collapsed:not(.layout-menu-hover) #layout-menu .layout-menu-toggle,
        html.layout-menu-collapsed:not(.layout-menu-hover) .custom-sidebar .layout-menu-toggle {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            margin: 0;
            padding: 4px;
        }

        html.layout-menu-collapsed:not(.layout-menu-hover) .custom-sidebar .menu-link div {
            opacity: 0;
            width: 0;
            overflow: hidden;
            transition: opacity 0.2s, width 0.2s;
        }

        html.layout-menu-collapsed:not(.layout-menu-hover) .custom-sidebar .menu-link {
            justify-content: center;
            padding: 10px;
        }

        html.layout-menu-collapsed:not(.layout-menu-hover) .custom-sidebar .menu-link::before {
            display: none;
        }

        html.layout-menu-collapsed:not(.layout-menu-hover) .custom-sidebar .menu-icon {
            margin: 0;
        }

        html.layout-menu-collapsed:not(.layout-menu-hover) .custom-sidebar .menu-item>.menu-sub {
            display: none !important;
        }

        html.layout-menu-collapsed:not(.layout-menu-hover) .custom-sidebar .menu-link.menu-toggle::after {
            display: none;
        }

        html.layout-menu-collapsed:not(.layout-menu-hover) .user-profile {
            padding: 14px 10px;
            justify-content: center;
            flex-direction: column;
            gap: 6px;
        }

        html.layout-menu-collapsed:not(.layout-menu-hover) .user-info {
            display: none;
        }

        html.layout-menu-collapsed:not(.layout-menu-hover) .user-menu-toggle {
            display: none;
        }

        html.layout-menu-collapsed:not(.layout-menu-hover) .user-avatar {
            width: 32px;
            height: 32px;
        }

        html.layout-menu-collapsed:not(.layout-menu-hover) .custom-sidebar .menu-header {
            display: none;
        }


        html.layout-menu-collapsed:not(.layout-menu-hover) .sidebar-nav {
            padding: 0 8px;
        }

        /* Hover state when collapsed - show tooltips or expand slightly */
        html.layout-menu-collapsed.layout-menu-hover .custom-sidebar {
            width: 260px !important;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        html.layout-menu-collapsed.layout-menu-hover .custom-sidebar .menu-link div {
            opacity: 1;
            width: auto;
        }

        html.layout-menu-collapsed.layout-menu-hover .sidebar-title {
            display: block;
        }

        html.layout-menu-collapsed.layout-menu-hover .user-info {
            display: block;
        }

        html.layout-menu-collapsed.layout-menu-hover .user-menu-toggle {
            display: block;
        }

        html.layout-menu-collapsed.layout-menu-hover .custom-sidebar .menu-link {
            justify-content: flex-start;
            padding: 12px 8px;
        }

        html.layout-menu-collapsed.layout-menu-hover .custom-sidebar .menu-header {
            display: block;
        }

        html.layout-menu-collapsed.layout-menu-hover .sidebar-header {
            justify-content: space-between;
        }

        html.layout-menu-collapsed.layout-menu-hover .sidebar-logo {
            justify-content: flex-start;
            width: auto;
        }

        html.layout-menu-collapsed.layout-menu-hover .logo-shape {
            width: 32px;
            height: 32px;
            font-size: 16px;
        }

        /* Hanya berlaku untuk toggle di sidebar, bukan di navbar */
        html.layout-menu-collapsed.layout-menu-hover #layout-menu .layout-menu-toggle,
        html.layout-menu-collapsed.layout-menu-hover .custom-sidebar .layout-menu-toggle {
            position: static;
            transform: none;
        }

        /* Smooth transitions */
        .custom-sidebar,
        .sidebar-header,
        .sidebar-logo,
        .sidebar-title,
        .logo-shape,
        .layout-menu-toggle,
        .custom-sidebar .menu-link,
        .custom-sidebar .menu-link div,
        .user-profile,
        .user-info {
            transition: all 0.3s ease;
        }

        /* Mobile responsive */
        @media (max-width: 991px) {
            .custom-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                width: 260px;
                box-shadow: 2px 0 20px rgba(0, 0, 0, 0.3);
            }

            .custom-sidebar.menu-open {
                transform: translateX(0);
            }

            /* Override collapsed styles on mobile */
            html.layout-menu-collapsed .custom-sidebar {
                width: 260px !important;
            }

            .sidebar-header {
                padding: 14px 12px;
            }

            .sidebar-nav {
                padding: 6px 8px;
            }
        }

        @media (max-width: 576px) {
            .custom-sidebar {
                width: 240px;
            }

            .sidebar-title {
                font-size: 15px;
            }

            .custom-sidebar .menu-link {
                padding: 9px 8px;
                font-size: 13px;
            }

            .custom-sidebar .menu-link div {
                font-size: 13px;
            }
        }
    </style>

    <!-- Header -->
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <div class="logo-shapes">
                <div class="logo-shape logo-shape-1">C</div>
            </div>
            <h1 class="sidebar-title">Portax</h1>
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
            {{-- @if (in_array($level_user, ['super admin', 'direktur', 'gm administrasi', 'manager keuangan', 'regional operation manager', 'spv accounting']))
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
            @endif --}}

            {{-- @if (auth()->user()->hasAnyPermission(['sfa.trackingsalesman']))
                <li class="menu-item {{ request()->is(['sfa/trackingsalesman']) ? 'active' : '' }}">
                    <a href="{{ route('sfa.trackingsalesman') }}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-location"></i>
                        <div>Tracking Salesman</div>
                    </a>
                </li>
            @endif --}}

            {{-- @if (auth()->user()->hasAnyPermission(['sfa.pelanggan']))
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
            @endif --}}

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
            {{-- @include('layouts.sidebar.hrd') --}}
            {{-- @include('layouts.sidebar.worksheetom') --}}

            @if (auth()->user()->hasRole('super admin'))
                <li class="menu-header">System</li>
                <li class="menu-item {{ request()->is(['resetdata', 'resetdata/*']) ? 'active' : '' }}">
                    <a href="{{ route('resetdata.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-alert-triangle" style="color: #dc3545;"></i>
                        <div style="color: #dc3545;">Reset Data</div>
                    </a>
                </li>
            @endif

            {{-- <li class="menu-item {{ request()->is(['ticket', 'ticket/*']) ? 'active' : '' }}">
                <a href="{{ route('ticket.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-ticket"></i>
                    <div>Ticket</div>
                </a>
            </li> --}}
        </ul>
    </div>

    <!-- User Profile -->
    <div class="user-profile" id="userProfileDropdown">
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
            <i class="ti ti-chevron-up"></i>
        </div>

        <!-- Dropdown Menu -->
        <div class="user-dropdown">
            <a href="#" class="user-dropdown-item">
                <i class="ti ti-user"></i>
                <span>Profile</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="user-dropdown-item logout" onclick="event.preventDefault(); this.closest('form').submit();">
                    <i class="ti ti-logout"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    <script>
        // Toggle user dropdown
        document.addEventListener('DOMContentLoaded', function() {
            const userProfile = document.getElementById('userProfileDropdown');
            if (userProfile) {
                userProfile.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userProfile.classList.toggle('show');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!userProfile.contains(e.target)) {
                        userProfile.classList.remove('show');
                    }
                });
            }
        });
    </script>
</aside>
<!-- / Menu -->
