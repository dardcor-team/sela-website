<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard | SELA')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik+Mono+One&family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/sela.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { 
                        sans: ['DM Sans', 'sans-serif'],
                        mono: ['Rubik Mono One', 'monospace']
                    },
                    colors: {
                        cyan: '#06b6d4',
                        primary: { DEFAULT: '#06b6d4', dark: '#0891b2', light: '#cffafe' },
                    },
                    boxShadow: {
                        'neo': '4px 4px 0px #000',
                        'neo-hover': '6px 6px 0px #000',
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
            font-family: 'DM Sans', sans-serif;
            background-color: #ffffff;
            color: #000;
            background-image: radial-gradient(#000 1px, transparent 1px);
            background-size: 20px 20px;
        }
        .dark body { 
            background-color: #111; color: #fff; 
            background-image: radial-gradient(#333 1px, transparent 1px);
        }

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
            border-right: 4px solid #000;
            z-index: 100;
            transition: transform 0.3s cubic-bezier(0.19,1,0.22,1);
            overflow-y: auto;
        }
        .dark #sidebar { background: #000; border-right-color: #fff; }

        @media (max-width: 992px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); }
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: #000;
            text-decoration: none;
            font-weight: 700;
            border: 3px solid transparent;
            border-radius: 12px;
            margin: 4px 16px;
            transition: all 0.2s cubic-bezier(0.19,1,0.22,1);
            font-size: 0.95rem;
        }
        .dark .nav-item { color: #fff; }
        .nav-item svg { width: 22px; height: 22px; }

        .nav-item:hover {
            background: #cffafe;
            color: #000;
            border-color: #000;
            transform: translate(-2px, -2px);
            box-shadow: 4px 4px 0 #000;
        }
        .dark .nav-item:hover {
            background: #000;
            color: #06b6d4;
            border-color: #fff;
            box-shadow: 4px 4px 0 #fff;
        }
        .nav-item.active {
            background: #06b6d4;
            color: #fff;
            border-color: #000;
            box-shadow: 4px 4px 0 #000;
        }
        .dark .nav-item.active {
            background: #06b6d4;
            color: #000;
            border-color: #fff;
            box-shadow: 4px 4px 0 #fff;
        }

        .nav-section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #000;
            margin: 20px 20px 8px;
            font-weight: 800;
            font-family: 'Rubik Mono One', monospace;
        }
        .dark .nav-section-title { color: #fff; }

        /* Main Wrapper */
        #main-wrapper {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        @media (max-width: 992px) {
            #main-wrapper { margin-left: 0; }
        }

        /* Topbar */
        #topbar {
            height: 75px;
            background: #fff;
            border-bottom: 4px solid #000;
            display: flex;
            align-items: center;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 90;
        }
        .dark #topbar { background: #000; border-bottom-color: #fff; }

        #content { padding: 32px 24px; flex: 1; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; border-left: 2px solid #000; }
        .dark ::-webkit-scrollbar-track { border-left-color: #fff; }
        ::-webkit-scrollbar-thumb { background: #000; }
        .dark ::-webkit-scrollbar-thumb { background: #fff; }

        /* Neo Brutalism Components */
        .neo-card {
            background: #fff;
            border: 4px solid #000;
            border-radius: 16px;
            box-shadow: 6px 6px 0 #000;
            padding: 24px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .dark .neo-card { background: #111; border-color: #fff; box-shadow: 6px 6px 0 #fff; }
        .neo-card:hover {
            transform: translate(-3px, -3px);
            box-shadow: 9px 9px 0 #000;
        }
        .dark .neo-card:hover { box-shadow: 9px 9px 0 #fff; }

        .neo-btn {
            background: #06b6d4;
            color: #fff;
            border: 3px solid #000;
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 700;
            font-family: 'Rubik Mono One', monospace;
            font-size: 0.8rem;
            cursor: pointer;
            box-shadow: 4px 4px 0 #000;
            transition: all 0.2s cubic-bezier(0.19,1,0.22,1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .dark .neo-btn { border-color: #fff; box-shadow: 4px 4px 0 #fff; color: #000; }
        .neo-btn:hover {
            transform: translate(-3px, -3px);
            box-shadow: 7px 7px 0 #000;
        }
        .dark .neo-btn:hover { box-shadow: 7px 7px 0 #fff; }

        /* Badges */
        .badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 800; border: 2px solid #000; box-shadow: 2px 2px 0 #000; text-transform: uppercase; letter-spacing: 1px; }
        .dark .badge { border-color: #fff; box-shadow: 2px 2px 0 #fff; }
        .badge-primary { background: #06b6d4; color: #fff; }
        .badge-success { background: #a3e635; color: #000; }
        .badge-warning { background: #fde047; color: #000; }
        .badge-danger  { background: #f43f5e; color: #fff; }
        .badge-info    { background: #38bdf8; color: #000; }

        .dark .badge-primary { color: #000; }
    </style>

    @yield('styles')
</head>
<body>

<!-- Sidebar Overlay (mobile) -->
<div id="sidebar-overlay" onclick="closeSidebar()"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.8); z-index:99;"></div>

<!-- Sidebar -->
<aside id="sidebar">
    <!-- Logo -->
    <div style="padding: 24px 20px; border-bottom: 4px solid #000;" class="dark:border-white">
        <a href="{{ route('admin.overview') }}" style="display:flex; align-items:center; gap:12px; text-decoration:none;">
            <div style="width:44px; height:44px; background:#06b6d4; border:3px solid #000; border-radius:12px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:22px; font-weight:800; font-family:'Rubik Mono One', monospace; flex-shrink:0; box-shadow:3px 3px 0 #000;">S</div>
            <span style="font-size:26px; font-family:'Rubik Mono One', monospace; color:#000;" class="dark:text-white">SELA</span>
        </a>
    </div>

    <!-- Nav -->
    <nav style="flex:1; padding: 16px 0; overflow-y: auto;">
        <div class="nav-section-title">Utama</div>
        <a href="{{ route('admin.overview') }}" class="nav-item {{ Route::is('admin.overview') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <rect x="3" y="3" width="7" height="7" rx="1"/>
                <rect x="14" y="3" width="7" height="7" rx="1"/>
                <rect x="3" y="14" width="7" height="7" rx="1"/>
                <rect x="14" y="14" width="7" height="7" rx="1"/>
            </svg>
            Dashboard
        </a>

        <div class="nav-section-title" style="margin-top:24px;">Kelola Data</div>
        <a href="{{ route('admin.users') }}" class="nav-item {{ Route::is('admin.users') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
            </svg>
            Kelola Pengguna
        </a>
        <a href="{{ route('admin.groups') }}" class="nav-item {{ Route::is('admin.groups') || Route::is('admin.groups.detail') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 11h8M8 15h5"/>
            </svg>
            Kelompok & Tugas
        </a>

        <div class="nav-section-title" style="margin-top:24px;">Konfigurasi</div>
        <a href="{{ route('admin.system') }}" class="nav-item {{ Route::is('admin.system') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            Sistem & Pemeliharaan
        </a>
    </nav>

    <!-- Sidebar Footer: User + Logout -->
    <div style="border-top: 4px solid #000; padding: 20px;" class="dark:border-white">
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full py-3 px-4 bg-white dark:bg-black text-red-500 border-3 border-black dark:border-white rounded-xl font-bold font-mono text-[10px] flex items-center justify-center gap-2 shadow-[4px_4px_0_#000] dark:shadow-[4px_4px_0_#fff] hover:-translate-y-1 hover:-translate-x-1 hover:shadow-[6px_6px_0_#000] dark:hover:shadow-[6px_6px_0_#fff] transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                KELUAR
            </button>
        </form>
    </div>
</aside>

<!-- Main Wrapper -->
<div id="main-wrapper">

    <!-- Top Navbar -->
    <header id="topbar">
        <!-- Hamburger (mobile) -->
        <button onclick="openSidebar()" style="display:none; margin-right:16px; padding:8px; border:3px solid #000; border-radius:10px; background:#fff; cursor:pointer; box-shadow:3px 3px 0 #000;" id="hamburger-btn" class="dark:border-white dark:bg-black dark:shadow-[3px_3px_0_#fff]">
            <svg style="width:20px; height:20px; color:#000;" class="dark:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <h1 class="text-xl md:text-2xl font-mono font-bold text-black dark:text-white uppercase hidden md:block tracking-wider">@yield('page_title', 'SELA ADMIN')</h1>

        <!-- Right Actions -->
        <div style="margin-left:auto; display:flex; align-items:center; gap:16px;">
            <!-- Theme toggle -->
            <button onclick="toggleTheme()" class="w-10 h-10 flex items-center justify-center border-3 border-black dark:border-white bg-white dark:bg-black rounded-xl shadow-[3px_3px_0_#000] dark:shadow-[3px_3px_0_#fff] hover:-translate-y-1 hover:-translate-x-1 hover:shadow-[5px_5px_0_#000] dark:hover:shadow-[5px_5px_0_#fff] transition-all text-black dark:text-white cursor-pointer">
                <svg id="icon-sun" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <circle cx="12" cy="12" r="5"/>
                    <path stroke-linecap="round" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
                </svg>
                <svg id="icon-moon" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z"/>
                </svg>
            </button>

            <!-- Avatar -->
            <div class="relative cursor-pointer">
                <div class="w-10 h-10 border-3 border-black dark:border-white bg-[#fde047] text-black rounded-xl shadow-[3px_3px_0_#000] dark:shadow-[3px_3px_0_#fff] flex items-center justify-center font-mono font-bold text-lg">SA</div>
                <span class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-green-400 border-2 border-black dark:border-white rounded-full"></span>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main id="content">
        @if(session('success'))
        <div class="bg-[#a3e635] border-4 border-black p-4 mb-8 rounded-xl shadow-[4px_4px_0_#000] flex items-center gap-3 text-black font-bold text-sm md:text-base">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span><strong>SUKSES!</strong> {{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-[#f43f5e] border-4 border-black p-4 mb-8 rounded-xl shadow-[4px_4px_0_#000] flex items-center gap-3 text-white font-bold text-sm md:text-base">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span><strong>GAGAL!</strong> {{ session('error') }}</span>
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
    if (window.innerWidth <= 992) {
        var btn = document.getElementById('hamburger-btn');
        if (btn) btn.style.display = 'flex';
    }
    window.addEventListener('resize', function() {
        var btn = document.getElementById('hamburger-btn');
        if (btn) btn.style.display = window.innerWidth <= 992 ? 'flex' : 'none';
    });
</script>

@yield('scripts')
</body>
</html>