@extends('layouts.admin_layout')
@section('page_title', 'Kelola Pengguna')
@section('title', 'Kelola Pengguna | SELA')

@section('content')

{{-- Header --}}
<div class="mb-8 flex items-center gap-3">
    <div class="p-3 bg-cyan-100 text-cyan-700 rounded-xl">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
    </div>
    <div>
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Kelola Pengguna</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400">Manajemen akun mahasiswa, dosen, dan admin</p>
    </div>
</div>

{{-- Search & Filter --}}
<div class="card mb-8">
    <form action="{{ route('admin.users') }}" method="GET">
        <div class="flex gap-4 flex-wrap items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-slate-500 mb-1 flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>Cari Pengguna</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Nama, username, atau email..."
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-cyan-500">
            </div>
            <div class="min-w-[180px]">
                <label class="block text-xs font-semibold text-slate-500 mb-1 flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>Filter Peran</label>
                <select name="role" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-cyan-500">
                    <option value="">Semua Peran</option>
                    <option value="student" {{ $role === 'student' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="lecturer" {{ $role === 'lecturer' ? 'selected' : '' }}>Dosen</option>
                    <option value="super_admin" {{ $role === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary">Cari</button>
                <a href="{{ route('admin.users') }}" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg font-semibold hover:bg-slate-200">Reset</a>
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
                    <th class="p-4 font-semibold">Pengguna</th>
                    <th class="p-4 font-semibold">Email</th>
                    <th class="p-4 font-semibold">Peran</th>
                    <th class="p-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($users as $user)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800">
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-cyan-100 text-cyan-700 flex items-center justify-center font-bold">
                                {{ strtoupper(substr($user->username ?? 'U', 0, 1)) }}
                            </div>
                            <div class="font-medium text-slate-900 dark:text-white">{{ $user->profile?->full_name ?? $user->username }}</div>
                        </div>
                    </td>
                    <td class="p-4 text-slate-600 dark:text-slate-300">{{ $user->email }}</td>
                    <td class="p-4">
                        <span class="badge {{ $user->role === 'student' ? 'bg-sky-100 text-sky-700' : ($user->role === 'lecturer' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700') }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        <button type="button" onclick="openRoleModal('{{ $user->id }}', '{{ $user->email }}', '{{ $user->role }}')" class="inline-flex items-center gap-1 text-cyan-600 hover:text-cyan-700 font-semibold mr-3">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            Ubah
                        </button>
                        @if($user->id !== Auth::id())
                        <button type="button" onclick="confirmDelete('{{ $user->id }}', '{{ $user->email }}')" class="inline-flex items-center gap-1 text-rose-600 hover:text-rose-700 font-semibold">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Hapus
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-4 text-center text-slate-500">
                        <div class="flex flex-col items-center justify-center py-8">
                            <svg class="w-12 h-12 text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                            <p class="font-medium">Tidak ada pengguna ditemukan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modals ... (keeping original modals but styling needs update) --}}
@endsection
