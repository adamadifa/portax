<aside id="layout-menu" class="group flex flex-col flex-shrink-0 bg-[#052659] border-r border-white/5 h-screen transition-all duration-300 ease-in-out w-[260px] relative overflow-hidden" :class="collapsed ? 'w-[80px]' : 'w-[260px]'">
    <style>
        /* Modern Premium Sidebar Ornaments */
        #layout-menu::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.08) 0%, rgba(59, 130, 246, 0) 70%);
            pointer-events: none;
            z-index: 0;
        }

        #layout-menu::after {
            content: '';
            position: absolute;
            bottom: -50px;
            left: -50px;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.05) 0%, rgba(99, 102, 241, 0) 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* Global Reset for includes */
        #layout-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        /* Aggressively hide all template pseudo-elements to prevent "double" icons */
        #layout-menu .menu-item::before,
        #layout-menu .menu-item::after,
        #layout-menu .menu-link::before,
        #layout-menu .menu-link::after {
            content: none !important;
            display: none !important;
        }

        .sidebar-scroll,
        .menu-inner, 
        .menu-item {
            display: flex;
            flex-direction: column;
            width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin: 0 !important;
            position: relative;
            z-index: 1;
        }

        /* Custom Scrollbar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.08);
            border-radius: 10px;
        }
        .sidebar-scroll:hover::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.15);
        }

        /* Menu Link Styling */
        .menu-link {
            display: flex !important;
            align-items: center !important;
            width: 100% !important;
            padding: 0.55rem 1.5rem !important;
            margin: 0 !important;
            color: rgba(255,255,255,0.5);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            border-left: 3px solid transparent;
            position: relative;
            overflow: visible !important;
        }

        .menu-link > div {
            display: flex;
            align-items: center;
        }

        .menu-link:hover {
            background-color: rgba(255,255,255,0.05);
            color: #ffffff;
            padding-left: 1.75rem !important; /* Elegant slide effect */
        }

        .menu-item.active > .menu-link {
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.02) 100%);
            color: #ffffff;
            font-weight: 600;
            border-left-color: #ffffff;
            box-shadow: inset 4px 0 10px -4px rgba(255,255,255,0.1);
        }

        /* Submenu Tree Lines */
        .menu-sub {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            position: relative;
            display: none;
        }
        
        .menu-item.open > .menu-sub {
            display: flex !important;
            flex-direction: column !important;
        }

        .menu-sub::before {
            content: '';
            position: absolute;
            left: 2.25rem;
            top: 0;
            bottom: 0;
            width: 1px;
            background: linear-gradient(to bottom, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0.1) 50%, rgba(255,255,255,0.05) 100%);
            z-index: 1;
        }

        .menu-sub .menu-link::before {
            content: '' !important;
            display: block !important;
            position: absolute !important;
            left: 2.25rem !important;
            top: 50% !important;
            width: 0.75rem !important;
            height: 1px !important;
            background-color: rgba(255,255,255,0.1) !important;
            z-index: 1 !important;
        }

        .menu-sub .menu-link {
            padding: 0.45rem 2.5rem 0.45rem 3.5rem !important;
            font-size: 0.8125rem;
            color: rgba(255,255,255,0.4);
        }
        
        .menu-sub .menu-link:hover {
            padding-left: 3.75rem !important;
            color: #ffffff;
        }

        /* User Profile Card - Tech Nuance */
        .user-card {
            background: radial-gradient(120% 120% at 0% 0%, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.01) 100%) !important;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            box-shadow: 0 4px 24px -1px rgba(0, 0, 0, 0.2), inset 0 0 20px rgba(255,255,255,0.02);
            position: relative;
            overflow: hidden;
        }

        .user-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
        }

        /* Online status with a tech pulse */
        .online-status-dot {
            box-shadow: 0 0 0 2px rgba(5, 38, 89, 1), 0 0 8px rgba(34, 197, 94, 0.5);
            position: relative;
        }

        .online-status-dot::after {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: 50%;
            border: 1px solid rgba(34, 197, 94, 0.5);
            animation: tech-pulse 2s infinite;
        }

        @keyframes tech-pulse {
            0% { transform: scale(1); opacity: 1; }
            100% { transform: scale(3); opacity: 0; }
        }

        .user-profile-nav {
            padding: 0 1rem;
            margin-bottom: 0.75rem;
        }
        
        .badge {
            font-size: 0.65rem;
            padding: 0.25em 0.6em;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: #fff;
            backdrop-filter: blur(4px);
        }

        /* Collapsed States */
        #layout-menu.collapsed {
            width: 80px !important;
        }
        #layout-menu.collapsed .menu-text, 
        #layout-menu.collapsed .user-details,
        #layout-menu.collapsed .badge,
        #layout-menu.collapsed .menu-toggle::after,
        #layout-menu.collapsed span.menu-text {
            display: none !important;
            opacity: 0;
        }

        /* User Card Collapsed State */
        #layout-menu.collapsed .user-profile-nav {
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
        }
        
        #layout-menu.collapsed .user-card {
            padding: 0.5rem !important;
            justify-content: center;
            border-color: transparent;
            background: transparent;
        }

        #layout-menu.collapsed .user-card img {
            width: 40px;
            height: 40px;
        }

        /* Toggle Button (Minimalist) */
        #sidebarToggle {
            cursor: pointer;
            flex-shrink: 0;
            z-index: 100;
        }
        
        #layout-menu.collapsed #sidebarToggle {
            display: none !important; /* Hide toggle when collapsed, or move it */
        }

        /* The ONLY Allowed Chevron Icon - Increased specificity and reset theme properties */
        #layout-menu .menu-link.menu-toggle::after {
            content: "\f105" !important;
            font-family: "Font Awesome 6 Free" !important;
            font-weight: 900 !important;
            display: block !important;
            position: absolute !important;
            right: 1.25rem !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            font-size: 11px !important;
            color: #ffffff !important;
            opacity: 0.3 !important;
            transition: all 0.3s ease !important;
            z-index: 5 !important;
            
            /* Deep Reset: Remove original theme's CSS-border chevron */
            border: none !important;
            width: auto !important;
            height: auto !important;
            background: none !important;
        }
        
        #layout-menu .menu-item.open > .menu-link.menu-toggle::after {
            transform: translateY(-50%) rotate(90deg) !important;
            opacity: 0.8 !important;
            border: none !important; /* Ensure border doesn't reappear */
        }

        .menu-icon {
            flex-shrink: 0;
            width: 1.5rem;
            height: 1.5rem;
            margin-right: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            opacity: 0.7;
            transition: all 0.3s ease;
        }

        .menu-link:hover .menu-icon,
        .menu-item.active .menu-icon {
            opacity: 1;
            transform: scale(1.1);
        }

        .menu-header {
            padding: 1.75rem 1.75rem 0.625rem;
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            color: rgba(255,255,255,0.2);
            letter-spacing: 0.15em;
        }
    </style>

    <!-- Load Tabler Icons CDN early -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <!-- Sidebar Header (Logo, Name, Toggle) -->
    <div class="px-6 pt-3 pb-2 flex items-center justify-between gap-3 overflow-hidden">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center border border-white/10 overflow-hidden">
                <img src="{{ asset('assets/img/logo/logoportal.png') }}" alt="Logo" class="w-7 h-7 object-contain">
            </div>
            <span class="text-2xl font-bold text-white tracking-widest menu-text">PORTAX</span>
        </div>
        
        <!-- Sidebar Toggle (Minimalist Circle) -->
        <button id="sidebarToggle" class="w-6 h-6 rounded-full border border-white/20 flex items-center justify-center hover:bg-white/10 transition-all duration-300">
            <div class="w-1.5 h-1.5 rounded-full bg-white/60"></div>
        </button>
    </div>

    <!-- Horizontal Divider -->
    <div class="px-6 mb-3">
        <div class="h-px bg-white/10 w-full"></div>
    </div>

    <!-- User Context Card -->
    <div class="px-1.5 mb-2 user-profile-nav">
        @php
            $user = Auth::user();
            $userPhoto = 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=3b82f6&color=fff';
            if (!empty($user->foto) && Storage::disk('public')->exists('users/' . $user->foto)) {
                $userPhoto = Storage::url('users/' . $user->foto);
            }
        @endphp
        <div class="user-card bg-white/[0.03] border border-white/5 rounded-2xl p-3 flex items-center gap-4 transition-all duration-300 hover:bg-white/[0.06]">
            <!-- Avatar with Online Dot -->
            <div class="relative flex-shrink-0">
                <img src="{{ $userPhoto }}" alt="{{ $user->name }}" class="w-11 h-11 rounded-xl object-cover border border-white/10 shadow-lg">
                <div class="absolute top-1/2 -translate-y-1/2 -right-1 w-3 h-3 bg-green-500 border-2 border-[#052659] rounded-full online-status-dot"></div>
            </div>
            
            <div class="user-details overflow-hidden">
                <p class="text-[13px] font-bold text-white truncate leading-tight mb-1">{{ $user->name }}</p>
                <div class="flex items-center gap-1.5 text-white/40">
                    <i class="fas fa-shield-halved text-[9px]"></i>
                    <p class="text-[10px] font-medium uppercase tracking-wider truncate">
                        {{ $user->getRoleNames()->first() ?? 'User' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <div class="flex-1 overflow-y-auto sidebar-scroll py-1">
        <ul class="menu-inner">
            <!-- Dashboard -->
            <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="menu-link">
                    <i class="menu-icon fas fa-chart-pie"></i>
                    <div>Dashboard</div>
                </a>
            </li>

            <!-- Included Groups -->
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

            @if (auth()->user()->hasRole('super admin'))
                <li class="menu-header">System</li>
                <li class="menu-item {{ request()->is(['resetdata', 'resetdata/*']) ? 'active' : '' }}">
                    <a href="{{ route('resetdata.index') }}" class="menu-link text-red-400">
                        <i class="menu-icon fas fa-shield-virus"></i>
                        <span class="menu-text">Reset Data</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>

    <!-- Footer Links -->
    <div class="p-4 border-t border-white/5 bg-black/20">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="menu-link !m-0 !w-full hover:!bg-red-500/10 hover:!text-red-400 group/logout">
                <i class="menu-icon fas fa-power-off group-hover/logout:text-red-400"></i>
                <span class="menu-text">Logout</span>
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('layout-menu');
            const toggleBtn = document.getElementById('sidebarToggle');
            const mainContent = document.querySelector('.flex-1.flex.flex-col'); // Select the main content wrapper

            // Persistence logic
            const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
            }

            // Toggle Sidebar
            toggleBtn.addEventListener('click', () => {
                const collapsed = sidebar.classList.toggle('collapsed');
                localStorage.setItem('sidebar-collapsed', collapsed);
            });

            // Handle Submenu Toggles (Classic way)
            const menuToggles = document.querySelectorAll('.menu-toggle');
            menuToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    if (sidebar.classList.contains('collapsed')) return; // Don't toggle sub-menu if collapsed
                    
                    e.preventDefault();
                    const menuItem = this.parentElement;
                    
                    // Close others in same level (Accordion style)
                    const parentUl = menuItem.parentElement;
                    parentUl.querySelectorAll(':scope > .menu-item.open').forEach(item => {
                        if (item !== menuItem) item.classList.remove('open');
                    });
                    
                    menuItem.classList.toggle('open');
                });
            });

            // Auto-expand active groups on load and highlight parent
            document.querySelectorAll('.menu-item.active').forEach(item => {
                let parent = item.parentElement;
                while (parent && !parent.classList.contains('menu-inner')) {
                    if (parent.classList.contains('menu-sub')) {
                        parent.parentElement.classList.add('open', 'active');
                    }
                    parent = parent.parentElement;
                }
            });
        });
    </script>
    
    <!-- Load Tabler Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
</aside>

