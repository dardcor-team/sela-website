<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | SELA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] } } }
        }
    </script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-100 dark:bg-slate-900 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-sm bg-white dark:bg-slate-800 p-8 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Admin Login</h1>
            <p class="text-sm text-slate-500">Selamat datang kembali ke pusat kendali SELA</p>
        </div>

        @if(session('error'))
            <div class="bg-rose-50 text-rose-700 p-3 rounded-lg text-sm mb-6">{{ session('error') }}</div>
        @endif

        <form action="{{ route('admin.login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email</label>
                <input type="email" name="email" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-slate-50 dark:bg-slate-700 focus:ring-2 focus:ring-cyan-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Password</label>
                <input type="password" name="password" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-slate-50 dark:bg-slate-700 focus:ring-2 focus:ring-cyan-500 outline-none">
            </div>
            <button type="submit" class="w-full py-2 bg-cyan-700 text-white rounded-lg font-semibold hover:bg-cyan-800 transition-colors">Login</button>
        </form>
    </div>
</body>
</html>