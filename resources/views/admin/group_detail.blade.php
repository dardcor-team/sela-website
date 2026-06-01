@extends('layouts.admin_layout')
@section('page_title', 'Detail Kelompok')
@section('title', 'Detail Kelompok | SELA')

@section('content')

{{-- Back Button --}}
<a href="{{ route('admin.groups') }}" class="inline-flex items-center gap-2 mb-6 px-4 py-2 bg-white dark:bg-black text-black dark:text-white border-3 border-black dark:border-white font-black uppercase text-xs shadow-[3px_3px_0_#000] dark:shadow-[3px_3px_0_#fff] hover:-translate-y-1 hover:-translate-x-1 hover:shadow-[5px_5px_0_#000] dark:hover:shadow-[5px_5px_0_#fff] transition-all rounded-lg font-mono">
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
    </svg>
    Kembali
</a>

{{-- Header Banner --}}
<div class="mb-8 p-8 bg-[#A3E635] dark:bg-[#5f8714] border-4 border-black dark:border-white shadow-[4px_4px_0_#000] dark:shadow-[4px_4px_0_#fff] rounded-xl flex flex-col md:flex-row gap-6 md:items-center justify-between">
    <div>
        <h4 class="text-3xl md:text-4xl font-mono font-black text-black dark:text-white uppercase tracking-wider m-0">{{ $group->name }}</h4>
        <div class="flex items-center gap-3 mt-4 flex-wrap">
            <span class="px-3 py-1 bg-white text-black border-2 border-black font-black uppercase text-xs shadow-[2px_2px_0_#000] rounded-md font-mono">Kelas: {{ $group->schoolClass?->name ?? '-' }}</span>
            <span class="px-3 py-1 bg-[#06b6d4] text-white border-2 border-black font-black uppercase text-xs shadow-[2px_2px_0_#000] rounded-md font-mono">Matkul: {{ $group->schoolClass?->course?->name ?? '-' }}</span>
        </div>
    </div>
    <div class="bg-white p-4 border-3 border-black text-center shadow-[4px_4px_0_#000] rounded-xl min-w-[150px]">
        <div class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 font-mono">Kode Akses</div>
        <div class="text-2xl font-mono font-black text-black tracking-widest">{{ $group->join_code }}</div>
    </div>
</div>

{{-- Anggota List --}}
<div class="mb-8">
    <h5 class="text-xl font-mono font-black text-black dark:text-white uppercase mb-4 tracking-wider flex items-center gap-3">
        ANGGOTA KELOMPOK
        <span class="inline-flex items-center justify-center w-8 h-8 bg-[#fde047] border-2 border-black text-black text-sm shadow-[2px_2px_0_#000] rounded-full">{{ $group->members->count() }}</span>
    </h5>
    
    <div class="bg-white dark:bg-black border-4 border-black dark:border-white shadow-[6px_6px_0_#000] dark:shadow-[6px_6px_0_#fff] overflow-hidden rounded-xl">
        <table class="w-full border-collapse text-left text-sm font-semibold">
            <thead>
                <tr class="bg-[#38BDF8] dark:bg-[#0284c7] border-b-4 border-black dark:border-white">
                    <th class="p-4 border-r-3 border-black dark:border-white font-black uppercase text-xs text-black tracking-widest font-mono">Mahasiswa</th>
                    <th class="p-4 border-r-3 border-black dark:border-white font-black uppercase text-xs text-black tracking-widest font-mono text-center">Status</th>
                    <th class="p-4 font-black uppercase text-xs text-black tracking-widest font-mono text-center">Bergabung Pada</th>
                </tr>
            </thead>
            <tbody class="divide-y-3 divide-black dark:divide-white">
                @forelse($group->members as $member)
                <tr class="hover:bg-cyan-50/50 dark:hover:bg-cyan-900/30 transition-colors">
                    <td class="p-4 border-r-3 border-black dark:border-white">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 border-2 border-black dark:border-white bg-[#FDA4AF] text-black font-black uppercase flex items-center justify-center shadow-[2px_2px_0_#000] dark:shadow-[2px_2px_0_#fff] shrink-0 rounded-lg font-mono">
                                {{ strtoupper(substr($member->user->profile?->full_name ?? $member->user->username ?? 'U', 0, 2)) }}
                            </div>
                            <div>
                                <div class="font-extrabold text-black dark:text-white text-base leading-none mb-1">{{ $member->user->profile?->full_name ?? $member->user->username }}</div>
                                <div class="text-xs font-bold text-gray-500 dark:text-gray-400">{{ "@".$member->user->username }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="p-4 border-r-3 border-black dark:border-white text-center">
                        @if($member->status === 'active')
                            <span class="inline-block px-3 py-1 bg-[#A3E635] text-black border-2 border-black dark:border-white font-black uppercase text-[10px] tracking-wider shadow-[2px_2px_0_#000] dark:shadow-[2px_2px_0_#fff] rounded-md font-mono">Aktif</span>
                        @else
                            <span class="inline-block px-3 py-1 bg-[#F43F5E] text-white border-2 border-black dark:border-white font-black uppercase text-[10px] tracking-wider shadow-[2px_2px_0_#000] dark:shadow-[2px_2px_0_#fff] rounded-md font-mono">{{ $member->status }}</span>
                        @endif
                    </td>
                    <td class="p-4 text-center font-bold text-black dark:text-gray-200">
                        {{ $member->joined_at ? $member->joined_at->format('d M Y') : '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="p-10 text-center font-bold text-gray-500 uppercase tracking-wider font-mono">Belum ada anggota di kelompok ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
