@php
    $agent = new Jenssegers\Agent\Agent();
@endphp

<style>
    .modern-navbar {
        background: #ffc800 !important;
        box-shadow: none !important;
        border-bottom: none !important;
        padding: 0.75rem 1.5rem;
        padding-right: 1.5rem !important;
        min-height: 70px;
        margin: 0 !important;
        width: 100% !important;
        max-width: 100%;
        box-sizing: border-box;
        overflow: visible !important;
    }

    /* Override navbar-detached untuk membuat fluid */
    .layout-navbar.navbar-detached.modern-navbar,
    .layout-navbar.navbar-detached {
        margin: 0 !important;
        border-radius: 0 !important;
        padding: 0.75rem 1.5rem !important;
    }

    /* Override untuk layout fixed - hitung lebar dengan benar */
    @media (min-width: 1200px) {

        .layout-menu-fixed:not(.layout-menu-collapsed) .layout-navbar.navbar-detached.modern-navbar,
        .layout-menu-fixed:not(.layout-menu-collapsed) .layout-navbar.navbar-detached {
            width: calc(100% - 280px) !important;
            max-width: calc(100% - 280px) !important;
            margin: 0 !important;
            left: 280px !important;
            right: 0 !important;
            box-sizing: border-box;
        }

        .layout-menu-fixed.layout-menu-collapsed:not(.layout-menu-hover) .layout-navbar.navbar-detached.modern-navbar,
        .layout-menu-fixed.layout-menu-collapsed:not(.layout-menu-hover) .layout-navbar.navbar-detached {
            width: calc(100% - 80px) !important;
            max-width: calc(100% - 80px) !important;
            margin: 0 !important;
            left: 80px !important;
            right: 0 !important;
            box-sizing: border-box;
        }

        .layout-menu-fixed.layout-menu-collapsed.layout-menu-hover .layout-navbar.navbar-detached.modern-navbar,
        .layout-menu-fixed.layout-menu-collapsed.layout-menu-hover .layout-navbar.navbar-detached {
            width: calc(100% - 280px) !important;
            max-width: calc(100% - 280px) !important;
            margin: 0 !important;
            left: 280px !important;
            right: 0 !important;
            box-sizing: border-box;
        }
    }

    /* Untuk mobile dan tablet */
    @media (max-width: 1199.98px) {

        .layout-navbar.navbar-detached.modern-navbar,
        .layout-navbar.navbar-detached {
            width: 100% !important;
            margin: 0 !important;
            left: 0 !important;
            right: 0 !important;
        }
    }

    /* Membuat konten menyatu dengan navbar */
    .content-wrapper {
        padding-top: 0 !important;
    }

    .content-wrapper .container-fluid {
        padding-top: 0 !important;
        padding-left: 1.5rem !important;
        padding-right: 1.5rem !important;
    }

    /* Memastikan navbar benar-benar fluid */
    .layout-page {
        flex-direction: column;
    }

    .layout-navbar {
        flex-shrink: 0;
    }

    /* Memastikan navbar-nav-right dan dropdown terlihat */
    .navbar-nav-right {
        position: relative;
        z-index: 1000;
        width: 100%;
        display: flex !important;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: nowrap;
        overflow: visible !important;
        gap: 0.5rem;
        max-width: 100%;
    }

    /* Memastikan navbar tidak overflow */
    .modern-navbar {
        max-width: 100%;
        box-sizing: border-box;
    }

    .modern-navbar .navbar {
        max-width: 100%;
        box-sizing: border-box;
        overflow: visible !important;
    }

    .modern-navbar .navbar-nav {
        position: relative;
        z-index: 1001;
        display: flex !important;
        flex-wrap: nowrap;
        overflow: visible !important;
    }

    .modern-navbar ul.navbar-nav {
        margin-left: auto !important;
        flex-shrink: 0;
        min-width: 0;
    }

    /* Memastikan notifikasi tidak terpotong */
    .modern-navbar .nav-item:last-child {
        margin-right: 0 !important;
        padding-right: 0 !important;
    }

    /* Memastikan user avatar di kanan tidak terpotong */
    .modern-navbar .navbar-nav:last-child {
        margin-right: 0 !important;
    }

    /* Memastikan padding kanan navbar tidak memotong notifikasi */
    @media (max-width: 1199.98px) {
        .modern-navbar {
            padding-right: 1rem !important;
        }
    }

    /* Memastikan dropdown menu tidak terpotong di kanan */
    .modern-navbar .dropdown-menu-end {
        right: 0 !important;
        left: auto !important;
    }

    .modern-navbar .nav-item {
        position: relative;
        z-index: 1002;
        flex-shrink: 0;
    }

    .modern-navbar .dropdown-menu {
        z-index: 1050 !important;
    }

    /* Memastikan dropdown tidak tertutup */
    .modern-dropdown-menu {
        z-index: 1050 !important;
        position: absolute !important;
    }

    /* Memastikan badge terlihat */
    .modern-badge {
        z-index: 1002;
    }

    .modern-navbar .navbar-search-wrapper {
        position: relative;
        flex-shrink: 1;
        min-width: 0;
    }

    /* Memastikan search tidak mengambil terlalu banyak ruang */
    .modern-navbar .navbar-nav:first-child {
        flex: 0 1 auto;
        min-width: 0;
    }

    .modern-search-input {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 10px 16px 10px 44px;
        width: 320px;
        max-width: 320px;
        font-size: 14px;
        transition: all 0.3s;
        flex-shrink: 1;
    }

    .modern-search-input:focus {
        outline: none;
        border-color: #1e3a8a;
        background: white;
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
        width: 400px;
        max-width: 400px;
    }

    .modern-search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        font-size: 18px;
    }

    .modern-nav-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        color: #495057;
        transition: all 0.3s;
        position: relative;
        background: transparent;
        border: none;
        cursor: pointer;
    }

    .modern-nav-icon:hover {
        background: #f8f9fa;
        color: #1e3a8a;
        transform: translateY(-2px);
    }

    .modern-nav-icon i {
        font-size: 20px;
    }

    .modern-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
        color: white;
        font-size: 10px;
        font-weight: 600;
        padding: 2px 6px;
        border-radius: 10px;
        min-width: 20px;
        text-align: center;
        box-shadow: 0 2px 4px rgba(220, 38, 38, 0.3);
        border: 2px solid white;
    }

    .modern-user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid #e9ecef;
        transition: all 0.3s;
        cursor: pointer;
    }

    .modern-user-avatar:hover {
        border-color: #1e3a8a;
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.2);
    }

    .modern-dropdown-menu {
        border: none;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        border-radius: 12px;
        padding: 8px;
        margin-top: 8px;
    }

    .modern-dropdown-item {
        border-radius: 8px;
        padding: 10px 12px;
        transition: all 0.2s;
        margin-bottom: 4px;
    }

    .modern-dropdown-item:hover {
        background: #f8f9fa;
        color: #1e3a8a;
    }

    .modern-dropdown-header {
        padding: 12px;
        border-bottom: 1px solid #e9ecef;
        margin-bottom: 8px;
    }

    .modern-shortcuts-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
        padding: 8px;
    }

    .modern-shortcut-item {
        padding: 16px;
        border-radius: 12px;
        background: #f8f9fa;
        text-align: center;
        transition: all 0.3s;
        cursor: pointer;
        border: 1px solid transparent;
    }

    .modern-shortcut-item:hover {
        background: white;
        border-color: #1e3a8a;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.1);
    }

    .modern-shortcut-icon {
        width: 48px;
        height: 48px;
        margin: 0 auto 8px;
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
    }

    .modern-shortcut-item:hover .modern-shortcut-icon {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
    }

    .modern-shortcut-label {
        font-size: 13px;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 4px;
    }

    .modern-shortcut-desc {
        font-size: 11px;
        color: #6c757d;
    }

    .modern-menu-toggle {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        color: #495057;
        transition: all 0.3s;
        background: transparent;
        border: none;
    }

    .modern-menu-toggle:hover {
        background: #f8f9fa;
        color: #1e3a8a;
    }

    /* Memastikan hamburger menu di navbar tidak terpengaruh styling sidebar */
    .layout-navbar .layout-menu-toggle {
        position: static !important;
        transform: none !important;
        margin: 0 !important;
        padding: 0 !important;
        top: auto !important;
        right: auto !important;
    }

    /* Override untuk semua state collapsed */
    html.layout-menu-collapsed .layout-navbar .layout-menu-toggle,
    html.layout-menu-collapsed:not(.layout-menu-hover) .layout-navbar .layout-menu-toggle {
        position: static !important;
        transform: none !important;
        margin: 0 !important;
        padding: 0 !important;
        top: auto !important;
        right: auto !important;
    }

    .modern-btn-primary {
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
        border: none;
        border-radius: 10px;
        padding: 8px 16px;
        color: white;
        font-weight: 500;
        transition: all 0.3s;
    }

    .modern-btn-primary:hover {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
        color: white;
    }

    @media (max-width: 991px) {
        .modern-search-input {
            width: 200px;
        }

        .modern-search-input:focus {
            width: 250px;
        }

        /* Override untuk mobile */
        .layout-navbar.navbar-detached.modern-navbar,
        .layout-navbar.navbar-detached {
            width: 100% !important;
            margin: 0 !important;
            padding: 0.75rem 1rem !important;
        }

        .content-wrapper .container-fluid {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }

        /* Memastikan notifikasi terlihat di mobile */
        .navbar-nav-right {
            flex-wrap: wrap;
        }

        .modern-navbar ul.navbar-nav {
            margin-left: auto !important;
            flex-wrap: nowrap;
        }

        .modern-search-input {
            max-width: 100%;
        }
    }
</style>

<nav class="layout-navbar navbar navbar-expand-xl navbar-detached align-items-center modern-navbar" id="layout-navbar"
    @if ($agent->isMobile()) style="width:100% !important; margin:0 !important" @endif>

    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4 modern-menu-toggle" href="javascript:void(0)">
            <i class="ti ti-menu-2 ti-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search -->
        <div class="navbar-nav align-items-center me-3">
            <div class="nav-item navbar-search-wrapper mb-0">
                <div class="position-relative">
                    <i class="ti ti-search modern-search-icon"></i>
                    <input type="text" class="form-control modern-search-input" placeholder="Search (Ctrl+/)" aria-label="Search..." />
                </div>
            </div>
        </div>
        <!-- /Search -->

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            @if (Cookie::get('kodepelanggan') != null && $level_user == 'salesman')
                <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                    <a class="modern-btn-primary btn btn-sm" href="/sfa/pelanggan/{{ Cookie::get('kodepelanggan') }}/show">
                        <i class="ti ti-sm ti-user"></i> Pelanggan
                    </a>
                </li>
            @endif

            @if (in_array($level_user, [
                    'super admin',
                    'direktur',
                    'gm marketing',
                    'gm operasional',
                    'gm administrasi',
                    'operation manager',
                    'sales marketing manager',
                    'regional sales manager',
                    'regional operation manager',
                    'manager keuangan',
                ]))
                <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                    <a class="nav-link dropdown-toggle hide-arrow modern-nav-icon" href="javascript:void(0);" data-bs-toggle="dropdown"
                        data-bs-auto-close="outside" aria-expanded="false">
                        <i class="ti ti-layout-grid-add"></i>
                        <span class="modern-badge">{{ $total_notifikasi }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end py-0 modern-dropdown-menu" style="width: 320px;">
                        <div class="modern-dropdown-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="mb-0 fw-semibold">Shortcuts</h5>
                                <a href="javascript:void(0)" class="text-muted" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Add shortcuts">
                                    <i class="ti ti-sm ti-apps"></i>
                                </a>
                            </div>
                        </div>
                        <div class="modern-shortcuts-grid">
                            <div class="modern-shortcut-item">
                                <div class="modern-shortcut-icon">
                                    <i class="ti ti-brand-shopee"></i>
                                    @if (!empty($notifikasi_limitkredit))
                                        <span class="modern-badge" style="position: absolute; top: -4px; right: -4px;">
                                            {{ $notifikasi_limitkredit }}
                                        </span>
                                    @endif
                                </div>
                                <a href="{{ route('ajuanlimit.index', ['posisi_ajuan' => $level_user, 'status' => 0]) }}"
                                    class="stretched-link text-decoration-none">
                                    <div class="modern-shortcut-label">Ajuan</div>
                                    <div class="modern-shortcut-desc">Limit Kredit</div>
                                </a>
                            </div>
                            <div class="modern-shortcut-item">
                                <div class="modern-shortcut-icon">
                                    <i class="ti ti-file-invoice"></i>
                                    @if (!empty($notifikasi_ajuanfaktur))
                                        <span class="modern-badge" style="position: absolute; top: -4px; right: -4px;">
                                            {{ $notifikasi_ajuanfaktur }}
                                        </span>
                                    @endif
                                </div>
                                <a href="{{ route('ajuanfaktur.index', ['posisi_ajuan' => $level_user, 'status' => 0]) }}"
                                    class="stretched-link text-decoration-none">
                                    <div class="modern-shortcut-label">Ajuan</div>
                                    <div class="modern-shortcut-desc">Faktur Kredit</div>
                                </a>
                            </div>
                            <div class="modern-shortcut-item">
                                <div class="modern-shortcut-icon">
                                    <i class="ti ti-users"></i>
                                    @if (!empty($notifikasi_penilaiankaryawan))
                                        <span class="modern-badge" style="position: absolute; top: -4px; right: -4px;">
                                            {{ $notifikasi_penilaiankaryawan }}
                                        </span>
                                    @endif
                                </div>
                                <a href="{{ route('penilaiankaryawan.index', ['posisi_ajuan' => $level_user, 'status' => 'pending']) }}"
                                    class="stretched-link text-decoration-none">
                                    <div class="modern-shortcut-label">Penilaian</div>
                                    <div class="modern-shortcut-desc">Karyawan</div>
                                </a>
                            </div>
                            <div class="modern-shortcut-item">
                                <div class="modern-shortcut-icon">
                                    <i class="ti ti-target-arrow"></i>
                                    @if (!empty($notifikasi_target))
                                        <span class="modern-badge" style="position: absolute; top: -4px; right: -4px;">
                                            {{ $notifikasi_target }}
                                        </span>
                                    @endif
                                </div>
                                <a href="/targetkomisi?posisi_ajuan={{ $level_user }}&status=0" class="stretched-link text-decoration-none">
                                    <div class="modern-shortcut-label">Target</div>
                                    <div class="modern-shortcut-desc">Marketing</div>
                                </a>
                            </div>
                            <div class="modern-shortcut-item">
                                <div class="modern-shortcut-icon">
                                    <i class="ti ti-receipt"></i>
                                    @if (!empty($notifikasi_pengajuan_izin))
                                        <span class="modern-badge" style="position: absolute; top: -4px; right: -4px;">
                                            {{ $notifikasi_pengajuan_izin }}
                                        </span>
                                    @endif
                                </div>
                                <a href="{{ route('izinabsen.index', ['posisi_ajuan' => $level_user, 'status' => 'pending']) }}"
                                    class="stretched-link text-decoration-none">
                                    <div class="modern-shortcut-label">Pengajuan Izin</div>
                                    <div class="modern-shortcut-desc">Karyawan</div>
                                </a>
                            </div>
                            <div class="modern-shortcut-item">
                                <div class="modern-shortcut-icon">
                                    <i class="ti ti-target-arrow"></i>
                                    @if (!empty($notifikasi_lembur))
                                        <span class="modern-badge" style="position: absolute; top: -4px; right: -4px;">
                                            {{ $notifikasi_lembur }}
                                        </span>
                                    @endif
                                </div>
                                <a href="{{ route('lembur.index', ['posisi_ajuan' => $level_user, 'status' => 'pending']) }}"
                                    class="stretched-link text-decoration-none">
                                    <div class="modern-shortcut-label">Lembur</div>
                                    <div class="modern-shortcut-desc">Karyawan</div>
                                </a>
                            </div>
                            <div class="modern-shortcut-item">
                                <div class="modern-shortcut-icon">
                                    <i class="ti ti-wallet"></i>
                                    @if (!empty($notifikasiajuantransferdana))
                                        <span class="modern-badge" style="position: absolute; top: -4px; right: -4px;">
                                            {{ $notifikasiajuantransferdana }}
                                        </span>
                                    @endif
                                </div>
                                <a href="{{ route('ajuantransfer.index') }}" class="stretched-link text-decoration-none">
                                    <div class="modern-shortcut-label">Ajuan Transfer</div>
                                    <div class="modern-shortcut-desc">Dana</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                    <a class="nav-link dropdown-toggle hide-arrow modern-nav-icon" href="javascript:void(0);" data-bs-toggle="dropdown"
                        data-bs-auto-close="outside" aria-expanded="false">
                        <i class="ti ti-archive"></i>
                        <span class="modern-badge">{{ $notifikasi_ajuan_program }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end py-0 modern-dropdown-menu" style="width: 320px;">
                        <div class="modern-dropdown-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="mb-0 fw-semibold">Program</h5>
                                <a href="javascript:void(0)" class="text-muted" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Add shortcuts">
                                    <i class="ti ti-sm ti-archive"></i>
                                </a>
                            </div>
                        </div>
                        <div class="modern-shortcuts-grid">
                            <div class="modern-shortcut-item">
                                <div class="modern-shortcut-icon">
                                    <i class="ti ti-file-invoice"></i>
                                    @if (!empty($notifikasi_ajuanprogramikatan))
                                        <span class="modern-badge" style="position: absolute; top: -4px; right: -4px;">
                                            {{ $notifikasi_ajuanprogramikatan }}
                                        </span>
                                    @endif
                                </div>
                                <a href="{{ route('ajuanprogramikatan.index') }}?status=pending" class="stretched-link text-decoration-none">
                                    <div class="modern-shortcut-label">Ajuan</div>
                                    <div class="modern-shortcut-desc">Program Ikatan</div>
                                </a>
                            </div>
                            <div class="modern-shortcut-item">
                                <div class="modern-shortcut-icon">
                                    <i class="ti ti-file-invoice"></i>
                                    @if (!empty($notifikasi_pencairanprogramikatan))
                                        <span class="modern-badge" style="position: absolute; top: -4px; right: -4px;">
                                            {{ $notifikasi_pencairanprogramikatan }}
                                        </span>
                                    @endif
                                </div>
                                <a href="{{ route('pencairanprogramikatan.index') }}" class="stretched-link text-decoration-none">
                                    <div class="modern-shortcut-label">Pencairan</div>
                                    <div class="modern-shortcut-desc">Program Ikatan</div>
                                </a>
                            </div>
                            <div class="modern-shortcut-item">
                                <div class="modern-shortcut-icon">
                                    <i class="ti ti-file-invoice"></i>
                                    @if (!empty($notifikasi_ajuanprogramkumulatif))
                                        <span class="modern-badge" style="position: absolute; top: -4px; right: -4px;">
                                            {{ $notifikasi_ajuanprogramkumulatif }}
                                        </span>
                                    @endif
                                </div>
                                <a href="{{ route('ajuankumulatif.index') }}?status=pending" class="stretched-link text-decoration-none">
                                    <div class="modern-shortcut-label">Ajuan</div>
                                    <div class="modern-shortcut-desc">Program Kumulatif</div>
                                </a>
                            </div>
                            <div class="modern-shortcut-item">
                                <div class="modern-shortcut-icon">
                                    <i class="ti ti-file-invoice"></i>
                                    @if (!empty($notifikasi_pencairanprogramkumulatif))
                                        <span class="modern-badge" style="position: absolute; top: -4px; right: -4px;">
                                            {{ $notifikasi_pencairanprogramkumulatif }}
                                        </span>
                                    @endif
                                </div>
                                <a href="{{ route('pencairanprogram.index') }}" class="stretched-link text-decoration-none">
                                    <div class="modern-shortcut-label">Pencairan</div>
                                    <div class="modern-shortcut-desc">Program Kumulatif</div>
                                </a>
                            </div>
                            <div class="modern-shortcut-item">
                                <div class="modern-shortcut-icon">
                                    <i class="ti ti-file-invoice"></i>
                                    @if (!empty($notifikasi_ajuanprogramikatanenambulan))
                                        <span class="modern-badge" style="position: absolute; top: -4px; right: -4px;">
                                            {{ $notifikasi_ajuanprogramikatanenambulan }}
                                        </span>
                                    @endif
                                </div>
                                <a href="{{ route('ajuanprogramenambulan.index') }}?status=pending" class="stretched-link text-decoration-none">
                                    <div class="modern-shortcut-label">Ajuan</div>
                                    <div class="modern-shortcut-desc">Program Enambulan</div>
                                </a>
                            </div>
                            <div class="modern-shortcut-item">
                                <div class="modern-shortcut-icon">
                                    <i class="ti ti-file-invoice"></i>
                                    @if (!empty($notifikasi_pencairanprogramikatanenambulan))
                                        <span class="modern-badge" style="position: absolute; top: -4px; right: -4px;">
                                            {{ $notifikasi_pencairanprogramikatanenambulan }}
                                        </span>
                                    @endif
                                </div>
                                <a href="{{ route('pencairanprogramenambulan.index') }}?status=pending"
                                    class="stretched-link text-decoration-none">
                                    <div class="modern-shortcut-label">Pencairan</div>
                                    <div class="modern-shortcut-desc">Program Enambulan</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>

                @if ($level_user == 'gm administrasi' || $level_user == 'super admin')
                    <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                        <a class="nav-link modern-nav-icon" href="{{ route('ticket.index') }}">
                            <i class="ti ti-tool"></i>
                            <span class="modern-badge">{{ $notifikasi_ticket }}</span>
                        </a>
                    </li>
                    <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                        <a class="nav-link modern-nav-icon" href="{{ route('ticketupdate.index') }}?status=pending">
                            <i class="ti ti-recycle"></i>
                            <span class="modern-badge">{{ $notifikasi_update_data }}</span>
                        </a>
                    </li>
                @endif
            @endif

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <img src="{{ asset('/assets/img/avatars/1.png') }}" alt class="modern-user-avatar" />
                </a>
                <ul class="dropdown-menu dropdown-menu-end modern-dropdown-menu">
                    <li>
                        <a class="dropdown-item modern-dropdown-item" href="pages-account-settings-account.html">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <img src="{{ asset('/assets/img/avatars/1.png') }}" alt class="modern-user-avatar"
                                        style="width: 48px; height: 48px;" />
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ Auth::user()->name }}</span>
                                    <small class="text-muted">{{ textCamelCase($level_user) }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item modern-dropdown-item" href="{{ route('users.ubahpassword') }}">
                            <i class="ti ti-key me-2 ti-sm"></i>
                            <span class="align-middle">Ubah Password</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a class="dropdown-item modern-dropdown-item text-danger" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                <i class="ti ti-logout me-2 ti-sm"></i>
                                <span class="align-middle">Log Out</span>
                            </a>
                        </form>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>

    <!-- Search Small Screens -->
    <div class="navbar-search-wrapper search-input-wrapper d-none">
        <input type="text" class="form-control search-input container-fluid border-0" placeholder="Search..." aria-label="Search..." />
        <i class="ti ti-x ti-sm search-toggler cursor-pointer"></i>
    </div>
</nav>
