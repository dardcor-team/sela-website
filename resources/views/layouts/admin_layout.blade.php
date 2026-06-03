<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard | SELA')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/sela.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { 
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: { DEFAULT: '#0089A5', dark: '#006CA5', light: '#00A3C4' },
                    },
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
        body { font-family: 'Inter', sans-serif; }
        
        /* Layout */
        #sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            background: #ffffff;
            border-right: 2px solid #cbd5e1;
            z-index: 100;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
        }
        .dark #sidebar { background: #0f172a; border-right-color: #334155; }
        #main-wrapper { margin-left: 260px; min-height: 100vh; background: #f1f5f9; transition: margin-left 0.3s ease; }
        .dark #main-wrapper { background: #020617; }
        #topbar { height: 64px; background: #fff; border-bottom: 2px solid #cbd5e1; }
        .dark #topbar { background: #0f172a; border-bottom-color: #334155; }
        
        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 90;
        }

        @media (max-width: 1024px) {
            #sidebar {
                transform: translateX(-100%);
            }
            #sidebar.open {
                transform: translateX(0);
            }
            #main-wrapper {
                margin-left: 0;
            }
            #sidebar-overlay.open {
                display: block;
            }
        }

        /* Nav */
        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            color: #334155;
            font-weight: 600;
            border-radius: 8px;
            margin: 4px 12px;
            font-size: 0.95rem;
        }
        .dark .nav-item { color: #f1f5f9; }

        .nav-item:hover { background: #f1f5f9; color: #0891b2; }
        .dark .nav-item:hover { background: #1e293b; color: #38bdf8; }
        .nav-item.active { background: #e0f2fe; color: #0369a1; font-weight: 700; }
        .dark .nav-item.active { background: #0c4a6e; color: #bae6fd; }

        /* Components */
        .card { background: #fff; border: 2px solid #e2e8f0; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); }
        .dark .card { background: #1e293b; border-color: #334155; box-shadow: none; }
        .btn-primary { background: #0089A5; color: #fff; padding: 8px 16px; border-radius: 8px; font-weight: 600; }
        .btn-primary:hover { background: #006CA5; }
        .badge { padding: 4px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 600; }
    </style>
</head>
<body class="bg-slate-100 dark:bg-slate-950 text-slate-900 dark:text-slate-100">

<div id="sidebar-overlay" onclick="toggleSidebar()"></div>

<aside id="sidebar">
    <div class="p-6 border-b border-slate-200 dark:border-slate-700">
        <a href="{{ route('admin.overview') }}" class="flex items-center gap-3 font-bold text-xl text-cyan-700">
            SELA Admin
        </a>
    </div>
        <nav class="p-4 flex-1">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 px-4">Utama</div>
            <a href="{{ route('admin.overview') }}" class="nav-item {{ Route::is('admin.overview') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 px-4 mt-6">Kelola Data</div>
            <a href="{{ route('admin.users') }}" class="nav-item {{ Route::is('admin.users') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Pengguna
            </a>
            <a href="{{ route('admin.groups') }}" class="nav-item {{ Route::is('admin.groups') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Kelompok
            </a>
            <a href="{{ route('admin.academic') }}" class="nav-item {{ Route::is('admin.academic') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                Data Akademik
            </a>
            
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 px-4 mt-6">Konfigurasi</div>
            <a href="{{ route('admin.system') }}" class="nav-item {{ Route::is('admin.system') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Sistem & Pemeliharaan
            </a>
        </nav>

        <!-- Logout -->
        <div class="p-4 border-t border-slate-200 dark:border-slate-700">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-item w-full text-rose-600 hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-900/20">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Keluar
                </button>
            </form>
        </div>
</aside>

<div id="main-wrapper">
    <header id="topbar" class="flex items-center justify-between px-4 lg:px-8">
        <div class="flex items-center gap-3">
            <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                <svg class="w-5 h-5 text-slate-600 dark:text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <h1 class="text-lg font-semibold flex items-center gap-2">
                <svg class="w-6 h-6 text-cyan-600 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                @yield('page_title', 'Dashboard')
            </h1>
        </div>
        <button onclick="toggleTheme()" class="p-2 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
            <svg id="icon-sun" class="w-5 h-5 hidden dark:block text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            <svg id="icon-moon" class="w-5 h-5 block dark:hidden text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
        </button>
    </header>
    <main id="content" class="p-4 lg:p-8 overflow-x-hidden">
        @yield('content')
    </main>
</div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebar-overlay').classList.toggle('open');
        }
        function updateThemeIcons() {
            var isDark = document.documentElement.classList.contains('dark');
            var sun = document.getElementById('icon-sun');
            var moon = document.getElementById('icon-moon');
            if (sun && moon) {
                sun.style.display = isDark ? 'block' : 'none';
                moon.style.display = isDark ? 'none' : 'block';
            }
        }
        updateThemeIcons();

        function toggleTheme() {
            var isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('admin-theme', isDark ? 'dark' : 'light');
            updateThemeIcons();
        }
    </script>
    @stack('scripts')
</body>
</html>