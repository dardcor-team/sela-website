@extends('layouts.admin_layout')
@section('page_title', 'Sistem & Pemeliharaan')
@section('title', 'Sistem & Pemeliharaan | SELA')

@section('content')

{{-- Header Banner --}}
<div class="mb-8 p-6 bg-[#FDE047] dark:bg-[#ca8a04] border-4 border-black dark:border-white shadow-[4px_4px_0_#000] dark:shadow-[4px_4px_0_#fff] transition-colors rounded-xl">
    <h4 class="text-2xl md:text-3xl font-mono font-black text-black dark:text-white uppercase tracking-wider m-0">KONTROL SISTEM</h4>
    <p class="text-xs md:text-sm font-bold text-black dark:text-gray-200 uppercase tracking-wide mt-2">Atur mode perbaikan dan visibilitas aplikasi</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    {{-- Maintenance Control --}}
    <div class="bg-white dark:bg-black border-4 border-black dark:border-white shadow-[6px_6px_0_#000] dark:shadow-[6px_6px_0_#fff] p-8 rounded-xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-[#FF6B6B] border-l-4 border-b-4 border-black dark:border-white rounded-bl-full flex items-start justify-end p-4">
            <svg class="w-8 h-8 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>

        <h5 class="text-2xl font-mono font-black text-black dark:text-white mb-2 uppercase">Mode Pemeliharaan</h5>
        <div class="text-sm font-bold text-gray-600 dark:text-gray-400 mb-8 max-w-[80%]">
            @if($isMaintenance)
                Aplikasi sedang <strong class="text-[#F43F5E]">DALAM PERBAIKAN</strong>. Seluruh request API untuk pengguna reguler akan ditolak dengan status 503. Super Admin tetap bisa mengakses sistem.
            @else
                Aplikasi berjalan <strong class="text-[#A3E635]">NORMAL</strong>. Mode pemeliharaan sedang dimatikan.
            @endif
        </div>

        <form action="{{ route('admin.system.toggle-maintenance') }}" method="POST">
            @csrf
            @if($isMaintenance)
                <button type="submit" class="w-full py-4 bg-[#A3E635] text-black border-4 border-black dark:border-white font-mono font-black uppercase text-base shadow-[4px_4px_0_#000] dark:shadow-[4px_4px_0_#fff] hover:-translate-y-1 hover:-translate-x-1 hover:shadow-[6px_6px_0_#000] dark:hover:shadow-[6px_6px_0_#fff] transition-all rounded-xl">
                    Matikan Mode Perbaikan
                </button>
            @else
                <button type="submit" onclick="return confirm('Yakin ingin menyalakan Mode Pemeliharaan? Semua pengguna akan mendapat 503 Service Unavailable.')" class="w-full py-4 bg-[#F43F5E] text-white border-4 border-black dark:border-white font-mono font-black uppercase text-base shadow-[4px_4px_0_#000] dark:shadow-[4px_4px_0_#fff] hover:-translate-y-1 hover:-translate-x-1 hover:shadow-[6px_6px_0_#000] dark:hover:shadow-[6px_6px_0_#fff] transition-all rounded-xl">
                    Nyalakan Mode Perbaikan
                </button>
            @endif
        </form>
    </div>

    {{-- System Status --}}
    <div class="bg-[#06b6d4] border-4 border-black dark:border-white shadow-[6px_6px_0_#000] dark:shadow-[6px_6px_0_#fff] p-8 rounded-xl flex flex-col justify-center items-center text-center">
        <svg class="w-16 h-16 text-black dark:text-white mb-4 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
        </svg>
        <h5 class="text-xl font-mono font-black text-black dark:text-white uppercase mb-2">Informasi Sistem</h5>
        <div class="inline-flex items-center gap-2 bg-black text-[#A3E635] px-4 py-2 border-2 border-black dark:border-white font-mono font-black text-sm uppercase rounded-lg shadow-[2px_2px_0_#000] dark:shadow-[2px_2px_0_#fff]">
            <span class="w-3 h-3 bg-[#A3E635] rounded-full animate-ping"></span>
            Online & Aktif
        </div>
        <div class="mt-6 text-xs font-bold text-black dark:text-white bg-white/20 p-4 rounded-xl border-2 border-black dark:border-white text-left w-full">
            <div class="flex justify-between mb-2"><span>Framework</span> <span class="font-mono">Laravel 12.x</span></div>
            <div class="flex justify-between mb-2"><span>PHP Version</span> <span class="font-mono">{{ phpversion() }}</span></div>
            <div class="flex justify-between"><span>Environment</span> <span class="font-mono uppercase">{{ env('APP_ENV', 'production') }}</span></div>
        </div>
    </div>
</div>

@endsection
