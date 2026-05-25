<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Super Admin | SELA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/sela.png') }}">
    <script>
        (function() {
            var t = localStorage.getItem('admin-theme');
            if (t === 'dark' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        })();
    </script>
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html, body { margin: 0; padding: 0; height: 100%; }
        body {
            font-family: 'Public Sans', sans-serif;
            background: #f5f5f9;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        [data-theme="dark"] body { background: #28243d; }

        .card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e7e7ff;
            box-shadow: 0 4px 24px rgba(105,108,255,0.06);
        }
        [data-theme="dark"] .card { background: #2b2c40; border-color: #3b3d55; }

        .input-field {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d9dbe9;
            border-radius: 8px;
            font-size: 14px;
            color: #566a7f;
            background: #fff;
            outline: none;
            font-family: 'Public Sans', sans-serif;
            transition: border-color 0.2s;
            box-sizing: border-box;
        }
        .input-field:focus { border-color: #696cff; box-shadow: 0 0 0 3px rgba(105,108,255,0.12); }
        [data-theme="dark"] .input-field { background: #3b3d55; border-color: #4a4c6a; color: #cdd0d8; }

        label { display: block; font-size: 12px; font-weight: 600; color: #697a8d; margin-bottom: 6px; }
        [data-theme="dark"] label { color: #a8aabc; }

        .btn-primary {
            width: 100%; padding: 11px; background: #696cff; color: #fff;
            border: none; border-radius: 8px; font-size: 14px; font-weight: 700;
            cursor: pointer; font-family: 'Public Sans', sans-serif; transition: opacity 0.2s;
        }
        .btn-primary:hover { opacity: 0.88; }

        h5 { margin: 0; font-size: 22px; font-weight: 700; color: #566a7f; }
        [data-theme="dark"] h5 { color: #e0e0e0; }
        p { margin: 0; }
    </style>
</head>
<body>
    <div style="flex:1; display:flex; align-items:center; justify-content:center; padding:32px 16px;">
        <div style="width:100%; max-width:400px;">

            {{-- Logo --}}
            <div style="text-align:center; margin-bottom:28px;">
                <div style="display:inline-flex; align-items:center; gap:10px; margin-bottom:8px;">
                    <div style="width:38px; height:38px; background:#696cff; border-radius:10px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:20px; font-weight:800;">S</div>
                    <span style="font-size:22px; font-weight:700; color:#696cff;">sela</span>
                </div>
                <p style="font-size:13px; color:#a8aabc;">Super Admin Panel</p>
            </div>

            {{-- Card --}}
            <div class="card" style="padding:32px;">
                <h5 style="margin-bottom:6px;">Selamat Datang! 👋</h5>
                <p style="font-size:13px; color:#a8aabc; margin-bottom:28px;">Masuk dengan akun Super Admin Anda</p>

                @if($errors->any())
                <div style="padding:12px 16px; background:rgba(234,84,85,0.08); border:1px solid rgba(234,84,85,0.25); border-radius:8px; margin-bottom:20px; color:#ea5455; font-size:13px;">
                    {{ $errors->first() }}
                </div>
                @endif

                @if(session('success'))
                <div style="padding:12px 16px; background:rgba(40,199,111,0.08); border:1px solid rgba(40,199,111,0.25); border-radius:8px; margin-bottom:20px; color:#28c76f; font-size:13px;">
                    {{ session('success') }}
                </div>
                @endif

                <form action="{{ route('admin.login.submit') }}" method="POST">
                    @csrf
                    <div style="margin-bottom:18px;">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="input-field" value="{{ old('email') }}" placeholder="admin@pens.ac.id" required>
                    </div>
                    <div style="margin-bottom:24px;">
                        <label for="password">Kata Sandi</label>
                        <input type="password" name="password" id="password" class="input-field" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn-primary">Masuk ke Dashboard</button>
                </form>
            </div>

            {{-- Theme Toggle --}}
            <div style="text-align:center; margin-top:20px;">
                <button onclick="toggleTheme()" style="background:none; border:none; cursor:pointer; font-size:12px; color:#a8aabc; font-family:'Public Sans',sans-serif; display:inline-flex; align-items:center; gap:5px;">
                    <svg id="ico-sun" style="width:14px; height:14px; display:none;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path stroke-linecap="round" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                    <svg id="ico-moon" style="width:14px; height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z"/></svg>
                    <span id="theme-label">Mode Gelap</span>
                </button>
            </div>

            <p style="text-align:center; font-size:11px; color:#a8aabc; margin-top:24px;">
                &copy; {{ date('Y') }} SELA — Sistem Kolaborasi Mahasiswa PENS
            </p>
        </div>
    </div>

    <script>
        function updateUI() {
            var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            document.getElementById('ico-sun').style.display = isDark ? 'block' : 'none';
            document.getElementById('ico-moon').style.display = isDark ? 'none' : 'block';
            document.getElementById('theme-label').innerText = isDark ? 'Mode Terang' : 'Mode Gelap';
        }
        updateUI();

        function toggleTheme() {
            var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            if (isDark) {
                document.documentElement.removeAttribute('data-theme');
                localStorage.setItem('admin-theme', 'light');
            } else {
                document.documentElement.setAttribute('data-theme', 'dark');
                localStorage.setItem('admin-theme', 'dark');
            }
            updateUI();
        }
    </script>
</body>
</html>
