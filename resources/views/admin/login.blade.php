<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Super Admin | SELA</title>
    
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
                        mono: ['Rubik Mono One', 'monospace'],
                    },
                    colors: {
                        cyan: '#06b6d4',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #fde047; /* Bright yellow */
            background-image: radial-gradient(#000 1px, transparent 1px);
            background-size: 20px 20px;
        }
        .dark body {
            background-color: #111;
            background-image: radial-gradient(#333 1px, transparent 1px);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <!-- Back to Web -->
    <a href="{{ url('/') }}" class="absolute top-6 left-6 flex items-center gap-2 font-black uppercase text-sm border-3 border-black dark:border-white px-4 py-2 bg-white dark:bg-black text-black dark:text-white shadow-[4px_4px_0_#000] dark:shadow-[4px_4px_0_#fff] hover:-translate-y-1 hover:-translate-x-1 hover:shadow-[6px_6px_0_#000] dark:hover:shadow-[6px_6px_0_#fff] transition-all rounded-lg font-mono">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        BERANDA
    </a>

    <!-- Theme toggle -->
    <button onclick="document.documentElement.classList.toggle('dark')" class="absolute top-6 right-6 flex items-center gap-2 font-black uppercase text-sm border-3 border-black dark:border-white p-3 bg-white dark:bg-black text-black dark:text-white shadow-[4px_4px_0_#000] dark:shadow-[4px_4px_0_#fff] hover:-translate-y-1 hover:-translate-x-1 hover:shadow-[6px_6px_0_#000] dark:hover:shadow-[6px_6px_0_#fff] transition-all rounded-lg">
        <svg class="w-5 h-5 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z"/>
        </svg>
        <svg class="w-5 h-5 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
            <circle cx="12" cy="12" r="5"/><path stroke-linecap="round" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
        </svg>
    </button>

    <div class="w-full max-w-md bg-white dark:bg-[#1a1b26] border-4 border-black dark:border-white shadow-[12px_12px_0_#000] dark:shadow-[12px_12px_0_#fff] p-8 md:p-10 rounded-2xl relative">
        
        <!-- Decoration -->
        <div class="absolute -top-6 -right-6 w-14 h-14 bg-[#a3e635] border-4 border-black dark:border-white rounded-full shadow-[4px_4px_0_#000] dark:shadow-[4px_4px_0_#fff] animate-bounce"></div>

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-[#06b6d4] border-4 border-black dark:border-white shadow-[4px_4px_0_#000] dark:shadow-[4px_4px_0_#fff] text-white rounded-xl mb-4 font-mono text-3xl font-black">S</div>
            <h1 class="text-3xl font-mono font-black uppercase text-black dark:text-white tracking-widest mt-2">SELA ADMIN</h1>
            <p class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mt-2">Masuk ke pusat kendali</p>
        </div>

        @if(session('error'))
            <div class="bg-[#f43f5e] border-4 border-black p-4 mb-6 rounded-xl shadow-[4px_4px_0_#000] text-white font-black text-sm uppercase text-center font-mono">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.login') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block font-mono font-black text-xs uppercase tracking-widest text-black dark:text-white mb-2">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full bg-[#f8fafc] dark:bg-black border-4 border-black dark:border-white px-5 py-4 text-black dark:text-white font-bold rounded-xl shadow-[4px_4px_0_#000] dark:shadow-[4px_4px_0_#fff] focus:shadow-[6px_6px_0_#000] dark:focus:shadow-[6px_6px_0_#fff] focus:-translate-y-1 focus:-translate-x-1 outline-none transition-all placeholder:text-gray-400">
            </div>

            <div>
                <label class="block font-mono font-black text-xs uppercase tracking-widest text-black dark:text-white mb-2">Password</label>
                <input type="password" name="password" required
                    class="w-full bg-[#f8fafc] dark:bg-black border-4 border-black dark:border-white px-5 py-4 text-black dark:text-white font-bold rounded-xl shadow-[4px_4px_0_#000] dark:shadow-[4px_4px_0_#fff] focus:shadow-[6px_6px_0_#000] dark:focus:shadow-[6px_6px_0_#fff] focus:-translate-y-1 focus:-translate-x-1 outline-none transition-all placeholder:text-gray-400">
            </div>

            <button type="submit" class="w-full bg-[#A3E635] text-black border-4 border-black dark:border-white px-5 py-4 font-mono font-black uppercase tracking-widest text-lg rounded-xl shadow-[6px_6px_0_#000] dark:shadow-[6px_6px_0_#fff] hover:shadow-[8px_8px_0_#000] dark:hover:shadow-[8px_8px_0_#fff] hover:-translate-y-1 hover:-translate-x-1 transition-all mt-4">
                LOGIN SEKARANG
            </button>
        </form>
    </div>

</body>
</html>
