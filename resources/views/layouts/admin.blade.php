<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kasir Toko - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Kasir Admin">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>

        :root {
            --primary:      #4e73df;
            --primary-dark: #2e59d9;
            --accent:       #1cc88a;
            --sidebar-w:    260px;
            --topbar-h:     64px;
            --bottombar-h:  68px;
            --radius:       16px;
            --shadow:       0 4px 20px rgba(0,0,0,.08);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f0f4f8;
            color: #1e293b;
            transition: background .3s, color .3s;
            padding-bottom: var(--bottombar-h);
        }

        /* ==============================================
           DARK MODE
        ============================================== */
        body.dark-mode {
            background: #0f172a;
            color: #e2e8f0;
        }
        body.dark-mode .sidebar         { background: linear-gradient(180deg, #1e293b, #0f172a); }
        body.dark-mode .topbar          { background: #1e293b; box-shadow: 0 2px 6px rgba(0,0,0,.3); }
        body.dark-mode .card            { background: #1e293b !important; color: #e2e8f0; border: none; }
        body.dark-mode .dropdown-menu   { background: #1e293b; border-color: #334155; }
        body.dark-mode .dropdown-item   { color: #e2e8f0; }
        body.dark-mode .dropdown-item:hover { background: #334155; }
        body.dark-mode .bottom-nav      { background: #1e293b; border-top: 1px solid #334155; }
        body.dark-mode .bottom-nav-item { color: #94a3b8; }
        body.dark-mode .bottom-nav-item.active { color: var(--primary); }
        body.dark-mode .mobile-overlay  { background: rgba(0,0,0,.7); }
        body.dark-mode .topbar-toggle   { background: #334155; color: #94a3b8; }
        body.dark-mode .topbar-toggle:hover { background: #475569; color: #e2e8f0; }
        body.dark-mode .topbar-btn      { background: #334155; color: #94a3b8; }
        body.dark-mode .topbar-btn:hover { background: #475569; color: #e2e8f0; }
        body.dark-mode .user-chip       { background: #334155; }
        body.dark-mode .user-chip:hover { background: #475569; }
        body.dark-mode .user-chip-name  { color: #e2e8f0; }
        body.dark-mode .topbar-title    { color: #e2e8f0; }
        body.dark-mode .time-badge      { background: #334155; color: #94a3b8; }

        /* ==============================================
           SIDEBAR
        ============================================== */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: linear-gradient(160deg, #4e73df 0%, #1cc88a 100%);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            display: flex;
            flex-direction: column;
            transition: transform .3s cubic-bezier(.4,0,.2,1), box-shadow .3s;
            box-shadow: 4px 0 24px rgba(78,115,223,.2);
        }

        .sidebar-header {
            padding: 24px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,.15);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .sidebar-brand-icon {
            width: 40px; height: 40px;
            background: rgba(255,255,255,.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: white;
            flex-shrink: 0;
        }

        .sidebar-brand-text {
            color: white;
            font-weight: 800;
            font-size: 16px;
            line-height: 1.2;
        }

        .sidebar-brand-sub {
            font-size: 11px;
            font-weight: 500;
            opacity: .7;
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        .sidebar-menu {
            padding: 16px 12px;
            flex: 1;
            overflow-y: auto;
            list-style: none;
            margin: 0;
        }

        .sidebar-menu::-webkit-scrollbar { width: 4px; }
        .sidebar-menu::-webkit-scrollbar-thumb { background: rgba(255,255,255,.2); border-radius: 4px; }

        .sidebar-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: rgba(255,255,255,.45);
            padding: 12px 12px 6px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 12px;
            color: rgba(255,255,255,.8);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all .2s ease;
            margin-bottom: 2px;
        }

        .sidebar-link:hover {
            background: rgba(255,255,255,.15);
            color: white;
            transform: translateX(4px);
        }

        .sidebar-link.active {
            background: rgba(255,255,255,.25);
            color: white;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(0,0,0,.15);
        }

        .sidebar-link .si-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            background: rgba(255,255,255,.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
            transition: background .2s;
        }

        .sidebar-link.active .si-icon,
        .sidebar-link:hover .si-icon {
            background: rgba(255,255,255,.25);
        }

        .sidebar-divider {
            border: none;
            border-top: 1px solid rgba(255,255,255,.15);
            margin: 8px 12px;
        }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,.15);
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 12px;
            background: rgba(255,255,255,.1);
        }

        .sidebar-user-avatar {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: rgba(255,255,255,.25);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: white;
            flex-shrink: 0;
        }

        .sidebar-user-name {
            font-size: 13px;
            font-weight: 600;
            color: white;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user-role {
            font-size: 11px;
            color: rgba(255,255,255,.6);
        }

        /* ==============================================
           MOBILE OVERLAY
        ============================================== */
        .mobile-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.5);
            z-index: 1039;
            backdrop-filter: blur(2px);
        }

        .mobile-overlay.show { display: block; }

        /* ==============================================
           TOPBAR
        ============================================== */
        .topbar {
            height: var(--topbar-h);
            background: #ffffff;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 1030;
            display: flex;
            align-items: center;
            padding: 0 20px;
            gap: 12px;
            transition: background .3s;
        }

        .topbar-toggle {
            width: 40px; height: 40px;
            border-radius: 10px;
            border: none;
            background: #f1f5f9;
            color: #475569;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: .2s;
            flex-shrink: 0;
        }

        .topbar-toggle:hover { background: #e2e8f0; color: #1e293b; }

        .topbar-title {
            font-weight: 700;
            font-size: 15px;
            color: #1e293b;
            flex: 1;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .topbar-btn {
            width: 40px; height: 40px;
            border-radius: 10px;
            border: none;
            background: #f1f5f9;
            color: #475569;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: .2s;
        }

        .topbar-btn:hover { background: #e2e8f0; color: #1e293b; }

        /* Jam Realtime Badge */
        .time-badge {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            padding: 4px 12px;
            background: #f1f5f9;
            border-radius: 10px;
            line-height: 1.3;
        }

        .time-badge .time-jam {
            font-size: 14px;
            font-weight: 700;
            color: #1e293b;
        }

        .time-badge .time-tanggal {
            font-size: 10px;
            color: #64748b;
        }

        body.dark-mode .time-badge .time-jam { color: #e2e8f0; }
        body.dark-mode .time-badge .time-tanggal { color: #94a3b8; }

        /* User Chip */
        .user-chip {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px 6px 6px;
            background: #f1f5f9;
            border-radius: 20px;
            cursor: pointer;
            text-decoration: none;
            transition: .2s;
            border: none;
        }

        .user-chip:hover { background: #e2e8f0; }

        .user-chip-avatar {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-chip-name {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* ==============================================
           MAIN CONTENT
        ============================================== */
        .main-wrapper {
            transition: margin-left .3s;
        }

        .page-content {
            min-height: calc(100vh - var(--topbar-h));
            padding: 24px;
        }

        /* ==============================================
           BOTTOM NAV — MOBILE & TABLET ONLY
        ============================================== */
        .bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: var(--bottombar-h);
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            z-index: 1031;
            box-shadow: 0 -4px 20px rgba(0,0,0,.08);
            padding: 0 4px;
        }

        .bottom-nav-inner {
            display: flex;
            height: 100%;
            align-items: center;
        }

        .bottom-nav-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            padding: 8px 2px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 9px;
            font-weight: 600;
            border-radius: 12px;
            transition: .2s;
            position: relative;
            text-align: center;
        }

        .bottom-nav-item i {
            font-size: 19px;
            transition: .2s;
        }

        .bottom-nav-item.active {
            color: var(--primary);
        }

        .bottom-nav-item.active i {
            transform: translateY(-2px);
        }

        .bottom-nav-item.active::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 32px;
            height: 3px;
            background: var(--primary);
            border-radius: 0 0 4px 4px;
        }

        /* ==============================================
           RESPONSIVE BREAKPOINTS
        ============================================== */

        /* DESKTOP */
        @media (min-width: 992px) {
            .sidebar { transform: translateX(0) !important; }
            .main-wrapper { margin-left: var(--sidebar-w); }
            .topbar-toggle { display: none; }
            .bottom-nav { display: none !important; }
            body { padding-bottom: 0; }
        }

        /* TABLET */
        @media (min-width: 768px) and (max-width: 991px) {
            .sidebar { transform: translateX(calc(-1 * var(--sidebar-w))); }
            .sidebar.show { transform: translateX(0); box-shadow: 8px 0 32px rgba(0,0,0,.2); }
            .main-wrapper { margin-left: 0; }
            .bottom-nav { display: flex; }
            body { padding-bottom: var(--bottombar-h); }
        }

        /* MOBILE */
        @media (max-width: 767px) {
            .sidebar { transform: translateX(calc(-1 * var(--sidebar-w))); }
            .sidebar.show { transform: translateX(0); box-shadow: 8px 0 32px rgba(0,0,0,.2); }
            .main-wrapper { margin-left: 0; }
            .bottom-nav { display: flex; }
            .page-content { padding: 16px; }
            .user-chip-name { display: none; }
            .time-badge { display: none !important; }
        }

        /* ==============================================
           RESPONSIVE TABLE → CARD DI MOBILE
           ──────────────────────────────────────────────
           Cara pakai:
           1. Tambah class "table-mobile-card" ke <table>
           2. Tambah data-label="Nama Kolom" ke tiap <td>
        ============================================== */
        @media (max-width: 767px) {

            .table-responsive {
                overflow-x: unset !important;
            }

            .table-mobile-card {
                border: none !important;
                background: transparent !important;
            }

            .table-mobile-card thead {
                display: none !important;
            }

            .table-mobile-card,
            .table-mobile-card tbody,
            .table-mobile-card tr,
            .table-mobile-card td {
                border: none !important;
            }

            .table-mobile-card tbody tr {
                display: block !important;
                background: #ffffff;
                border-radius: 14px !important;
                margin-bottom: 12px !important;
                padding: 6px 12px !important;
                box-shadow: 0 2px 12px rgba(0,0,0,.07) !important;
                border: 1px solid #e2e8f0 !important;
            }

            body.dark-mode .table-mobile-card tbody tr {
                background: #1e293b !important;
                border-color: #334155 !important;
                box-shadow: 0 2px 12px rgba(0,0,0,.2) !important;
            }

            .table-mobile-card tbody td {
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
                padding: 9px 4px !important;
                font-size: 13px !important;
                border-bottom: 1px solid #f1f5f9 !important;
                gap: 12px;
                min-height: 40px;
                text-align: right;
            }

            body.dark-mode .table-mobile-card tbody td {
                border-bottom-color: #334155 !important;
            }

            .table-mobile-card tbody td:last-child {
                border-bottom: none !important;
            }

            .table-mobile-card tbody td::before {
                content: attr(data-label);
                font-size: 11px;
                font-weight: 700;
                color: #94a3b8;
                text-transform: uppercase;
                letter-spacing: .6px;
                flex-shrink: 0;
                white-space: nowrap;
                text-align: left;
            }

            body.dark-mode .table-mobile-card tbody td::before {
                color: #64748b;
            }

            .table-mobile-card .btn {
                font-size: 12px !important;
                padding: 5px 11px !important;
                border-radius: 8px !important;
            }

            .table-mobile-card .d-flex {
                justify-content: flex-end;
                flex-wrap: wrap;
                gap: 4px;
            }

        }

    </style>
</head>

<body>

{{-- ===================================================
     OVERLAY
     =================================================== --}}
<div class="mobile-overlay" id="mobileOverlay" onclick="closeSidebar()"></div>


{{-- ===================================================
     SIDEBAR
     =================================================== --}}
<div class="sidebar" id="sidebar">

    {{-- Header --}}
    <div class="sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <div class="sidebar-brand-icon">
                <i class="fas fa-cash-register"></i>
            </div>
            <div>
                <div class="sidebar-brand-text">Kasir Toko</div>
                <div class="sidebar-brand-sub">Panel Admin</div>
            </div>
        </a>
    </div>

    {{-- Menu --}}
    <ul class="sidebar-menu">

        <li class="sidebar-label">Menu Utama</li>

        <li>
            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="si-icon"><i class="fas fa-chart-line"></i></span>
                Dashboard
            </a>
        </li>

        <li>
            <a href="{{ route('admin.products.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <span class="si-icon"><i class="fas fa-boxes"></i></span>
                Kelola Produk
            </a>
        </li>

        <li>
            <a href="{{ route('admin.karyawan.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.karyawan.*') ? 'active' : '' }}">
                <span class="si-icon"><i class="fas fa-users"></i></span>
                Kelola Karyawan
            </a>
        </li>

        <hr class="sidebar-divider">
        <li class="sidebar-label">Laporan</li>

        <li>
            <a href="{{ route('admin.transaksi.riwayat') }}"
               class="sidebar-link {{ request()->routeIs('admin.transaksi.*') ? 'active' : '' }}">
                <span class="si-icon"><i class="fas fa-receipt"></i></span>
                Riwayat Transaksi
            </a>
        </li>

        <li>
            <a href="{{ route('admin.top-products.monthly') }}"
               class="sidebar-link {{ request()->routeIs('admin.top-products.monthly') ? 'active' : '' }}">
                <span class="si-icon"><i class="fas fa-star"></i></span>
                Produk Terlaris
            </a>
        </li>

    </ul>

    {{-- Footer User --}}
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div style="flex:1; min-width:0;">
                <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                <div class="sidebar-user-role">Administrator</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Logout"
                    style="width:30px;height:30px;border-radius:8px;border:none;background:rgba(255,255,255,.15);color:white;font-size:14px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:.2s;"
                    onmouseover="this.style.background='rgba(255,255,255,.3)'"
                    onmouseout="this.style.background='rgba(255,255,255,.15)'">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>

</div>


{{-- ===================================================
     MAIN WRAPPER
     =================================================== --}}
<div class="main-wrapper">

    {{-- TOPBAR --}}
    <nav class="topbar">

        <button class="topbar-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>

        <div class="topbar-title" id="pageTitle">Dashboard</div>

        <div class="topbar-actions ms-auto">

            {{-- Jam Realtime --}}
            <div class="time-badge d-none d-md-flex" id="timeBadge">
                <span class="time-jam" id="jam">00:00:00</span>
                <span class="time-tanggal" id="tanggal">-</span>
            </div>

            {{-- Dark Mode --}}
            <button class="topbar-btn" onclick="toggleDarkMode()" title="Toggle Dark Mode" id="darkBtn">
                <i class="fas fa-moon"></i>
            </button>

            {{-- User Dropdown --}}
            <div class="dropdown">
                <a href="#" class="user-chip dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"
                   style="text-decoration:none;">
                    <div class="user-chip-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <span class="user-chip-name">{{ auth()->user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius:12px; min-width:200px;">
                    <li class="px-3 py-2">
                        <div style="font-size:12px; color:#64748b;">Login sebagai</div>
                        <div style="font-weight:700; font-size:14px;">Administrator</div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item text-danger d-flex align-items-center gap-2"
                                    style="font-size:14px; padding:10px 16px;">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

        </div>
    </nav>

    {{-- PAGE CONTENT --}}
    <div class="page-content">
        @yield('content')
    </div>

</div>


{{-- ===================================================
     BOTTOM NAV — MOBILE & TABLET
     =================================================== --}}
<nav class="bottom-nav" id="bottomNav">
    <div class="bottom-nav-inner">

        <a href="{{ route('admin.dashboard') }}"
           class="bottom-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.products.index') }}"
           class="bottom-nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <i class="fas fa-boxes"></i>
            <span>Produk</span>
        </a>

        <a href="{{ route('admin.karyawan.index') }}"
           class="bottom-nav-item {{ request()->routeIs('admin.karyawan.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            <span>Karyawan</span>
        </a>

        <a href="{{ route('admin.transaksi.riwayat') }}"
           class="bottom-nav-item {{ request()->routeIs('admin.transaksi.*') ? 'active' : '' }}">
            <i class="fas fa-receipt"></i>
            <span>Transaksi</span>
        </a>

        <a href="{{ route('admin.top-products.monthly') }}"
           class="bottom-nav-item {{ request()->routeIs('admin.top-products.monthly') ? 'active' : '' }}">
            <i class="fas fa-star"></i>
            <span>Terlaris</span>
        </a>

    </div>
</nav>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

    /* ===== JAM REALTIME ===== */
    function updateWaktu(){
        const now = new Date();
        const el_jam     = document.getElementById('jam');
        const el_tanggal = document.getElementById('tanggal');
        if(el_jam)     el_jam.textContent     = now.toLocaleTimeString('id-ID');
        if(el_tanggal) el_tanggal.textContent = now.toLocaleDateString('id-ID', {
            weekday: 'short', day: 'numeric', month: 'short', year: 'numeric'
        });
    }
    setInterval(updateWaktu, 1000);
    updateWaktu();


    /* ===== DARK MODE ===== */
    function toggleDarkMode(){
        document.body.classList.toggle('dark-mode');
        const isDark = document.body.classList.contains('dark-mode');
        localStorage.setItem('darkMode', isDark ? 'enabled' : 'disabled');
        updateDarkIcon(isDark);
    }

    function updateDarkIcon(isDark){
        const btn = document.getElementById('darkBtn');
        if(!btn) return;
        btn.querySelector('i').className = isDark ? 'fas fa-sun' : 'fas fa-moon';
    }

    const savedDark = localStorage.getItem('darkMode') === 'enabled';
    if(savedDark){
        document.body.classList.add('dark-mode');
        updateDarkIcon(true);
    }


    /* ===== SIDEBAR TOGGLE ===== */
    function toggleSidebar(){
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobileOverlay');
        const isOpen  = sidebar.classList.contains('show');
        if(isOpen){
            closeSidebar();
        } else {
            sidebar.classList.add('show');
            overlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeSidebar(){
        document.getElementById('sidebar').classList.remove('show');
        document.getElementById('mobileOverlay').classList.remove('show');
        document.body.style.overflow = '';
    }

    window.addEventListener('resize', function(){
        if(window.innerWidth >= 992) closeSidebar();
    });

    document.addEventListener('keydown', function(e){
        if(e.key === 'Escape') closeSidebar();
    });


    /* ===== DYNAMIC PAGE TITLE ===== */
    const activeLink = document.querySelector('.sidebar-link.active, .bottom-nav-item.active');
    if(activeLink){
        const text = activeLink.textContent.trim();
        const titleEl = document.getElementById('pageTitle');
        if(titleEl && text) titleEl.textContent = text;
    }

</script>

@yield('scripts')

</body>
</html>