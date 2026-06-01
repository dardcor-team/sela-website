@extends('layouts.admin_layout')
@section('title', 'Dashboard Overview | SELA')

@section('content')

{{-- Page Header --}}
<div class="mb-8">
    <h4 class="text-3xl md:text-4xl font-mono font-black text-black dark:text-white uppercase tracking-wider">DASHBOARD</h4>
    <p class="text-xs md:text-sm font-bold text-black dark:text-gray-200 mt-1 uppercase tracking-wide">Pantau statistik dan aktivitas sistem SELA</p>
</div>

{{-- Welcome Banner + Server Status --}}
<div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-6 mb-8">
    {{-- Welcome Card --}}
    <div class="bg-[#a855f7] border-4 border-black dark:border-white shadow-[6px_6px_0_#000] dark:shadow-[6px_6px_0_#fff] p-8 text-black relative overflow-hidden flex flex-col md:flex-row md:items-center md:justify-between min-h-[160px] rounded-xl">
        <div class="relative z-10">
            <h5 class="text-xl md:text-2xl font-mono font-black text-black mb-3 uppercase tracking-wide">Selamat Datang, Admin!</h5>
            <p class="text-sm font-bold text-black mb-6 max-w-md leading-relaxed">
                Sistem SELA berjalan normal. <span class="bg-black text-white px-3 py-1 inline-block border-2 border-black font-black font-mono shadow-[2px_2px_0_#fff]">{{ $completedSubtasks }}</span> sub-tugas telah diselesaikan.
            </p>
            <a href="{{ route('admin.groups') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[#FFE600] text-black border-3 border-black font-black uppercase text-xs tracking-wider shadow-[4px_4px_0_#000] hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[6px_6px_0_#000] transition-all rounded-lg font-mono">
                Pantau Kelompok
            </a>
        </div>
        {{-- Decorative Neo-Brutalist shapes --}}
        <div class="absolute right-[-40px] bottom-[-40px] w-48 h-48 border-4 border-black bg-white opacity-20 rotate-12 transform pointer-events-none hidden md:block"></div>
        <div class="absolute right-[40px] top-[-20px] w-24 h-24 border-4 border-black bg-[#38BDF8] opacity-35 -rotate-45 transform pointer-events-none hidden md:block"></div>
    </div>

    {{-- Server Status Card --}}
    <div class="bg-[#22C55E] border-4 border-black dark:border-white shadow-[6px_6px_0_#000] dark:shadow-[6px_6px_0_#fff] p-6 text-black flex flex-col justify-between rounded-xl">
        <div>
            <div class="flex items-center justify-between mb-4 pb-3 border-b-3 border-black">
                <span class="text-xs font-mono font-black uppercase tracking-wider text-black">Status Server</span>
                <span class="inline-flex items-center gap-1.5 text-[10px] bg-black text-[#A3E635] px-3 py-1.5 border-2 border-black font-black uppercase shadow-[2px_2px_0_#000] rounded-lg font-mono">
                    <span class="w-2.5 h-2.5 bg-[#A3E635] border border-black rounded-full inline-block animate-pulse"></span>
                    Online
                </span>
            </div>
            <div class="space-y-4">
                <div>
                    <div class="text-[10px] font-black uppercase tracking-wider text-black/70 mb-2 font-mono">Mode Pemeliharaan</div>
                    @if(Cache::get('app_maintenance', false))
                        <span class="inline-block bg-[#F472B6] text-black border-2 border-black font-black text-[10px] uppercase px-3 py-1 shadow-[2px_2px_0_#000] rounded-md font-mono">AKTIF - Akses Dikunci</span>
                    @else
                        <span class="inline-block bg-[#A3E635] text-black border-2 border-black font-black text-[10px] uppercase px-3 py-1 shadow-[2px_2px_0_#000] rounded-md font-mono">TIDAK AKTIF - Normal</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t-3 border-black text-right">
            <a href="{{ route('admin.system') }}" class="text-xs font-black uppercase text-black hover:text-white transition-colors flex items-center justify-end gap-1 font-mono">
                Atur Sistem
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </div>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-[#38BDF8] border-4 border-black dark:border-white p-6 shadow-[6px_6px_0_#000] dark:shadow-[6px_6px_0_#fff] rounded-xl hover:-translate-y-1 hover:shadow-[8px_8px_0_#000] dark:hover:shadow-[8px_8px_0_#fff] transition-all">
        <div class="flex justify-between items-start mb-4">
            <div class="text-xs font-mono font-black text-black uppercase tracking-wider">Total Pengguna</div>
            <div class="p-2 bg-white border-2 border-black rounded-lg shadow-[2px_2px_0_#000]">
                <svg class="w-5 h-5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>
        <h3 class="text-4xl font-mono font-black text-black mb-1">{{ $usersCount }}</h3>
        <p class="text-[10px] font-bold text-black/70 uppercase">Akun terdaftar</p>
    </div>

    <div class="bg-[#FDE047] border-4 border-black dark:border-white p-6 shadow-[6px_6px_0_#000] dark:shadow-[6px_6px_0_#fff] rounded-xl hover:-translate-y-1 hover:shadow-[8px_8px_0_#000] dark:hover:shadow-[8px_8px_0_#fff] transition-all">
        <div class="flex justify-between items-start mb-4">
            <div class="text-xs font-mono font-black text-black uppercase tracking-wider">Total Kelompok</div>
            <div class="p-2 bg-white border-2 border-black rounded-lg shadow-[2px_2px_0_#000]">
                <svg class="w-5 h-5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
        </div>
        <h3 class="text-4xl font-mono font-black text-black mb-1">{{ $groupsCount }}</h3>
        <p class="text-[10px] font-bold text-black/70 uppercase">Kelompok aktif</p>
    </div>

    <div class="bg-[#FDA4AF] border-4 border-black dark:border-white p-6 shadow-[6px_6px_0_#000] dark:shadow-[6px_6px_0_#fff] rounded-xl hover:-translate-y-1 hover:shadow-[8px_8px_0_#000] dark:hover:shadow-[8px_8px_0_#fff] transition-all">
        <div class="flex justify-between items-start mb-4">
            <div class="text-xs font-mono font-black text-black uppercase tracking-wider">Total Tugas</div>
            <div class="p-2 bg-white border-2 border-black rounded-lg shadow-[2px_2px_0_#000]">
                <svg class="w-5 h-5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
        </div>
        <h3 class="text-4xl font-mono font-black text-black mb-1">{{ $tasksCount }}</h3>
        <p class="text-[10px] font-bold text-black/70 uppercase">Independen & Grup</p>
    </div>

    <div class="bg-white border-4 border-black dark:border-white p-6 shadow-[6px_6px_0_#000] dark:shadow-[6px_6px_0_#fff] rounded-xl hover:-translate-y-1 hover:shadow-[8px_8px_0_#000] dark:hover:shadow-[8px_8px_0_#fff] transition-all dark:bg-black">
        <div class="flex justify-between items-start mb-4">
            <div class="text-xs font-mono font-black text-black dark:text-white uppercase tracking-wider">Mata Kuliah</div>
            <div class="p-2 bg-[#A3E635] border-2 border-black dark:border-white rounded-lg shadow-[2px_2px_0_#000] dark:shadow-[2px_2px_0_#fff]">
                <svg class="w-5 h-5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
        </div>
        <h3 class="text-4xl font-mono font-black text-black dark:text-white mb-1">{{ $coursesCount }}</h3>
        <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase">Terdaftar di sistem</p>
    </div>
</div>

@endsection
