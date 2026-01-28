<aside id="layout-menu" class="w-64 bg-[#003d9e] border-r border-[#003d9e] hidden md:flex flex-col flex-shrink-0 transition-all duration-300">
    <style>
        /* General Reset */
        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu-inner {
            display: flex;
            flex-direction: column;
            gap: 0.25rem; /* space-y-1 */
        }

        /* Specific Reset for Top-Level Items to remove template artifacts */
        .menu-inner > .menu-item::before,
        .menu-inner > .menu-item::after,
        .menu-inner > .menu-item > .menu-link::before,
        .menu-inner > .menu-item > .menu-link::after {
            content: none !important;
            display: none !important;
            border: none !important;
            width: 0 !important;
            height: 0 !important;
            opacity: 0 !important;
            background: none !important;
            box-shadow: none !important;
            list-style: none !important;
        }

        .menu-item {
            position: relative;
        }

        /* Menu Link (Parent & Child) */
        .menu-link {
            display: flex;
            align-items: center;
            padding: 0.625rem 0.75rem; /* py-2.5 px-3 */
            color: rgba(255,255,255,0.7); /* White with opacity */
            border-radius: 0.5rem; /* rounded-lg */
            transition: all 0.2s ease-in-out;
            font-size: 0.875rem; /* text-sm */
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            position: relative;
        }

        .menu-link:hover {
            color: #ffffff;
            background-color: rgba(255,255,255,0.1);
        }

        /* Active State (Main Item) */
        .menu-item.active > .menu-link {
            background-color: rgba(255,255,255,0.15);
            color: #ffffff;
            font-weight: 600;
        }

        /* Open/Expanded State (Main Item) */
        .menu-item.open > .menu-link {
            color: #ffffff;
            font-weight: 700;
        }

        /* Icons */
        .menu-icon {
            flex-shrink: 0;
            width: 1.25rem;
            height: 1.25rem;
            margin-right: 0.75rem;
            text-align: center;
            font-size: 1.125rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Submenu Container */
        .menu-sub {
            display: none;
            padding-left: 1.7rem; /* Indent for the lines */
            margin-top: 0.25rem;
            position: relative;
        }

        .menu-item.open > .menu-sub {
            display: block;
        }

        /* Tree View Lines Guidelines */
        /* Vertical line for the whole submenu group */
        .menu-sub::before {
            content: "";
            position: absolute;
            top: 0;
            left: 1.35rem; /* Align with parent icon center */
            bottom: 0;
            width: 1px;
            background-color: rgba(255,255,255,0.2);
        }

        /* Submenu Items */
        .menu-sub .menu-item {
            margin-top: 0.25rem;
        }

        .menu-sub .menu-link {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            color: rgba(255,255,255,0.6);
        }

        .menu-sub .menu-link:hover {
            color: #ffffff;
            background-color: transparent; 
        }
        
        /* Active Submenu Item */
        .menu-sub .menu-item.active > .menu-link {
            color: #ffffff;
            font-weight: 600;
            background-color: transparent; 
        }

        /* Horizontal Connector Line for each Item */
        .menu-sub .menu-link::before {
            content: "";
            position: absolute;
            left: -0.35rem; /* Extends back to the vertical line */
            top: 50%;
            width: 0.5rem; /* Length of the horizontal dash */
            height: 1px;
            background-color: rgba(255,255,255,0.2);
        }

        /* Toggle Arrow */
        .menu-toggle {
            /* justify-content: space-between; Removed to keep text left */
        }
        
        .menu-toggle::after {
            content: '\f107'; /* fa-angle-down */
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            font-size: 0.75rem;
            transition: transform 0.2s;
            color: rgba(255,255,255,0.5);
            margin-left: auto; /* Push arrow to the right */
        }
        
        .menu-item.open > .menu-toggle::after {
            transform: rotate(180deg);
            color: #ffffff;
        }

        /* Menu Header */
        .menu-header {
            padding: 1.5rem 0.75rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            color: rgba(255,255,255,0.4);
            letter-spacing: 0.05em;
        }
    </style>

    <!-- Brand -->
    <div class="h-16 flex items-center px-6 border-b border-white/10 flex-shrink-0">
        <div class="flex items-center gap-2 text-white font-bold text-xl">
             <span class="p-1 rounded bg-white text-[#003d9e] text-xs"><i class="fas fa-plus"></i></span>
            <span class="text-white">Portax</span>
        </div>
    </div>

    <!-- Navigation -->
    <div class="flex-1 overflow-y-auto px-4 py-4 custom-scrollbar">
        <ul class="menu-inner">
            <!-- Dashboard (Static/First Item) -->
            <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="menu-link">
                    <i class="menu-icon fas fa-th-large"></i>
                    <div>Dashboard</div>
                </a>
            </li>

            <!-- Included Dynamic Menus -->
            <!-- Note: Some generic icons might need remapping via JS or CSS if the 'ti-icons' aren't loaded. 
                 Since app.blade.php now uses FontAwesome, we might need a shim or simple replace if the includes use 'ti'.
            -->
            
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
                    <a href="{{ route('resetdata.index') }}" class="menu-link">
                        <i class="menu-icon fas fa-exclamation-triangle text-red-500"></i>
                        <div class="text-red-500">Reset Data</div>
                    </a>
                </li>
            @endif
        </ul>
    </div>

    <!-- User Profile (Bottom) -->
    <div class="p-4 border-t border-white/10 bg-black/10">
        <div class="relative group">
            <button class="w-full flex items-center gap-3 p-2 rounded-lg hover:bg-white/10 transition-all" id="userMenuBtn">
                @php
                    $user = Auth::user();
                    $userPhoto = 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=fff&color=003d9e';
                    if (!empty($user->foto) && Storage::disk('public')->exists('users/' . $user->foto)) {
                        $userPhoto = Storage::url('users/' . $user->foto);
                    }
                @endphp
                <img src="{{ $userPhoto }}" alt="{{ $user->name }}" class="w-9 h-9 rounded-full object-cover border border-white/20">
                <div class="flex-1 text-left overflow-hidden">
                    <p class="text-sm font-semibold text-white truncate">{{ $user->name }}</p>
                    <p class="text-xs text-blue-100 truncate">{{ $user->getRoleNames()->first() ?? 'User' }}</p>
                </div>
                <i class="fas fa-chevron-up text-xs text-blue-200 group-hover:text-white transition-colors"></i>
            </button>

            <!-- Dropdown -->
            <div class="absolute bottom-full left-0 right-0 mb-2 bg-white rounded-xl shadow-lg border border-slate-100 py-1 hidden opacity-0 transform translate-y-2 transition-all duration-200" id="userMenuDropdown">
                <div class="px-4 py-2 border-b border-slate-50">
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Account</p>
                </div>
                <!-- <a href="#" class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-blue-50 hover:text-[#003d9e] transition-colors">
                    <i class="far fa-user w-5 mr-1"></i> Profile
                </a> -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors flex items-center">
                        <i class="fas fa-sign-out-alt w-5 mr-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle Submenu Toggles
            const menuToggles = document.querySelectorAll('.menu-toggle');
            menuToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const menuItem = this.parentElement;
                    menuItem.classList.toggle('open');
                });
            });

            // Handle User Menu
            const userBtn = document.getElementById('userMenuBtn');
            const userDropdown = document.getElementById('userMenuDropdown');
            
            if(userBtn && userDropdown) {
                userBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const isHidden = userDropdown.classList.contains('hidden');
                    if(isHidden) {
                        userDropdown.classList.remove('hidden');
                        // Small delay to allow transition
                        setTimeout(() => {
                            userDropdown.classList.remove('opacity-0', 'translate-y-2');
                        }, 10);
                    } else {
                        closeUserMenu();
                    }
                });

                document.addEventListener('click', (e) => {
                    if (!userBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                        closeUserMenu();
                    }
                });

                function closeUserMenu() {
                    userDropdown.classList.add('opacity-0', 'translate-y-2');
                    setTimeout(() => {
                        userDropdown.classList.add('hidden');
                    }, 200);
                }
            }

            // Compatibility shim for Tabler Icons (ti-*) to FontAwesome if needed
            // If the included files use <i class="ti ti-something"></i>
            // We can try to map them or just let them fail gracefully (or hopefully FA is loaded alongside or we load Tabler via CDN)
        });
    </script>
    

    <!-- Load Tabler Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
</aside>
