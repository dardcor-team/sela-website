<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard | SELA')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/sela.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Public Sans', 'sans-serif'] },
                    colors: {
                        primary: { DEFAULT: '#696cff', dark: '#5f61e6', light: 'rgba(105,108,255,0.16)' },
                    }
                }
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function() {
            var theme = localStorage.getItem('admin-theme');
            if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html, body { margin: 0; padding: 0; height: 100%; }
        body {
            font-family: 'Public Sans', sans-serif;
            background-color: #f5f5f9;
            color: #566a7f;
        }
        .dark body { background-color: #28243d; color: #cdd0d8; }

        /* Sidebar */
        #sidebar {
            width: 260px;
            min-width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            background: #fff;
            border-right: 1px solid #e7e7ff;
            z-index: 100;
            transition: transform 0.3s ease;
            overflow-y: auto;
        }
        .dark #sidebar { background: #2b2c40; border-color: #3b3d55; }

        /* Main layout */
        #main-wrapper {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navbar */
        #topbar {
            background: #fff;
            border-bottom: 1px solid #e7e7ff;
            height: 64px;
            display: flex;
            align-items: center;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .dark #topbar { background: #2b2c40; border-color: #3b3d55; }

        /* Nav items */
        .nav-section-title {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #a8aabc;
            padding: 0 20px;
            margin: 20px 0 6px;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            margin: 2px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            color: #697a8d;
            text-decoration: none;
            transition: all 0.2s;
            position: relative;
        }
        .nav-item:hover { background: rgba(105,108,255,0.08); color: #696cff; }
        .nav-item.active { background: rgba(105,108,255,0.12); color: #696cff; font-weight: 600; }
        .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }
        .dark .nav-item { color: #a8aabc; }
        .dark .nav-item:hover { background: rgba(105,108,255,0.12); color: #696cff; }
        .dark .nav-item.active { background: rgba(105,108,255,0.16); color: #696cff; }

        /* Cards */
        .card {
            background: #fff;
            border-radius: 8px;
            border: 1px solid #e7e7ff;
            overflow: hidden;
        }
        .dark .card { background: #2b2c40; border-color: #3b3d55; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d9dbe9; border-radius: 10px; }
        .dark ::-webkit-scrollbar-thumb { background: #3b3d55; }

        /* Badges */
        .badge { display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; }
        .badge-primary { background: rgba(105,108,255,0.12); color: #696cff; }
        .badge-success { background: rgba(40,199,111,0.12); color: #28c76f; }
        .badge-warning { background: rgba(255,159,67,0.12); color: #ff9f43; }
        .badge-danger  { background: rgba(234,84,85,0.12);  color: #ea5455; }
        .badge-info    { background: rgba(0,207,232,0.12);  color: #00cfe8; }

        /* Mobile */
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); }
            #main-wrapper { margin-left: 0; }
            #sidebar-overlay { display: block !important; }
        }

        /* Content area */
        #content { flex: 1; padding: 24px; }
    </style>

    @yield('styles')
</head>
<body>

<!-- Sidebar Overlay (mobile) -->
<div id="sidebar-overlay" onclick="closeSidebar()"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:99;"></div>

<!-- Sidebar -->
<aside id="sidebar">
    <!-- Logo -->
    <div style="padding: 20px 20px 16px; border-bottom: 1px solid #e7e7ff;" class="dark:border-gray-700">
        <a href="{{ route('admin.overview') }}" style="display:flex; align-items:center; gap:10px; text-decoration:none;">
            <div style="width:34px; height:34px; background:#696cff; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:16px; font-weight:800; flex-shrink:0;">S</div>
            <span style="font-size:18px; font-weight:700; color:#696cff; letter-spacing:-0.3px;">sela</span>
        </a>
    </div>

    <!-- Nav -->
    <nav style="flex:1; padding: 8px 0;">
        <div class="nav-section-title">Utama</div>
        <a href="{{ route('admin.overview') }}" class="nav-item {{ Route::is('admin.overview') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7" rx="1"/>
                <rect x="14" y="3" width="7" height="7" rx="1"/>
                <rect x="3" y="14" width="7" height="7" rx="1"/>
                <rect x="14" y="14" width="7" height="7" rx="1"/>
            </svg>
            Dashboard
        </a>

        <div class="nav-section-title" style="margin-top:16px;">Kelola Data</div>
        <a href="{{ route('admin.users') }}" class="nav-item {{ Route::is('admin.users') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
            </svg>
            Kelola Pengguna
        </a>
        <a href="{{ route('admin.groups') }}" class="nav-item {{ Route::is('admin.groups') || Route::is('admin.groups.detail') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 11h8M8 15h5"/>
            </svg>
            Kelompok & Tugas
        </a>

        <div class="nav-section-title" style="margin-top:16px;">Konfigurasi</div>
        <a href="{{ route('admin.system') }}" class="nav-item {{ Route::is('admin.system') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            Sistem & Pemeliharaan
        </a>
    </nav>

    <!-- Sidebar Footer: User + Logout -->
    <div style="border-top: 1px solid #e7e7ff; padding: 16px;" class="dark:border-gray-700">
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:12px;">
            <div style="width:36px; height:36px; border-radius:50%; background:rgba(105,108,255,0.12); color:#696cff; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:700; flex-shrink:0;">SA</div>
            <div style="overflow:hidden; flex:1;">
                <div style="font-size:13px; font-weight:600; color:#566a7f;" class="dark:text-gray-200">Super Admin</div>
                <div style="font-size:11px; color:#a8aabc; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                    {{ Auth::guard('web')->user()?->email ?? 'admin@pens.ac.id' }}
                </div>
            </div>
        </div>
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" style="width:100%; padding:8px 12px; border-radius:8px; border:1px solid #e7e7ff; background:transparent; color:#697a8d; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:6px; transition:all 0.2s;" onmouseover="this.style.background='rgba(234,84,85,0.08)'; this.style.color='#ea5455'; this.style.borderColor='rgba(234,84,85,0.3)';" onmouseout="this.style.background='transparent'; this.style.color='#697a8d'; this.style.borderColor='#e7e7ff';">
                <svg style="width:14px; height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar
            </button>
        </form>
    </div>
</aside>

<!-- Main Wrapper -->
<div id="main-wrapper">

    <!-- Top Navbar -->
    <header id="topbar">
        <!-- Hamburger (mobile) -->
        <button onclick="openSidebar()" style="display:none; margin-right:12px; padding:6px; border:none; background:none; cursor:pointer; color:#697a8d;" id="hamburger-btn">
            <svg style="width:20px; height:20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <!-- Search -->
        <div style="display:flex; align-items:center; gap:8px; flex:1; max-width:280px; background:#f5f5f9; border-radius:8px; padding:8px 14px;" class="dark:bg-gray-700">
            <svg style="width:16px; height:16px; color:#a8aabc; flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35"/>
            </svg>
            <input type="text" placeholder="Search…" disabled style="border:none; outline:none; background:transparent; font-size:13px; color:#697a8d; width:100%;">
        </div>

        <!-- Right Actions -->
        <div style="margin-left:auto; display:flex; align-items:center; gap:4px;">
            <!-- Theme toggle -->
            <button onclick="toggleTheme()" title="Toggle Theme" style="width:38px; height:38px; border-radius:8px; border:none; background:transparent; cursor:pointer; color:#697a8d; display:flex; align-items:center; justify-content:center; transition:background 0.2s;" onmouseover="this.style.background='rgba(105,108,255,0.08)'" onmouseout="this.style.background='transparent'">
                <svg id="icon-sun" style="width:18px; height:18px; display:none;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="5"/>
                    <path stroke-linecap="round" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
                </svg>
                <svg id="icon-moon" style="width:18px; height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z"/>
                </svg>
            </button>

            <!-- Bell -->
            <button style="width:38px; height:38px; border-radius:8px; border:none; background:transparent; cursor:pointer; color:#697a8d; display:flex; align-items:center; justify-content:center; transition:background 0.2s;" onmouseover="this.style.background='rgba(105,108,255,0.08)'" onmouseout="this.style.background='transparent'">
                <svg style="width:18px; height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </button>

            <!-- Divider -->
            <div style="width:1px; height:24px; background:#e7e7ff; margin:0 8px;"></div>

            <!-- Avatar -->
            <div style="position:relative; cursor:pointer;">
                <div style="width:36px; height:36px; border-radius:50%; background:#696cff; color:#fff; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:700;">A</div>
                <span style="position:absolute; bottom:1px; right:1px; width:9px; height:9px; background:#28c76f; border-radius:50%; border:2px solid #fff;"></span>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main id="content">
        @if(session('success'))
        <div style="display:flex; align-items:flex-start; gap:10px; padding:14px 16px; background:rgba(40,199,111,0.1); border:1px solid rgba(40,199,111,0.3); border-radius:8px; margin-bottom:20px; color:#28c76f; font-size:13px;">
            <svg style="width:16px; height:16px; flex-shrink:0; margin-top:1px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span><strong>Sukses!</strong> {{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div style="display:flex; align-items:flex-start; gap:10px; padding:14px 16px; background:rgba(234,84,85,0.1); border:1px solid rgba(234,84,85,0.3); border-radius:8px; margin-bottom:20px; color:#ea5455; font-size:13px;">
            <svg style="width:16px; height:16px; flex-shrink:0; margin-top:1px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span><strong>Gagal!</strong> {{ session('error') }}</span>
        </div>
        @endif

        @yield('content')
    </main>
</div>

<script>
    // Theme
    function updateThemeIcons() {
        var isDark = document.documentElement.classList.contains('dark');
        var sun = document.getElementById('icon-sun');
        var moon = document.getElementById('icon-moon');
        if (sun) sun.style.display = isDark ? 'block' : 'none';
        if (moon) moon.style.display = isDark ? 'none' : 'block';
    }
    updateThemeIcons();

    function toggleTheme() {
        var isDark = document.documentElement.classList.toggle('dark');
        localStorage.setItem('admin-theme', isDark ? 'dark' : 'light');
        updateThemeIcons();
        // Reload to reapply chart colors
        location.reload();
    }

    // Mobile sidebar
    function openSidebar() {
        document.getElementById('sidebar').classList.add('open');
        document.getElementById('sidebar-overlay').style.display = 'block';
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebar-overlay').style.display = 'none';
    }

    // Show hamburger on mobile
    if (window.innerWidth <= 768) {
        var btn = document.getElementById('hamburger-btn');
        if (btn) btn.style.display = 'flex';
    }
    window.addEventListener('resize', function() {
        var btn = document.getElementById('hamburger-btn');
        if (btn) btn.style.display = window.innerWidth <= 768 ? 'flex' : 'none';
    });
</script>

@yield('scripts')
</body>
</html>
