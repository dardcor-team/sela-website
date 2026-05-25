@extends('layouts.admin_layout')
@section('title', 'Kelompok & Tugas | SELA')

@section('content')

<div style="margin-bottom:24px;">
    <h4 style="font-size:20px; font-weight:600; color:#566a7f; margin:0 0 4px;" class="dark:text-gray-200">Kelompok & Tugas</h4>
    <p style="font-size:13px; color:#a8aabc; margin:0;">Pantau seluruh kelompok tugas mahasiswa</p>
</div>

{{-- Search --}}
<div class="card" style="padding:20px 24px; margin-bottom:20px;">
    <form action="{{ route('admin.groups') }}" method="GET">
        <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
            <div style="flex:1; min-width:200px;">
                <label style="display:block; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; color:#a8aabc; margin-bottom:6px;">Cari Kelompok</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Nama kelompok atau kode undangan…"
                    style="width:100%; padding:9px 14px; border:1px solid #d9dbe9; border-radius:8px; font-size:13px; color:#566a7f; background:#fff; outline:none; font-family:'Public Sans',sans-serif; box-sizing:border-box;" class="dark-input">
            </div>
            <div style="display:flex; gap:8px;">
                <button type="submit" style="padding:9px 20px; background:#696cff; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; font-family:'Public Sans',sans-serif;" onmouseover="this.style.opacity='0.88'" onmouseout="this.style.opacity='1'">Cari</button>
                <a href="{{ route('admin.groups') }}" style="padding:9px 20px; background:#f5f5f9; color:#697a8d; border:1px solid #d9dbe9; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center;">Reset</a>
            </div>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="card" style="overflow:hidden;">
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:13px;">
            <thead>
                <tr style="background:#f8f8fb; border-bottom:1px solid #e7e7ff;">
                    <th style="padding:12px 24px; text-align:left; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#a8aabc;">Nama Kelompok</th>
                    <th style="padding:12px 16px; text-align:left; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#a8aabc;">Mata Kuliah / Kelas</th>
                    <th style="padding:12px 16px; text-align:left; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#a8aabc;">Kode Undangan</th>
                    <th style="padding:12px 16px; text-align:left; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#a8aabc;">Anggota</th>
                    <th style="padding:12px 24px; text-align:right; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#a8aabc;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($groups as $group)
                <tr style="border-bottom:1px solid #f0f0f0; transition:background 0.15s;" onmouseover="this.style.background='#f8f8fb'" onmouseout="this.style.background='transparent'">
                    <td style="padding:14px 24px;">
                        <div style="font-weight:600; color:#566a7f;" class="dark:text-gray-200">{{ $group->name }}</div>
                        <div style="font-size:11px; color:#a8aabc;">{{ $group->created_at ? $group->created_at->format('d M Y') : '-' }}</div>
                    </td>
                    <td style="padding:14px 16px;">
                        <div style="color:#566a7f; font-weight:500;">{{ $group->course_name ?? '-' }}</div>
                        <div style="font-size:11px; color:#a8aabc;">Kelas: {{ $group->class_name ?? '-' }}</div>
                    </td>
                    <td style="padding:14px 16px;">
                        <span style="font-family:monospace; font-weight:700; color:#696cff; background:rgba(105,108,255,0.1); padding:4px 10px; border-radius:6px; font-size:12px;">{{ $group->invitation_code }}</span>
                    </td>
                    <td style="padding:14px 16px;">
                        <span class="badge badge-info">{{ $group->members_count ?? $group->members()->count() }} anggota</span>
                    </td>
                    <td style="padding:14px 24px; text-align:right;">
                        <a href="{{ route('admin.groups.detail', $group->id) }}"
                            style="padding:6px 14px; background:rgba(105,108,255,0.08); color:#696cff; border:1px solid rgba(105,108,255,0.25); border-radius:6px; font-size:12px; font-weight:600; text-decoration:none; transition:all 0.2s; white-space:nowrap;"
                            onmouseover="this.style.background='#696cff'; this.style.color='#fff';"
                            onmouseout="this.style.background='rgba(105,108,255,0.08)'; this.style.color='#696cff';">
                            Detail & Tugas
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding:40px; text-align:center; color:#a8aabc; font-size:13px;">Belum ada kelompok.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($groups->hasPages())
    <div style="padding:16px 24px; border-top:1px solid #f0f0f0; background:#f8f8fb;">
        {{ $groups->links() }}
    </div>
    @endif
</div>
@endsection
