@extends('layouts.admin_layout')
@section('title', 'Dashboard Overview | SELA')

@section('content')

{{-- Page Header --}}
<div style="margin-bottom:24px;">
    <h4 style="font-size:20px; font-weight:600; color:#566a7f; margin:0 0 4px;" class="dark:text-gray-200">Dashboard</h4>
    <p style="font-size:13px; color:#a8aabc; margin:0;">Pantau statistik dan aktivitas sistem SELA</p>
</div>

{{-- Welcome Banner + Server Status --}}
<div style="display:grid; grid-template-columns:1fr 320px; gap:20px; margin-bottom:20px;" class="grid-welcome">
    {{-- Welcome Card --}}
    <div class="card" style="padding:28px 32px; background:linear-gradient(135deg, #696cff 0%, #9155fd 100%); border:none; position:relative; overflow:hidden; display:flex; align-items:center; justify-content:space-between; min-height:140px;">
        <div style="position:relative; z-index:2;">
            <h5 style="font-size:20px; font-weight:700; color:#fff; margin:0 0 8px;">Selamat Datang, Admin! 🎉</h5>
            <p style="font-size:13px; color:rgba(255,255,255,0.8); margin:0 0 20px; max-width:340px; line-height:1.6;">
                Sistem SELA berjalan normal. <strong style="color:#fff;">{{ $completedSubtasks }}</strong> sub-tugas telah diselesaikan mahasiswa secara kolaboratif.
            </p>
            <a href="{{ route('admin.groups') }}" style="display:inline-block; padding:9px 22px; background:#fff; color:#696cff; border-radius:8px; font-size:13px; font-weight:700; text-decoration:none; transition:opacity 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                Pantau Kelompok →
            </a>
        </div>
        {{-- Decorative Circle --}}
        <div style="position:absolute; right:-30px; top:-30px; width:220px; height:220px; background:rgba(255,255,255,0.06); border-radius:50%;"></div>
        <div style="position:absolute; right:30px; top:30px; width:140px; height:140px; background:rgba(255,255,255,0.06); border-radius:50%;"></div>
    </div>

    {{-- Server Status Card --}}
    <div class="card" style="padding:24px;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
            <span style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#a8aabc;">Status Server</span>
            <span style="display:inline-flex; align-items:center; gap:5px; font-size:11px; color:#28c76f; font-weight:600;">
                <span style="width:7px; height:7px; background:#28c76f; border-radius:50%; display:inline-block; animation:pulse 2s infinite;"></span>
                Online
            </span>
        </div>
        <div style="space-y:12px;">
            <div style="margin-bottom:12px;">
                <div style="font-size:11px; color:#a8aabc; margin-bottom:4px; font-weight:500;">Mode Pemeliharaan</div>
                @if(Cache::get('app_maintenance', false))
                    <span class="badge badge-warning">AKTIF — Akses Dikunci</span>
                @else
                    <span class="badge badge-success">TIDAK AKTIF — Normal</span>
                @endif
            </div>
            <div style="margin-bottom:12px;">
                <div style="font-size:11px; color:#a8aabc; margin-bottom:4px; font-weight:500;">Database</div>
                <span class="badge badge-primary" style="font-family:monospace; text-transform:uppercase;">{{ DB::connection()->getDriverName() }}</span>
            </div>
        </div>
        <a href="{{ route('admin.system') }}" style="display:flex; align-items:center; gap:4px; font-size:12px; font-weight:600; color:#696cff; text-decoration:none; margin-top:16px; border-top:1px solid #f0f0f0; padding-top:14px;" class="dark-border">
            Kelola Sistem
            <svg style="width:14px; height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
</div>

{{-- Stats Cards Row --}}
<div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:20px; margin-bottom:20px;" class="stats-grid">

    {{-- Card: Total Users --}}
    <div class="card" style="padding:20px 24px;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
            <div style="width:42px; height:42px; border-radius:8px; background:rgba(105,108,255,0.12); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg style="width:20px; height:20px; color:#696cff;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                </svg>
            </div>
            <svg style="width:16px; height:16px; color:#a8aabc; cursor:pointer;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="5" r="1" fill="currentColor"/><circle cx="12" cy="12" r="1" fill="currentColor"/><circle cx="12" cy="19" r="1" fill="currentColor"/></svg>
        </div>
        <div style="font-size:11px; color:#a8aabc; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:6px;">Total Pengguna</div>
        <div style="font-size:26px; font-weight:700; color:#566a7f; margin-bottom:6px;" class="dark:text-gray-200">{{ $totalUsers }}</div>
        <div style="font-size:12px; color:#a8aabc;">
            <span style="color:#696cff; font-weight:600;">{{ $totalStudents }}</span> Mhs &nbsp;·&nbsp; <span style="font-weight:600;">{{ $totalLecturers }}</span> Dosen
        </div>
    </div>

    {{-- Card: Kelompok --}}
    <div class="card" style="padding:20px 24px;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
            <div style="width:42px; height:42px; border-radius:8px; background:rgba(145,85,253,0.12); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg style="width:20px; height:20px; color:#9155fd;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 11h8M8 15h5"/>
                </svg>
            </div>
            <svg style="width:16px; height:16px; color:#a8aabc; cursor:pointer;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="5" r="1" fill="currentColor"/><circle cx="12" cy="12" r="1" fill="currentColor"/><circle cx="12" cy="19" r="1" fill="currentColor"/></svg>
        </div>
        <div style="font-size:11px; color:#a8aabc; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:6px;">Kelompok Aktif</div>
        <div style="font-size:26px; font-weight:700; color:#566a7f; margin-bottom:6px;" class="dark:text-gray-200">{{ $totalGroups }}</div>
        <div style="font-size:12px; color:#28c76f; font-weight:600; display:flex; align-items:center; gap:3px;">
            <svg style="width:12px; height:12px;" fill="none" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L10 10.586 13.586 7H12z" clip-rule="evenodd" fill="#28c76f"/></svg>
            Aktif Kolaborasi
        </div>
    </div>

    {{-- Card: Tugas Selesai --}}
    <div class="card" style="padding:20px 24px;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
            <div style="width:42px; height:42px; border-radius:8px; background:rgba(40,199,111,0.12); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg style="width:20px; height:20px; color:#28c76f;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <svg style="width:16px; height:16px; color:#a8aabc; cursor:pointer;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="5" r="1" fill="currentColor"/><circle cx="12" cy="12" r="1" fill="currentColor"/><circle cx="12" cy="19" r="1" fill="currentColor"/></svg>
        </div>
        <div style="font-size:11px; color:#a8aabc; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:6px;">Tugas Selesai</div>
        <div style="font-size:26px; font-weight:700; color:#566a7f; margin-bottom:6px;" class="dark:text-gray-200">
            {{ $taskStatus['Done'] ?? 0 }} <span style="font-size:14px; font-weight:400; color:#a8aabc;">/ {{ $totalTasks }}</span>
        </div>
        <div style="font-size:12px; color:#a8aabc;">{{ $taskStatus['In Progress'] ?? 0 }} sedang berjalan</div>
    </div>

    {{-- Card: Subtugas --}}
    <div class="card" style="padding:20px 24px;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
            <div style="width:42px; height:42px; border-radius:8px; background:rgba(255,159,67,0.12); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg style="width:20px; height:20px; color:#ff9f43;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <svg style="width:16px; height:16px; color:#a8aabc; cursor:pointer;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="5" r="1" fill="currentColor"/><circle cx="12" cy="12" r="1" fill="currentColor"/><circle cx="12" cy="19" r="1" fill="currentColor"/></svg>
        </div>
        <div style="font-size:11px; color:#a8aabc; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:6px;">Subtugas Selesai</div>
        <div style="font-size:26px; font-weight:700; color:#566a7f; margin-bottom:6px;" class="dark:text-gray-200">{{ $subtaskCompletionRate }}%</div>
        <div style="font-size:12px; color:#a8aabc;">{{ $completedSubtasks }} dari {{ $totalSubtasks }}</div>
    </div>

</div>

{{-- Charts Row --}}
<div style="display:grid; grid-template-columns:3fr 2fr; gap:20px; margin-bottom:20px;" class="charts-grid">
    {{-- Task Status --}}
    <div class="card" style="padding:24px;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
            <h6 style="font-size:14px; font-weight:600; color:#566a7f; margin:0;" class="dark:text-gray-200">Status Distribusi Tugas</h6>
            <span style="font-size:11px; color:#a8aabc;">Doughnut Chart</span>
        </div>
        <div style="position:relative; height:240px;">
            <canvas id="taskStatusChart"></canvas>
        </div>
    </div>

    {{-- User Roles --}}
    <div class="card" style="padding:24px;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
            <h6 style="font-size:14px; font-weight:600; color:#566a7f; margin:0;" class="dark:text-gray-200">Peran Pengguna</h6>
            <span style="font-size:11px; color:#a8aabc;">Bar Chart</span>
        </div>
        <div style="position:relative; height:240px;">
            <canvas id="userRolesChart"></canvas>
        </div>
    </div>
</div>

{{-- Bottom Lists --}}
<div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:20px;" class="lists-grid">

    {{-- Course Distribution --}}
    <div class="card" style="padding:24px;">
        <h6 style="font-size:14px; font-weight:600; color:#566a7f; margin:0 0 16px; padding-bottom:14px; border-bottom:1px solid #f0f0f0;" class="dark:text-gray-200 dark-border">Distribusi Mata Kuliah</h6>
        <div style="display:flex; flex-direction:column; gap:10px; max-height:240px; overflow-y:auto;">
            @forelse($courseDistribution as $course)
            <div style="display:flex; align-items:center; justify-content:space-between;">
                <span style="font-size:13px; color:#566a7f; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; flex:1; padding-right:8px;" class="dark:text-gray-300">{{ $course->name ?? 'Mata Kuliah Umum' }}</span>
                <span class="badge badge-primary">{{ $course->count }} Kel</span>
            </div>
            @empty
            <p style="font-size:13px; color:#a8aabc; text-align:center; padding:20px 0;">Belum ada data.</p>
            @endforelse
        </div>
    </div>

    {{-- Recent Users --}}
    <div class="card" style="padding:24px;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; padding-bottom:14px; border-bottom:1px solid #f0f0f0;" class="dark-border">
            <h6 style="font-size:14px; font-weight:600; color:#566a7f; margin:0;" class="dark:text-gray-200">Pengguna Terbaru</h6>
            <a href="{{ route('admin.users') }}" style="font-size:12px; color:#696cff; text-decoration:none; font-weight:600;">Lihat Semua</a>
        </div>
        <div style="display:flex; flex-direction:column; gap:14px;">
            @forelse($recentUsers as $user)
            <div style="display:flex; align-items:center; gap:10px;">
                <div style="width:36px; height:36px; border-radius:50%; background:rgba(105,108,255,0.12); color:#696cff; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; flex-shrink:0;">
                    {{ strtoupper(substr($user->username ?? 'U', 0, 2)) }}
                </div>
                <div style="flex:1; overflow:hidden;">
                    <div style="font-size:13px; font-weight:600; color:#566a7f; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" class="dark:text-gray-200">{{ $user->profile?->full_name ?? $user->username }}</div>
                    <div style="font-size:11px; color:#a8aabc; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $user->email }}</div>
                </div>
                <span class="badge {{ $user->role === 'student' ? 'badge-primary' : ($user->role === 'lecturer' ? 'badge-info' : 'badge-danger') }}" style="flex-shrink:0; font-size:10px;">{{ $user->role }}</span>
            </div>
            @empty
            <p style="font-size:13px; color:#a8aabc; text-align:center; padding:20px 0;">Belum ada pengguna.</p>
            @endforelse
        </div>
    </div>

    {{-- Recent Tasks --}}
    <div class="card" style="padding:24px;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; padding-bottom:14px; border-bottom:1px solid #f0f0f0;" class="dark-border">
            <h6 style="font-size:14px; font-weight:600; color:#566a7f; margin:0;" class="dark:text-gray-200">Tugas Terbaru</h6>
            <a href="{{ route('admin.groups') }}" style="font-size:12px; color:#696cff; text-decoration:none; font-weight:600;">Pantau</a>
        </div>
        <div style="display:flex; flex-direction:column; gap:14px;">
            @forelse($recentTasks as $task)
            <div style="display:flex; align-items:center; justify-content:space-between; gap:10px;">
                <div style="flex:1; overflow:hidden;">
                    <div style="font-size:13px; font-weight:600; color:#566a7f; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" class="dark:text-gray-200">{{ $task->title }}</div>
                    <div style="font-size:11px; color:#a8aabc;">
                        <span class="badge {{ $task->status === 'Done' ? 'badge-success' : ($task->status === 'In Progress' ? 'badge-warning' : 'badge-info') }}" style="font-size:10px;">{{ $task->status }}</span>
                    </div>
                </div>
                <div style="font-size:11px; color:#a8aabc; flex-shrink:0; text-align:right;">
                    {{ $task->due_date ? date('d M', strtotime($task->due_date)) : '-' }}
                </div>
            </div>
            @empty
            <p style="font-size:13px; color:#a8aabc; text-align:center; padding:20px 0;">Belum ada tugas.</p>
            @endforelse
        </div>
    </div>

</div>

<style>
@media (max-width: 1024px) {
    .grid-welcome, .charts-grid { grid-template-columns: 1fr !important; }
    .stats-grid { grid-template-columns: repeat(2, 1fr) !important; }
    .lists-grid { grid-template-columns: 1fr !important; }
}
@media (max-width: 640px) {
    .stats-grid { grid-template-columns: 1fr !important; }
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var isDark = document.documentElement.classList.contains('dark');
    var textColor = isDark ? '#a8aabc' : '#697a8d';
    var gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.05)';

    // Chart 1: Doughnut - Task Status
    new Chart(document.getElementById('taskStatusChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['To Do', 'In Progress', 'Done'],
            datasets: [{
                data: [{{ $taskStatus['To Do'] ?? 0 }}, {{ $taskStatus['In Progress'] ?? 0 }}, {{ $taskStatus['Done'] ?? 0 }}],
                backgroundColor: ['rgba(168,170,188,0.7)', 'rgba(255,159,67,0.7)', 'rgba(40,199,111,0.7)'],
                borderColor: ['#a8aabc', '#ff9f43', '#28c76f'],
                borderWidth: 2,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false, cutout: '72%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: textColor, font: { family: 'Public Sans', size: 12 }, padding: 16, usePointStyle: true, pointStyleWidth: 10 }
                }
            }
        }
    });

    // Chart 2: Bar - User Roles
    new Chart(document.getElementById('userRolesChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: ['Mahasiswa', 'Dosen', 'Admin'],
            datasets: [{
                label: 'Jumlah',
                data: [{{ $totalStudents }}, {{ $totalLecturers }}, {{ $totalAdmins }}],
                backgroundColor: ['rgba(105,108,255,0.75)', 'rgba(145,85,253,0.75)', 'rgba(168,170,188,0.75)'],
                borderColor: ['#696cff', '#9155fd', '#a8aabc'],
                borderWidth: 2, borderRadius: 6
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: gridColor }, ticks: { color: textColor, font: { family: 'Public Sans', size: 11 }, stepSize: 1 } },
                x: { grid: { display: false }, ticks: { color: textColor, font: { family: 'Public Sans', size: 11 } } }
            }
        }
    });
});
</script>
@endsection
