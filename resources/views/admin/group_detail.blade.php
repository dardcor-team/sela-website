@extends('layouts.admin_layout')
@section('page_title', 'Detail Kelompok')
@section('title', 'Detail Kelompok | SELA')

@section('content')

{{-- Back Button --}}
<a href="{{ route('admin.groups') }}" class="inline-flex items-center gap-2 mb-6 text-sm text-slate-500 hover:text-cyan-700 dark:text-slate-400 dark:hover:text-cyan-400">
    ← Kembali ke Daftar Kelompok
</a>

{{-- Header --}}
<div class="mb-8">
    <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">{{ $group->name }}</h2>
    
    <div class="card">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <div class="text-xs font-semibold text-slate-500 uppercase mb-1">Kelas</div>
                <div class="font-bold text-slate-900 dark:text-white">{{ $group->class_name ?? '-' }}</div>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-500 uppercase mb-1">Matkul</div>
                <div class="font-bold text-slate-900 dark:text-white">{{ $group->course_name ?? '-' }}</div>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-500 uppercase mb-1">Kode Akses</div>
                <div class="font-mono font-bold text-cyan-700 dark:text-cyan-400 text-lg">{{ $group->invitation_code }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Anggota List --}}
<div class="card">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center gap-2">
            <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Anggota Kelompok
        </h3>
        <span class="px-3 py-1 bg-cyan-100 text-cyan-700 rounded-full text-xs font-bold">{{ $group->members->count() }} Anggota</span>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800 text-slate-500">
                <tr>
                    <th class="p-4 font-semibold">Mahasiswa</th>
                    <th class="p-4 font-semibold text-center">Status</th>
                    <th class="p-4 font-semibold text-center">Bergabung</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($group->members as $member)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800">
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-cyan-100 text-cyan-700 flex items-center justify-center font-bold">
                                {{ strtoupper(substr($member->user->profile?->full_name ?? $member->user->username ?? 'U', 0, 1)) }}
                            </div>
                            <div class="font-medium text-slate-900 dark:text-white">{{ $member->user->profile?->full_name ?? $member->user->username }}</div>
                        </div>
                    </td>
                    <td class="p-4 text-center">
                        <span class="badge {{ $member->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                            {{ ucfirst($member->status ?? 'Tidak Diketahui') }}
                        </span>
                    </td>
                    <td class="p-4 text-center text-slate-600 dark:text-slate-300">
                        {{ $member->joined_at ? $member->joined_at->format('d M Y') : '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="p-4 text-center text-slate-500">Belum ada anggota di kelompok ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Tasks List --}}
<div class="card mt-8">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center gap-2">
            <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            Tugas Kelompok
        </h3>
        <span class="px-3 py-1 bg-cyan-100 text-cyan-700 rounded-full text-xs font-bold">{{ $tasks->count() }} Tugas</span>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800 text-slate-500">
                <tr>
                    <th class="p-4 font-semibold">Tugas</th>
                    <th class="p-4 font-semibold text-center">Status</th>
                    <th class="p-4 font-semibold text-center">Progres</th>
                    <th class="p-4 font-semibold text-center">Lampiran</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($tasks as $task)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800">
                    <td class="p-4 font-medium text-slate-900 dark:text-white">{{ $task->title }}</td>
                    <td class="p-4 text-center">
                        <span class="badge {{ $task->status === 'Done' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $task->status }}
                        </span>
                    </td>
                    <td class="p-4 text-center">
                        <div class="w-full bg-slate-200 rounded-full h-2.5 max-w-[100px] mx-auto">
                            <div class="bg-cyan-600 h-2.5 rounded-full" style="width: {{ $task->progress_percentage }}%"></div>
                        </div>
                        <span class="text-xs text-slate-500">{{ $task->progress_percentage }}%</span>
                    </td>
                    <td class="p-4 text-center text-slate-600 dark:text-slate-300">
                        {{ $task->files->count() }} File, {{ $task->links->count() }} Link
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-4 text-center text-slate-500">Belum ada tugas di kelompok ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
