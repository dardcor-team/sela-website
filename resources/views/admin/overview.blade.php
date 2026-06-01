@extends('layouts.admin_layout')
@section('title', 'Dashboard Overview | SELA')

@section('content')

{{-- Page Header --}}
<div class="mb-8 flex items-center gap-3">
    <div class="p-3 bg-cyan-100 text-cyan-700 rounded-xl">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
    </div>
    <div>
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Dashboard</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400">Selamat datang kembali, Admin.</p>
    </div>
</div>

{{-- Welcome Banner + Server Status --}}
<div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-6 mb-8">
    {{-- Welcome Card --}}
    <div class="card bg-cyan-700 text-white p-8 flex flex-col md:flex-row md:items-center md:justify-between min-h-[160px]">
        <div class="relative z-10">
            <h5 class="text-2xl font-bold mb-2">Pantau Progress Tim</h5>
            <p class="text-white/80 mb-6 max-w-md">
                Sistem SELA berjalan normal. <span class="bg-cyan-900 px-2 py-0.5 rounded font-semibold">{{ $completedSubtasks }}</span> sub-tugas telah diselesaikan.
            </p>
            <a href="{{ route('admin.groups') }}" class="inline-flex items-center gap-2 bg-white text-cyan-700 px-4 py-2 rounded-lg font-semibold hover:bg-slate-100 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Kelola Kelompok
            </a>
        </div>
    </div>

    {{-- Server Status Card --}}
    <div class="card flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-200 dark:border-slate-700">
                <span class="text-sm font-bold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                    Status Server
                </span>
                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 bg-emerald-100 px-2 py-1 rounded-full">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                    Online
                </span>
            </div>
            <div class="space-y-4">
                <div>
                    <div class="text-xs font-bold text-slate-500 mb-1">Mode Pemeliharaan</div>
                    @if(Cache::get('app_maintenance', false))
                        <span class="text-xs font-semibold text-rose-700 bg-rose-50 px-2 py-1 rounded">AKTIF</span>
                    @else
                        <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2 py-1 rounded">NORMAL</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-700 text-right">
            <a href="{{ route('admin.system') }}" class="text-sm font-bold text-cyan-600 hover:text-cyan-700 font-semibold">
                Atur Sistem →
            </a>
        </div>
    </div>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="card flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="p-3 bg-sky-100 text-sky-700 rounded-lg">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        </div>
        <div>
            <div class="text-xs font-semibold text-slate-500">Total Pengguna</div>
            <h3 class="text-xl font-extrabold text-slate-900 dark:text-white">{{ $usersCount }}</h3>
        </div>
    </div>
    <div class="card flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="p-3 bg-amber-100 text-amber-700 rounded-lg">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        <div>
            <div class="text-xs font-semibold text-slate-500">Total Kelompok</div>
            <h3 class="text-xl font-extrabold text-slate-900 dark:text-white">{{ $groupsCount }}</h3>
        </div>
    </div>
    <div class="card flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="p-3 bg-rose-100 text-rose-700 rounded-lg">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
        </div>
        <div>
            <div class="text-xs font-semibold text-slate-500">Total Tugas</div>
            <h3 class="text-xl font-extrabold text-slate-900 dark:text-white">{{ $tasksCount }}</h3>
        </div>
    </div>
    <div class="card flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="p-3 bg-lime-100 text-lime-700 rounded-lg">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <div>
            <div class="text-xs font-semibold text-slate-500">Mata Kuliah</div>
            <h3 class="text-xl font-extrabold text-slate-900 dark:text-white">{{ $coursesCount }}</h3>
        </div>
    </div>
</div>
@endsection
