@extends('layouts.admin_layout')
@section('title', 'Sistem & Pemeliharaan | SELA')

@section('content')

<div style="margin-bottom:24px;">
    <h4 style="font-size:20px; font-weight:600; color:#566a7f; margin:0 0 4px;" class="dark:text-gray-200">Sistem & Pemeliharaan</h4>
    <p style="font-size:13px; color:#a8aabc; margin:0;">Konfigurasi server dan pemantauan database</p>
</div>

{{-- Maintenance Toggle --}}
<div class="card" style="padding:24px; margin-bottom:20px;">
    <div style="display:flex; flex-direction:column; gap:16px;">
        @if($maintenanceMode)
        <div style="display:flex; align-items:center; gap:10px; padding:12px 16px; background:rgba(255,159,67,0.08); border:1px solid rgba(255,159,67,0.25); border-radius:8px;">
            <svg style="width:18px; height:18px; color:#ff9f43; flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <span style="font-size:13px; font-weight:600; color:#ff9f43;">Mode Pemeliharaan Aktif — Akses publik sedang dikunci</span>
        </div>
        @endif

        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px;">
            <div>
                <h6 style="font-size:15px; font-weight:600; color:#566a7f; margin:0 0 6px;" class="dark:text-gray-200">Mode Pemeliharaan</h6>
                <p style="font-size:13px; color:#a8aabc; margin:0; max-width:520px; line-height:1.6;">
                    Aktifkan untuk menghentikan akses aplikasi mobile bagi mahasiswa & dosen. Akun admin tetap dapat login ke panel ini.
                </p>
            </div>
            <form action="{{ route('admin.system.toggle-maintenance') }}" method="POST">
                @csrf
                <input type="hidden" name="maintenance" value="{{ $maintenanceMode ? '0' : '1' }}">
                @if($maintenanceMode)
                <button type="submit" style="padding:10px 22px; background:#28c76f; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer; font-family:'Public Sans',sans-serif; transition:opacity 0.2s;" onmouseover="this.style.opacity='0.88'" onmouseout="this.style.opacity='1'">
                    ✓ Nonaktifkan Pemeliharaan
                </button>
                @else
                <button type="submit" style="padding:10px 22px; background:#ff9f43; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer; font-family:'Public Sans',sans-serif; transition:opacity 0.2s;" onmouseover="this.style.opacity='0.88'" onmouseout="this.style.opacity='1'">
                    ⚠ Aktifkan Pemeliharaan
                </button>
                @endif
            </form>
        </div>
    </div>
</div>

{{-- Stats Grid --}}
<div style="display:grid; grid-template-columns:1fr 340px; gap:20px;" class="sys-grid">

    {{-- Table Counts --}}
    <div class="card" style="padding:24px;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
            <h6 style="font-size:14px; font-weight:600; color:#566a7f; margin:0;" class="dark:text-gray-200">Statistik Tabel Database</h6>
            <span style="font-family:monospace; font-size:11px; font-weight:700; color:#696cff; background:rgba(105,108,255,0.1); padding:3px 10px; border-radius:6px; text-transform:uppercase;">{{ $dbDriver }}</span>
        </div>
        <div style="display:grid; grid-template-columns:repeat(2, 1fr); gap:12px;">
            @foreach($tableCounts as $tc)
            <div style="padding:16px; background:#f8f8fb; border:1px solid #e7e7ff; border-radius:10px; display:flex; align-items:center; justify-content:space-between; gap:8px; transition:border-color 0.2s;" onmouseover="this.style.borderColor='#c5c5ff'" onmouseout="this.style.borderColor='#e7e7ff'">
                <div>
                    <div style="font-size:13px; font-weight:600; color:#566a7f;" class="dark:text-gray-200">{{ $tc['label'] }}</div>
                    <div style="font-size:10px; color:#a8aabc; font-family:monospace; margin-top:2px;">{{ $tc['table'] }}</div>
                </div>
                <span style="font-size:20px; font-weight:700; color:#696cff; font-family:monospace;">{{ $tc['count'] }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Server Info --}}
    <div class="card" style="padding:24px;">
        <h6 style="font-size:14px; font-weight:600; color:#566a7f; margin:0 0 20px; padding-bottom:14px; border-bottom:1px solid #f0f0f0;" class="dark:text-gray-200">Informasi Server</h6>
        <div style="display:flex; flex-direction:column; gap:0;">
            <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px solid #f0f0f0;">
                <span style="font-size:13px; color:#a8aabc;">Versi PHP</span>
                <span style="font-size:13px; font-weight:600; color:#566a7f; font-family:monospace;" class="dark:text-gray-200">{{ $phpVersion }}</span>
            </div>
            <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px solid #f0f0f0;">
                <span style="font-size:13px; color:#a8aabc;">Versi Laravel</span>
                <span style="font-size:13px; font-weight:600; color:#566a7f; font-family:monospace;" class="dark:text-gray-200">{{ $laravelVersion }}</span>
            </div>
            <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px solid #f0f0f0;">
                <span style="font-size:13px; color:#a8aabc;">Database Driver</span>
                <span style="font-size:13px; font-weight:600; color:#566a7f; font-family:monospace; text-transform:uppercase;" class="dark:text-gray-200">{{ $dbDriver }}</span>
            </div>
            <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px solid #f0f0f0;">
                <span style="font-size:13px; color:#a8aabc;">Environment</span>
                <span class="badge badge-primary" style="font-family:monospace; text-transform:uppercase;">{{ $appEnv }}</span>
            </div>
            <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px solid #f0f0f0;">
                <span style="font-size:13px; color:#a8aabc;">Total File Diunggah</span>
                <span style="font-size:13px; font-weight:600; color:#566a7f;" class="dark:text-gray-200">{{ $totalFilesCount }} berkas</span>
            </div>
            <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 0;">
                <span style="font-size:13px; color:#a8aabc;">Status Server</span>
                <span style="display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:600; color:#28c76f;">
                    <span style="width:8px; height:8px; background:#28c76f; border-radius:50%; display:inline-block; animation:pulse 2s infinite;"></span>
                    Optimal
                </span>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:0.4;} }
@media (max-width: 1024px) { .sys-grid { grid-template-columns: 1fr !important; } }
</style>
@endsection
