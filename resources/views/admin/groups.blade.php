@extends('layouts.admin_layout')
@section('page_title', 'Kelola Kelompok')
@section('title', 'Kelola Kelompok | SELA')

@section('content')

{{-- Header --}}
<div class="mb-8 flex items-center gap-3">
    <div class="p-3 bg-amber-100 text-amber-700 rounded-xl">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
    </div>
    <div>
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Kelola Kelompok</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400">Daftar kelompok, kode akses, dan statistik anggota</p>
    </div>
</div>

{{-- Search & Filter --}}
<div class="card mb-8">
    <form action="{{ route('admin.groups') }}" method="GET">
        <div class="flex gap-4 flex-wrap items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-slate-500 mb-1 flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>Cari Kelompok</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Nama kelompok..."
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-cyan-500">
            </div>
            <div class="min-w-[180px]">
                <label class="block text-xs font-semibold text-slate-500 mb-1 flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>Kelas</label>
                <select name="class" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-cyan-500">
                    <option value="">Semua Kelas</option>
                    @foreach($classes as $cls)
                        <option value="{{ $cls->id }}" {{ $classFilter == $cls->id ? 'selected' : '' }}>
                            {{ $cls->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary">Cari</button>
                <a href="{{ route('admin.groups') }}" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg font-semibold hover:bg-slate-200">Reset</a>
            </div>
        </div>
    </form>
</div>

{{-- Main Data Table --}}
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800 text-slate-500">
                <tr>
                    <th class="p-4 font-semibold">Nama Kelompok</th>
                    <th class="p-4 font-semibold">Kelas / Matkul</th>
                    <th class="p-4 font-semibold">Kode</th>
                    <th class="p-4 font-semibold text-center">Anggota</th>
                    <th class="p-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($groups as $group)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800">
                    <td class="p-4 font-medium text-slate-900 dark:text-white">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center font-bold">
                                {{ strtoupper(substr($group->name, 0, 2)) }}
                            </div>
                            {{ $group->name }}
                        </div>
                    </td>
                    <td class="p-4 text-slate-600 dark:text-slate-300">{{ $group->class_name ?? '-' }} / {{ $group->course_name ?? '-' }}</td>
                    <td class="p-4">
                        <span class="px-2 py-1 bg-slate-100 dark:bg-slate-700 rounded font-mono text-xs">{{ $group->invitation_code }}</span>
                    </td>
                    <td class="p-4 text-center text-slate-600 dark:text-slate-300">{{ $group->members_count }}</td>
                    <td class="p-4 text-right">
                        <a href="{{ route('admin.groups.detail', $group->id) }}" class="inline-flex items-center gap-1 text-cyan-600 hover:text-cyan-700 font-semibold">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-10 text-center text-slate-500">
                         <div class="flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <p class="font-medium">Tidak ada kelompok ditemukan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
