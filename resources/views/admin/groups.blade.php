@extends('layouts.admin_layout')
@section('page_title', 'Kelola Kelompok')
@section('title', 'Kelola Kelompok | SELA')

@section('content')

{{-- Header Banner --}}
<div class="mb-8 p-6 bg-[#38BDF8] dark:bg-[#0284c7] border-4 border-black dark:border-white shadow-[4px_4px_0_#000] dark:shadow-[4px_4px_0_#fff] transition-colors rounded-xl">
    <h4 class="text-2xl md:text-3xl font-mono font-black text-black dark:text-white uppercase tracking-wider m-0">KUMPULAN KELOMPOK</h4>
    <p class="text-xs md:text-sm font-bold text-black dark:text-white uppercase tracking-wide mt-2">Daftar kelompok, kode akses, dan statistik anggota</p>
</div>

{{-- Search & Filter Container --}}
<div class="bg-white dark:bg-black border-4 border-black dark:border-white p-6 mb-8 shadow-[4px_4px_0_#000] dark:shadow-[4px_4px_0_#fff] rounded-xl">
    <form action="{{ route('admin.groups') }}" method="GET">
        <div class="flex gap-4 flex-wrap items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-black uppercase tracking-wider text-black dark:text-white mb-2 font-mono">Cari Kelompok</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Nama kelompok atau kode..."
                    class="w-full px-4 py-3 border-3 border-black dark:border-white bg-white dark:bg-black text-black dark:text-white font-bold outline-none shadow-[2px_2px_0_#000] dark:shadow-[2px_2px_0_#fff] focus:shadow-[4px_4px_0_#000] dark:focus:shadow-[4px_4px_0_#fff] focus:-translate-y-1 focus:-translate-x-1 transition-all duration-150 rounded-lg">
            </div>
            <div class="min-w-[180px]">
                <label class="block text-xs font-black uppercase tracking-wider text-black dark:text-white mb-2 font-mono">Kelas</label>
                <select name="class" class="w-full px-4 py-3 border-3 border-black dark:border-white bg-white dark:bg-black text-black dark:text-white font-bold outline-none shadow-[2px_2px_0_#000] dark:shadow-[2px_2px_0_#fff] focus:shadow-[4px_4px_0_#000] dark:focus:shadow-[4px_4px_0_#fff] focus:-translate-y-1 focus:-translate-x-1 transition-all duration-150 rounded-lg">
                    <option value="">Semua Kelas</option>
                    @foreach($classes as $cls)
                        <option value="{{ $cls->id }}" {{ $classFilter == $cls->id ? 'selected' : '' }}>
                            {{ $cls->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="px-6 py-3 bg-[#06b6d4] text-white border-3 border-black dark:border-white font-black uppercase tracking-wider text-xs shadow-[3px_3px_0_#000] dark:shadow-[3px_3px_0_#fff] hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[5px_5px_0_#000] dark:hover:shadow-[5px_5px_0_#fff] transition-all cursor-pointer font-mono rounded-lg">
                    Cari
                </button>
                <a href="{{ route('admin.groups') }}" class="px-6 py-3 bg-white dark:bg-black text-black dark:text-white border-3 border-black dark:border-white font-black uppercase tracking-wider text-xs shadow-[3px_3px_0_#000] dark:shadow-[3px_3px_0_#fff] hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[5px_5px_0_#000] dark:hover:shadow-[5px_5px_0_#fff] transition-all cursor-pointer inline-flex items-center justify-center font-mono rounded-lg">
                    Reset
                </a>
            </div>
        </div>
    </form>
</div>

{{-- Main Data Table --}}
<div class="bg-white dark:bg-black border-4 border-black dark:border-white shadow-[6px_6px_0_#000] dark:shadow-[6px_6px_0_#fff] overflow-hidden mb-12 rounded-xl">
    <div class="overflow-x-auto">
        <table class="w-full border-collapse text-left text-sm font-semibold">
            <thead>
                <tr class="bg-[#FDE047] dark:bg-[#ca8a04] border-b-4 border-black dark:border-white">
                    <th class="p-4 border-r-3 border-black dark:border-white font-black uppercase text-xs text-black tracking-widest whitespace-nowrap font-mono">Nama Kelompok</th>
                    <th class="p-4 border-r-3 border-black dark:border-white font-black uppercase text-xs text-black tracking-widest font-mono">Kelas / Matkul</th>
                    <th class="p-4 border-r-3 border-black dark:border-white font-black uppercase text-xs text-black tracking-widest font-mono">Kode</th>
                    <th class="p-4 border-r-3 border-black dark:border-white font-black uppercase text-xs text-black tracking-widest font-mono text-center">Jml Anggota</th>
                    <th class="p-4 font-black uppercase text-xs text-black tracking-widest text-right font-mono">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y-3 divide-black dark:divide-white">
                @forelse($groups as $group)
                <tr class="hover:bg-cyan-50/50 dark:hover:bg-cyan-900/30 transition-colors">
                    <td class="p-4 border-r-3 border-black dark:border-white">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 border-2 border-black dark:border-white bg-[#A3E635] text-black font-black uppercase flex items-center justify-center shadow-[2px_2px_0_#000] dark:shadow-[2px_2px_0_#fff] shrink-0 rounded-lg font-mono">
                                {{ strtoupper(substr($group->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="font-extrabold text-black dark:text-white text-base leading-none mb-1">{{ $group->name }}</div>
                                <div class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dibuat: {{ $group->created_at->format('d M Y') }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="p-4 border-r-3 border-black dark:border-white">
                        <div class="font-bold text-black dark:text-white mb-1">{{ $group->class_name ?? '-' }}</div>
                        <div class="text-[10px] font-black text-white bg-black px-2 py-0.5 inline-block uppercase">{{ $group->course_name ?? '-' }}</div>
                    </td>
                    <td class="p-4 border-r-3 border-black dark:border-white">
                        <span class="inline-block px-3 py-1 bg-white dark:bg-black text-black dark:text-white border-2 border-black dark:border-white font-black uppercase text-[10px] tracking-wider shadow-[2px_2px_0_#000] dark:shadow-[2px_2px_0_#fff] rounded-md font-mono">
                            {{ $group->invitation_code }}
                        </span>
                    </td>
                    <td class="p-4 border-r-3 border-black dark:border-white text-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#fde047] border-2 border-black text-black font-black font-mono shadow-[2px_2px_0_#000]">
                            {{ $group->members_count }}
                        </span>
                    </td>
                    <td class="p-4 text-right whitespace-nowrap">
                        <a href="{{ route('admin.groups.detail', $group->id) }}" class="px-4 py-2.5 bg-[#f5f5f9] dark:bg-zinc-800 text-black dark:text-white border-2 border-black dark:border-white font-black text-[10px] uppercase shadow-[2px_2px_0_#000] dark:shadow-[2px_2px_0_#fff] hover:-translate-y-1 hover:-translate-x-1 hover:shadow-[4px_4px_0_#000] dark:hover:shadow-[4px_4px_0_#fff] transition-all inline-flex items-center justify-center rounded-lg font-mono">
                            Lihat Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-10 text-center font-bold text-gray-500 uppercase tracking-wider font-mono">Tidak ada kelompok ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($groups->hasPages())
    <div class="p-4 border-t-4 border-black dark:border-white bg-white dark:bg-black font-bold">
        {{ $groups->links() }}
    </div>
    @endif
</div>
@endsection
