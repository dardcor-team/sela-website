@extends('layouts.admin_layout')
@section('title', 'Detail Kelompok | SELA')

@section('content')

{{-- Back + Header --}}
<div style="margin-bottom:24px;">
    <a href="{{ route('admin.groups') }}" style="display:inline-flex; align-items:center; gap:5px; font-size:12px; color:#a8aabc; text-decoration:none; font-weight:600; margin-bottom:10px; transition:color 0.2s;" onmouseover="this.style.color='#696cff'" onmouseout="this.style.color='#a8aabc'">
        <svg style="width:14px; height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Daftar Kelompok
    </a>
    <div style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:12px;">
        <div>
            <h4 style="font-size:20px; font-weight:700; color:#566a7f; margin:0 0 6px; text-transform:uppercase;" class="dark:text-gray-200">{{ $group->name }}</h4>
            <div style="display:flex; flex-wrap:wrap; gap:6px; align-items:center;">
                <span style="font-size:12px; color:#a8aabc;">Mata Kuliah: <strong style="color:#566a7f;" class="dark:text-gray-300">{{ $group->course_name ?? 'N/A' }}</strong></span>
                <span style="color:#d9dbe9;">•</span>
                <span style="font-size:12px; color:#a8aabc;">Kelas: <strong style="color:#566a7f;" class="dark:text-gray-300">{{ $group->class_name ?? 'N/A' }}</strong></span>
                <span style="color:#d9dbe9;">•</span>
                <span style="font-size:12px; color:#a8aabc;">Pembuat: <strong style="color:#566a7f;" class="dark:text-gray-300">{{ $group->creator?->full_name ?? 'N/A' }}</strong></span>
            </div>
        </div>
        <div style="display:flex; align-items:center; gap:8px;">
            <span style="font-size:12px; color:#a8aabc; font-weight:600;">KODE:</span>
            <span style="font-family:monospace; font-size:13px; font-weight:700; color:#696cff; background:rgba(105,108,255,0.1); padding:6px 14px; border-radius:8px; border:1px solid rgba(105,108,255,0.2);">{{ $group->invitation_code }}</span>
        </div>
    </div>
</div>

{{-- Main Grid --}}
<div style="display:grid; grid-template-columns:280px 1fr; gap:20px; align-items:start;" class="detail-grid">

    {{-- Members Sidebar --}}
    <div class="card" style="padding:24px;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; padding-bottom:14px; border-bottom:1px solid #f0f0f0;">
            <h6 style="font-size:14px; font-weight:600; color:#566a7f; margin:0;" class="dark:text-gray-200">Anggota</h6>
            <span style="background:rgba(105,108,255,0.12); color:#696cff; padding:2px 8px; border-radius:20px; font-size:11px; font-weight:700;">{{ count($members) }}</span>
        </div>
        <div style="display:flex; flex-direction:column; gap:12px; max-height:400px; overflow-y:auto;">
            @forelse($members as $member)
            <div style="display:flex; align-items:center; gap:10px; padding:10px 12px; background:#f8f8fb; border-radius:8px; border:1px solid #f0f0f0;">
                <div style="width:34px; height:34px; border-radius:50%; background:rgba(105,108,255,0.12); color:#696cff; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:700; flex-shrink:0;">
                    {{ strtoupper(substr($member->user?->username ?? 'U', 0, 2)) }}
                </div>
                <div style="flex:1; overflow:hidden;">
                    <div style="font-size:12px; font-weight:600; color:#566a7f; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" class="dark:text-gray-200">{{ $member->user?->profile?->full_name ?? $member->user?->username }}</div>
                    <div style="font-size:10px; color:#a8aabc; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $member->user?->email }}</div>
                </div>
                <span style="font-size:10px; font-weight:600; color:#696cff; background:rgba(105,108,255,0.1); padding:2px 6px; border-radius:4px; flex-shrink:0; text-transform:uppercase; border:1px solid rgba(105,108,255,0.2);">{{ $member->role }}</span>
            </div>
            @empty
            <p style="font-size:13px; color:#a8aabc; text-align:center; padding:20px 0;">Belum ada anggota.</p>
            @endforelse
        </div>
    </div>

    {{-- Tasks Column --}}
    <div>
        <div class="card" style="padding:24px;">
            <h6 style="font-size:14px; font-weight:600; color:#566a7f; margin:0 0 20px; padding-bottom:14px; border-bottom:1px solid #f0f0f0;" class="dark:text-gray-200">Daftar Tugas & Progres</h6>

            @forelse($tasks as $task)
            <div style="border:1px solid #e7e7ff; border-radius:10px; padding:18px; margin-bottom:16px; background:#fafafa; transition:border-color 0.2s;" onmouseover="this.style.borderColor='#c5c5ff'" onmouseout="this.style.borderColor='#e7e7ff'">
                {{-- Task Header --}}
                <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:10px; margin-bottom:14px; flex-wrap:wrap;">
                    <div>
                        <h6 style="font-size:14px; font-weight:700; color:#566a7f; margin:0 0 4px;" class="dark:text-gray-200">{{ $task->title }}</h6>
                        <span style="font-size:11px; color:#a8aabc;">Deadline: {{ $task->due_date ? date('d M Y, H:i', strtotime($task->due_date)) : 'Tanpa Deadline' }}</span>
                    </div>
                    @if($task->status === 'Done')
                        <span class="badge badge-success">✓ Done</span>
                    @elseif($task->status === 'In Progress')
                        <span class="badge badge-warning">⚡ In Progress</span>
                    @else
                        <span class="badge" style="background:#f5f5f9; color:#697a8d;">○ To Do</span>
                    @endif
                </div>

                {{-- Progress Bar --}}
                <div style="margin-bottom:14px;">
                    <div style="display:flex; justify-content:space-between; font-size:11px; color:#a8aabc; margin-bottom:6px;">
                        <span>Penyelesaian Subtugas</span>
                        <span style="font-weight:700; color:#566a7f;">{{ $task->progress_percentage }}%</span>
                    </div>
                    <div style="background:#e7e7ff; border-radius:20px; height:6px; overflow:hidden;">
                        <div style="background:linear-gradient(90deg, #696cff, #9155fd); height:100%; border-radius:20px; width:{{ $task->progress_percentage }}%; transition:width 0.6s ease;"></div>
                    </div>
                </div>

                {{-- Files & Links --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; padding-top:14px; border-top:1px solid #f0f0f0;">
                    <div>
                        <div style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#a8aabc; margin-bottom:8px;">Berkas</div>
                        @forelse($task->files as $file)
                        <div style="display:flex; align-items:center; justify-content:space-between; padding:6px 10px; background:#fff; border:1px solid #e7e7ff; border-radius:6px; margin-bottom:4px;">
                            <span style="font-size:11px; color:#697a8d; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; flex:1; padding-right:8px;">{{ $file->file_name }}</span>
                            <a href="{{ $file->file_path }}" target="_blank" style="font-size:11px; color:#696cff; text-decoration:none; font-weight:600; flex-shrink:0;">Unduh</a>
                        </div>
                        @empty
                        <p style="font-size:11px; color:#a8aabc;">Tidak ada berkas.</p>
                        @endforelse
                    </div>
                    <div>
                        <div style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#a8aabc; margin-bottom:8px;">Tautan</div>
                        @forelse($task->links as $link)
                        <div style="display:flex; align-items:center; justify-content:space-between; padding:6px 10px; background:#fff; border:1px solid #e7e7ff; border-radius:6px; margin-bottom:4px;">
                            <span style="font-size:11px; color:#697a8d; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; flex:1; padding-right:8px;">{{ $link->title ?? $link->url }}</span>
                            <a href="{{ $link->url }}" target="_blank" style="font-size:11px; color:#696cff; text-decoration:none; font-weight:600; flex-shrink:0;">Buka</a>
                        </div>
                        @empty
                        <p style="font-size:11px; color:#a8aabc;">Tidak ada tautan.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            @empty
            <div style="text-align:center; padding:40px; color:#a8aabc; font-size:13px; background:#f8f8fb; border:1px dashed #e7e7ff; border-radius:10px;">
                Belum ada tugas di kelompok ini.
            </div>
            @endforelse
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .detail-grid { grid-template-columns: 1fr !important; }
}
</style>
@endsection
