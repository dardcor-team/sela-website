@extends('layouts.admin_layout')
@section('page_title', 'Kelola Pengguna')
@section('title', 'Kelola Pengguna | SELA')

@section('content')

{{-- Header Banner: Neo-Brutalist Block --}}
<div class="mb-8 p-6 bg-[#A3E635] dark:bg-[#5f8714] border-4 border-black dark:border-white shadow-[4px_4px_0_#000] dark:shadow-[4px_4px_0_#fff] transition-colors rounded-xl">
    <h4 class="text-2xl md:text-3xl font-mono font-black text-black dark:text-white uppercase tracking-wider m-0">KUMPULAN PENGGUNA</h4>
    <p class="text-xs md:text-sm font-bold text-black dark:text-gray-200 uppercase tracking-wide mt-2">Manajemen akun mahasiswa, dosen, dan admin</p>
</div>

{{-- Search & Filter Container --}}
<div class="bg-white dark:bg-black border-4 border-black dark:border-white p-6 mb-8 shadow-[4px_4px_0_#000] dark:shadow-[4px_4px_0_#fff] rounded-xl">
    <form action="{{ route('admin.users') }}" method="GET">
        <div class="flex gap-4 flex-wrap items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-black uppercase tracking-wider text-black dark:text-white mb-2 font-mono">Cari Pengguna</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Nama, username, atau email…"
                    class="w-full px-4 py-3 border-3 border-black dark:border-white bg-white dark:bg-black text-black dark:text-white font-bold outline-none shadow-[2px_2px_0_#000] dark:shadow-[2px_2px_0_#fff] focus:shadow-[4px_4px_0_#000] dark:focus:shadow-[4px_4px_0_#fff] focus:-translate-y-1 focus:-translate-x-1 transition-all duration-150 rounded-lg">
            </div>
            <div class="min-w-[180px]">
                <label class="block text-xs font-black uppercase tracking-wider text-black dark:text-white mb-2 font-mono">Filter Peran</label>
                <select name="role" class="w-full px-4 py-3 border-3 border-black dark:border-white bg-white dark:bg-black text-black dark:text-white font-bold outline-none shadow-[2px_2px_0_#000] dark:shadow-[2px_2px_0_#fff] focus:shadow-[4px_4px_0_#000] dark:focus:shadow-[4px_4px_0_#fff] focus:-translate-y-1 focus:-translate-x-1 transition-all duration-150 rounded-lg">
                    <option value="">Semua Peran</option>
                    <option value="student" {{ $role === 'student' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="lecturer" {{ $role === 'lecturer' ? 'selected' : '' }}>Dosen</option>
                    <option value="super_admin" {{ $role === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                </select>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="px-6 py-3 bg-[#06b6d4] text-white border-3 border-black dark:border-white font-black uppercase tracking-wider text-xs shadow-[3px_3px_0_#000] dark:shadow-[3px_3px_0_#fff] hover:-translate-x-1 hover:-translate-y-1 active:translate-x-0 active:translate-y-0 hover:shadow-[5px_5px_0_#000] dark:hover:shadow-[5px_5px_0_#fff] active:shadow-[1px_1px_0_#000] transition-all cursor-pointer font-mono rounded-lg">
                    Cari
                </button>
                <a href="{{ route('admin.users') }}" class="px-6 py-3 bg-white dark:bg-black text-black dark:text-white border-3 border-black dark:border-white font-black uppercase tracking-wider text-xs shadow-[3px_3px_0_#000] dark:shadow-[3px_3px_0_#fff] hover:-translate-x-1 hover:-translate-y-1 active:translate-x-0 active:translate-y-0 hover:shadow-[5px_5px_0_#000] dark:hover:shadow-[5px_5px_0_#fff] active:shadow-[1px_1px_0_#000] transition-all cursor-pointer inline-flex items-center justify-center font-mono rounded-lg">
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
                    <th class="p-4 border-r-3 border-black dark:border-white font-black uppercase text-xs text-black tracking-widest whitespace-nowrap font-mono">Pengguna</th>
                    <th class="p-4 border-r-3 border-black dark:border-white font-black uppercase text-xs text-black tracking-widest font-mono">Email</th>
                    <th class="p-4 border-r-3 border-black dark:border-white font-black uppercase text-xs text-black tracking-widest font-mono">Peran</th>
                    <th class="p-4 border-r-3 border-black dark:border-white font-black uppercase text-xs text-black tracking-widest whitespace-nowrap font-mono">Terdaftar</th>
                    <th class="p-4 font-black uppercase text-xs text-black tracking-widest text-right font-mono">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y-3 divide-black dark:divide-white">
                @forelse($users as $user)
                <tr class="hover:bg-yellow-50/50 dark:hover:bg-zinc-800/40 transition-colors">
                    <td class="p-4 border-r-3 border-black dark:border-white">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 border-2 border-black dark:border-white bg-[#FDA4AF] text-black font-black uppercase flex items-center justify-center shadow-[2px_2px_0_#000] dark:shadow-[2px_2px_0_#fff] shrink-0 rounded-lg font-mono">
                                {{ strtoupper(substr($user->username ?? 'U', 0, 2)) }}
                            </div>
                            <div>
                                <div class="font-extrabold text-black dark:text-white text-base leading-none mb-1">{{ $user->profile?->full_name ?? $user->username }}</div>
                                <div class="text-xs font-bold text-gray-500 dark:text-gray-400">{{ "@".$user->username }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="p-4 border-r-3 border-black dark:border-white text-black dark:text-gray-200 font-bold">{{ $user->email }}</td>
                    <td class="p-4 border-r-3 border-black dark:border-white">
                        @if($user->role === 'student')
                            <span class="inline-block px-3 py-1 bg-[#38BDF8] text-black border-2 border-black dark:border-white font-black uppercase text-[10px] tracking-wider shadow-[2px_2px_0_#000] dark:shadow-[2px_2px_0_#fff] rounded-md font-mono">Mahasiswa</span>
                        @elseif($user->role === 'lecturer')
                            <span class="inline-block px-3 py-1 bg-[#A3E635] text-black border-2 border-black dark:border-white font-black uppercase text-[10px] tracking-wider shadow-[2px_2px_0_#000] dark:shadow-[2px_2px_0_#fff] rounded-md font-mono">Dosen</span>
                        @else
                            <span class="inline-block px-3 py-1 bg-[#F43F5E] text-white border-2 border-black dark:border-white font-black uppercase text-[10px] tracking-wider shadow-[2px_2px_0_#000] dark:shadow-[2px_2px_0_#fff] rounded-md font-mono">Super Admin</span>
                        @endif
                    </td>
                    <td class="p-4 border-r-3 border-black dark:border-white text-black dark:text-gray-200 font-bold whitespace-nowrap text-xs">
                        {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                    </td>
                    <td class="p-4 text-right whitespace-nowrap">
                        <button type="button" onclick="openRoleModal('{{ $user->id }}', '{{ $user->email }}', '{{ $user->role }}')"
                            class="px-3 py-2 bg-white dark:bg-black text-black dark:text-white border-2 border-black dark:border-white font-black text-[10px] uppercase shadow-[2px_2px_0_#000] dark:shadow-[2px_2px_0_#fff] hover:-translate-y-1 hover:-translate-x-1 active:translate-x-0 active:translate-y-0 hover:shadow-[4px_4px_0_#000] dark:hover:shadow-[4px_4px_0_#fff] active:shadow-[1px_1px_0_#000] transition-all cursor-pointer mr-2 rounded-lg font-mono">
                            Ubah Peran
                        </button>
                        @if($user->id !== Auth::id())
                        <button type="button" onclick="confirmDelete('{{ $user->id }}', '{{ $user->email }}')"
                            class="px-3 py-2 bg-[#FF6B6B] text-black border-2 border-black dark:border-white font-black text-[10px] uppercase shadow-[2px_2px_0_#000] dark:shadow-[2px_2px_0_#fff] hover:-translate-y-1 hover:-translate-x-1 active:translate-x-0 active:translate-y-0 hover:shadow-[4px_4px_0_#000] dark:hover:shadow-[4px_4px_0_#fff] active:shadow-[1px_1px_0_#000] transition-all cursor-pointer rounded-lg font-mono">
                            Hapus
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-10 text-center font-bold text-gray-500 uppercase tracking-wider font-mono">Tidak ada pengguna ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="p-4 border-t-4 border-black dark:border-white bg-white dark:bg-black font-bold">
        {{ $users->links() }}
    </div>
    @endif
</div>

{{-- Role Modal --}}
<div id="roleModal" class="hidden fixed inset-0 z-[200] bg-black/80 backdrop-blur-sm items-center justify-center p-4">
    <div class="bg-white dark:bg-black border-4 border-black dark:border-white p-8 max-w-md w-full shadow-[8px_8px_0_#000] dark:shadow-[8px_8px_0_#fff] relative rounded-xl transform transition-transform scale-95" id="roleModalContent">
        <h5 class="text-xl font-black uppercase text-black dark:text-white border-b-4 border-black dark:border-white pb-3 mb-4 font-mono">UBAH PERAN</h5>
        <p class="text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wide mb-6">Ubah peran untuk: <br><strong id="modalUserEmail" class="text-black dark:text-white font-mono break-all text-sm mt-1 inline-block"></strong></p>
        <form id="roleForm" method="POST">
            @csrf
            <div class="mb-8">
                <label class="block text-xs font-black uppercase tracking-wider text-black dark:text-white mb-2 font-mono">Peran Baru</label>
                <select name="role" id="modalRoleSelect" class="w-full px-4 py-3 border-3 border-black dark:border-white bg-white dark:bg-black text-black dark:text-white font-bold outline-none shadow-[2px_2px_0_#000] dark:shadow-[2px_2px_0_#fff] focus:shadow-[4px_4px_0_#000] dark:focus:shadow-[4px_4px_0_#fff] focus:-translate-y-1 focus:-translate-x-1 transition-all rounded-lg">
                    <option value="student">Mahasiswa</option>
                    <option value="lecturer">Dosen</option>
                    <option value="super_admin">Super Admin</option>
                </select>
            </div>
            <div class="flex justify-end gap-4 mt-8">
                <button type="button" onclick="closeRoleModal()" class="px-5 py-3 bg-white dark:bg-black text-black dark:text-white border-3 border-black dark:border-white font-black uppercase text-xs shadow-[3px_3px_0_#000] dark:shadow-[3px_3px_0_#fff] hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[5px_5px_0_#000] dark:hover:shadow-[5px_5px_0_#fff] transition-all cursor-pointer rounded-lg font-mono">Batal</button>
                <button type="submit" class="px-5 py-3 bg-[#A3E635] text-black border-3 border-black dark:border-white font-black uppercase text-xs shadow-[3px_3px_0_#000] dark:shadow-[3px_3px_0_#fff] hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[5px_5px_0_#000] dark:hover:shadow-[5px_5px_0_#fff] transition-all cursor-pointer rounded-lg font-mono">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Modal --}}
<div id="deleteModal" class="hidden fixed inset-0 z-[200] bg-black/80 backdrop-blur-sm items-center justify-center p-4">
    <div class="bg-white dark:bg-black border-4 border-black dark:border-white p-8 max-w-md w-full shadow-[8px_8px_0_#000] dark:shadow-[8px_8px_0_#fff] relative rounded-xl transform transition-transform scale-95" id="deleteModalContent">
        <h5 class="text-xl font-black uppercase text-[#F43F5E] border-b-4 border-black dark:border-white pb-3 mb-4 font-mono">HAPUS AKUN</h5>
        <p class="text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wide mb-6">Yakin ingin menghapus <strong id="deleteUserEmail" class="text-black dark:text-white font-mono break-all inline-block mt-1"></strong>?<br><br> Tindakan ini permanen.</p>
        <form id="deleteForm" method="POST">
            @csrf
            <div class="flex justify-end gap-4 mt-8">
                <button type="button" onclick="closeDeleteModal()" class="px-5 py-3 bg-white dark:bg-black text-black dark:text-white border-3 border-black dark:border-white font-black uppercase text-xs shadow-[3px_3px_0_#000] dark:shadow-[3px_3px_0_#fff] hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[5px_5px_0_#000] dark:hover:shadow-[5px_5px_0_#fff] transition-all cursor-pointer rounded-lg font-mono">Batal</button>
                <button type="submit" class="px-5 py-3 bg-[#F43F5E] text-white border-3 border-black dark:border-white font-black uppercase text-xs shadow-[3px_3px_0_#000] dark:shadow-[3px_3px_0_#fff] hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[5px_5px_0_#000] dark:hover:shadow-[5px_5px_0_#fff] transition-all cursor-pointer rounded-lg font-mono">Hapus</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openRoleModal(userId, email, currentRole) {
        document.getElementById('modalUserEmail').innerText = email;
        document.getElementById('modalRoleSelect').value = currentRole;
        document.getElementById('roleForm').action = "{{ route('admin.users.update-role', ':id') }}".replace(':id', userId);
        const modal = document.getElementById('roleModal');
        const content = document.getElementById('roleModalContent');
        modal.style.display = 'flex';
        modal.classList.remove('hidden');
        setTimeout(() => content.classList.replace('scale-95', 'scale-100'), 10);
    }
    function closeRoleModal() {
        const modal = document.getElementById('roleModal');
        const content = document.getElementById('roleModalContent');
        content.classList.replace('scale-100', 'scale-95');
        setTimeout(() => {
            modal.style.display = 'none';
            modal.classList.add('hidden');
        }, 150);
    }

    function confirmDelete(userId, email) {
        document.getElementById('deleteUserEmail').innerText = email;
        document.getElementById('deleteForm').action = "{{ route('admin.users.delete', ':id') }}".replace(':id', userId);
        const modal = document.getElementById('deleteModal');
        const content = document.getElementById('deleteModalContent');
        modal.style.display = 'flex';
        modal.classList.remove('hidden');
        setTimeout(() => content.classList.replace('scale-95', 'scale-100'), 10);
    }
    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        const content = document.getElementById('deleteModalContent');
        content.classList.replace('scale-100', 'scale-95');
        setTimeout(() => {
            modal.style.display = 'none';
            modal.classList.add('hidden');
        }, 150);
    }

    // Close on backdrop click
    document.getElementById('roleModal').addEventListener('click', function(e) { if(e.target===this) closeRoleModal(); });
    document.getElementById('deleteModal').addEventListener('click', function(e) { if(e.target===this) closeDeleteModal(); });
</script>
@endsection
