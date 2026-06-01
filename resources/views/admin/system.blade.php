@extends('layouts.admin_layout')
@section('page_title', 'Sistem & Pemeliharaan')
@section('title', 'Sistem & Pemeliharaan | SELA')

@section('content')

{{-- Header --}}
<div class="mb-8 flex items-center gap-3">
    <div class="p-3 bg-amber-100 text-amber-700 rounded-xl">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
    </div>
    <div>
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Kontrol Sistem</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400">Atur mode perbaikan dan pantau visibilitas aplikasi</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    {{-- Maintenance Control --}}
    <div class="card">
        <h5 class="text-lg font-semibold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
            <svg class="w-5 h-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            Mode Pemeliharaan
        </h5>
        <p class="text-sm text-slate-600 dark:text-slate-300 mb-6">
            @if($isMaintenance)
                Aplikasi saat ini <span class="font-semibold text-rose-600">DALAM PERBAIKAN</span>. Request API pengguna reguler ditolak (503).
            @else
                Aplikasi berjalan <span class="font-semibold text-emerald-600">NORMAL</span>.
            @endif
        </p>

        <form action="{{ route('admin.system.toggle-maintenance') }}" method="POST">
            @csrf
            @if($isMaintenance)
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg font-semibold hover:bg-emerald-700">Matikan Mode Perbaikan</button>
            @else
                <button type="submit" onclick="return confirm('Yakin ingin menyalakan Mode Pemeliharaan?')" class="px-4 py-2 bg-rose-600 text-white rounded-lg font-semibold hover:bg-rose-700">Nyalakan Mode Perbaikan</button>
            @endif
        </form>
    </div>

    {{-- System Status --}}
    <div class="card">
        <h5 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Informasi Sistem</h5>
        <div class="space-y-3">
            <div class="flex justify-between text-sm">
                <span class="text-slate-500">Framework</span>
                <span class="font-mono text-slate-900 dark:text-white">Laravel 12.x</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-slate-500">PHP Version</span>
                <span class="font-mono text-slate-900 dark:text-white">{{ phpversion() }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-slate-500">Environment</span>
                <span class="font-mono text-slate-900 dark:text-white uppercase">{{ env('APP_ENV', 'production') }}</span>
            </div>
        </div>
    </div>
</div>

@endsection
