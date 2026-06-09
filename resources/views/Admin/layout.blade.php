<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — BimmerGuide</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-w: 220px;
            --navy: #0f172a;
            --navy-2: #1e293b;
            --indigo: #4f46e5;
            --indigo-light: #6366f1;
            --surface: #f8fafc;
            --border: #e2e8f0;
            --text: #0f172a;
            --muted: #64748b;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--surface);
            color: var(--text);
            display: flex;
            min-height: 100vh;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--navy);
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            z-index: 50;
        }
        .sidebar-logo {
            padding: 24px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }
        .sidebar-logo .brand {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.3px;
        }
        .sidebar-logo .brand span { color: #818cf8; }
        .sidebar-logo .badge {
            display: inline-block;
            margin-top: 6px;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #818cf8;
            background: rgba(99,102,241,0.15);
            border: 1px solid rgba(99,102,241,0.3);
            padding: 2px 8px;
            border-radius: 4px;
        }
        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .sidebar-section {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.25);
            padding: 12px 8px 6px;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            color: rgba(255,255,255,0.55);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 450;
            transition: all 0.15s;
        }
        .nav-item:hover { background: rgba(255,255,255,0.07); color: #fff; }
        .nav-item.active {
            background: var(--indigo);
            color: #fff;
            font-weight: 500;
        }
        .nav-item svg { width: 16px; height: 16px; flex-shrink: 0; opacity: 0.8; }
        .nav-item.active svg { opacity: 1; }
        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,0.07);
        }
        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border-radius: 8px;
        }
        .user-avatar {
            width: 32px; height: 32px;
            border-radius: 8px;
            background: var(--indigo);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 12px; font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }
        .user-name { font-size: 13px; font-weight: 500; color: #fff; }
        .user-role { font-size: 11px; color: rgba(255,255,255,0.4); }

        .sidebar-sep {
            height: 1px;
            background: rgba(255,255,255,0.07);
            margin: 4px 12px;
        }
        .nav-item.danger:hover { background: rgba(239,68,68,0.15); color: #f87171; }

        /* ── Main content ── */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .topbar {
            background: #fff;
            border-bottom: 1px solid var(--border);
            padding: 0 32px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 40;
        }
        .page-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 15px;
            font-weight: 600;
            color: var(--text);
        }
        .page-subtitle { font-size: 12px; color: var(--muted); margin-top: 1px; }
        .topbar-date { font-size: 12px; color: var(--muted); }

        .content { padding: 28px 32px; flex: 1; }

        /* ── Cards & components ── */
        .card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }
        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 14px;
            font-weight: 600;
        }
        .card-body { padding: 20px; }

        /* stat cards */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
        .stat-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        .stat-card::after {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 60px; height: 60px;
            border-radius: 0 0 0 60px;
            opacity: 0.06;
        }
        .stat-card.blue::after { background: #4f46e5; }
        .stat-card.green::after { background: #10b981; }
        .stat-card.amber::after { background: #f59e0b; }
        .stat-card.rose::after { background: #f43f5e; }
        .stat-icon {
            width: 36px; height: 36px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 14px;
        }
        .stat-icon svg { width: 18px; height: 18px; }
        .stat-icon.blue { background: #ede9fe; color: #4f46e5; }
        .stat-icon.green { background: #d1fae5; color: #059669; }
        .stat-icon.amber { background: #fef3c7; color: #d97706; }
        .stat-icon.rose { background: #ffe4e6; color: #e11d48; }
        .stat-value {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 28px;
            font-weight: 700;
            color: var(--text);
            line-height: 1;
        }
        .stat-label { font-size: 12.5px; color: var(--muted); margin-top: 4px; }

        /* table */
        .tbl { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        .tbl th {
            padding: 10px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: var(--muted);
            background: var(--surface);
            border-bottom: 1px solid var(--border);
        }
        .tbl td {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        .tbl tr:last-child td { border-bottom: none; }
        .tbl tr:hover td { background: #f8fafc; }

        /* badge */
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 500; }
        .badge-blue { background: #ede9fe; color: #4f46e5; }
        .badge-green { background: #d1fae5; color: #059669; }
        .badge-amber { background: #fef3c7; color: #d97706; }
        .badge-red { background: #ffe4e6; color: #e11d48; }
        .badge-gray { background: #f1f5f9; color: var(--muted); }

        /* buttons */
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; border: none; transition: all 0.15s; text-decoration: none; }
        .btn-primary { background: var(--indigo); color: #fff; }
        .btn-primary:hover { background: #4338ca; }
        .btn-ghost { background: transparent; color: var(--muted); border: 1px solid var(--border); }
        .btn-ghost:hover { background: var(--surface); color: var(--text); }
        .btn-danger-ghost { background: transparent; color: #e11d48; border: 1px solid #fecdd3; }
        .btn-danger-ghost:hover { background: #ffe4e6; }
        .btn-sm { padding: 5px 10px; font-size: 12px; }
        .btn-icon { padding: 6px; border-radius: 6px; }

        /* flash */
        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .alert-error {
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #9f1239;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
        }

        /* tab filters */
        .tab-bar { display: flex; gap: 4px; flex-wrap: wrap; margin-bottom: 20px; }
        .tab-btn {
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 450;
            color: var(--muted);
            background: #fff;
            border: 1px solid var(--border);
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s;
        }
        .tab-btn:hover { border-color: var(--indigo); color: var(--indigo); }
        .tab-btn.active { background: var(--indigo); color: #fff; border-color: var(--indigo); font-weight: 500; }

        /* form */
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 12.5px; font-weight: 500; color: var(--text); margin-bottom: 6px; }
        .form-control {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 13.5px;
            font-family: inherit;
            color: var(--text);
            background: #fff;
            transition: border-color 0.15s;
            outline: none;
        }
        .form-control:focus { border-color: var(--indigo); box-shadow: 0 0 0 3px rgba(79,70,229,0.08); }
        .form-select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; padding-right: 32px; }
        textarea.form-control { resize: vertical; min-height: 80px; }
        .form-hint { font-size: 11.5px; color: var(--muted); margin-top: 4px; }
        .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }

        /* pagination */
        .pagination { display: flex; gap: 4px; align-items: center; }
        .pagination a, .pagination span {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 32px; height: 32px; padding: 0 8px;
            border-radius: 6px; font-size: 13px;
            border: 1px solid var(--border);
            color: var(--muted);
            text-decoration: none;
            transition: all 0.15s;
        }
        .pagination a:hover { border-color: var(--indigo); color: var(--indigo); }
        .pagination .active span { background: var(--indigo); border-color: var(--indigo); color: #fff; font-weight: 600; }
        .pagination .disabled span { opacity: 0.4; pointer-events: none; }

        /* mini chart bar */
        .bar-wrap { height: 6px; background: #f1f5f9; border-radius: 3px; overflow: hidden; }
        .bar-fill { height: 100%; background: var(--indigo); border-radius: 3px; transition: width 0.6s ease; }

        /* empty state */
        .empty-state { padding: 60px 20px; text-align: center; color: var(--muted); }
        .empty-state svg { width: 40px; height: 40px; margin: 0 auto 12px; opacity: 0.3; }
        .empty-state p { font-size: 13.5px; }

        /* top bar actions */
        .topbar-actions { display: flex; align-items: center; gap: 12px; }
    </style>
</head>
<body>



{{-- Sidebar --}}
<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="brand">Bimmer<span>Guide</span></div>
        <div class="badge">Admin Panel</div>
    </div>

    <nav class="sidebar-nav">
        <div class="sidebar-section">Menu</div>

        <a href="{{ route('admin.dashboard') }}"
           class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
            </svg>
            Dashboard
        </a>

        <div class="sidebar-section">Data</div>

        <a href="{{ route('admin.mobil') }}"
           class="nav-item {{ request()->routeIs('admin.mobil*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M5 17H3a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v9a2 2 0 0 1-2 2h-2"/>
                <circle cx="7.5" cy="17.5" r="2.5"/><circle cx="16.5" cy="17.5" r="2.5"/>
            </svg>
            Data Mobil
        </a>

        <a href="{{ route('admin.seri') }}"
           class="nav-item {{ request()->routeIs('admin.seri*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2V9M9 21H5a2 2 0 0 1-2-2V9m0 0h18"/>
            </svg>
            Data Seri
        </a>

        <a href="{{ route('admin.kriteria') }}"
           class="nav-item {{ request()->routeIs('admin.kriteria*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                <rect x="9" y="3" width="6" height="4" rx="1"/><path d="m9 12 2 2 4-4"/>
            </svg>
            Kriteria
        </a>

        <div class="sidebar-section">Analisis</div>

        <a href="{{ route('admin.riwayat') }}"
           class="nav-item {{ request()->routeIs('admin.riwayat*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/>
                <line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
            </svg>
            Riwayat Analisis
        </a>

        <a href="{{ route('admin.user') }}"
           class="nav-item {{ request()->routeIs('admin.user*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            Kelola User
        </a>

        <div class="sidebar-sep"></div>

        <a href="{{ route('home') }}" class="nav-item">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            Kembali ke Situs
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-item danger" style="width:100%;background:none;cursor:pointer;font-family:inherit;">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Logout
            </button>
        </form>
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ Auth::user()->name ?? 'Admin' }}</div>
                <div class="user-role">Administrator</div>
            </div>
        </div>
    </div>
</aside>

{{-- Main --}}
<div class="main">
    <div class="topbar">
        <div>
            <div class="page-title">@yield('page-title', 'Dashboard')</div>
            <div class="page-subtitle">@yield('page-subtitle', '')</div>
        </div>
        <div class="topbar-actions">
            <span class="topbar-date">{{ now()->translatedFormat('l, d F Y') }}</span>
            @yield('topbar-actions')
        </div>
    </div>

    <div class="content">
        @if(session('success'))
            <div class="alert-success">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path d="m5 12 5 5L20 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif
        @yield('content')
    </div>
</div>

</body>
</html>