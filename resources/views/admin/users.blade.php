@extends('layouts.admin_layout')
@section('title', 'Kelola Pengguna | SELA')

@section('content')

<div style="margin-bottom:24px;">
    <h4 style="font-size:20px; font-weight:600; color:#566a7f; margin:0 0 4px;" class="dark:text-gray-200">Kelola Pengguna</h4>
    <p style="font-size:13px; color:#a8aabc; margin:0;">Manajemen akun mahasiswa, dosen, dan admin</p>
</div>

{{-- Search & Filter --}}
<div class="card" style="padding:20px 24px; margin-bottom:20px;">
    <form action="{{ route('admin.users') }}" method="GET">
        <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
            <div style="flex:1; min-width:200px;">
                <label style="display:block; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; color:#a8aabc; margin-bottom:6px;">Cari Pengguna</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Nama, username, atau email…"
                    style="width:100%; padding:9px 14px; border:1px solid #d9dbe9; border-radius:8px; font-size:13px; color:#566a7f; background:#fff; outline:none; font-family:'Public Sans',sans-serif; box-sizing:border-box;"
                    class="dark-input">
            </div>
            <div style="min-width:160px;">
                <label style="display:block; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; color:#a8aabc; margin-bottom:6px;">Filter Peran</label>
                <select name="role" style="width:100%; padding:9px 14px; border:1px solid #d9dbe9; border-radius:8px; font-size:13px; color:#566a7f; background:#fff; outline:none; font-family:'Public Sans',sans-serif; box-sizing:border-box;" class="dark-input">
                    <option value="">Semua Peran</option>
                    <option value="student" {{ $role === 'student' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="lecturer" {{ $role === 'lecturer' ? 'selected' : '' }}>Dosen</option>
                    <option value="super_admin" {{ $role === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                </select>
            </div>
            <div style="display:flex; gap:8px;">
                <button type="submit" style="padding:9px 20px; background:#696cff; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; font-family:'Public Sans',sans-serif; transition:opacity 0.2s;" onmouseover="this.style.opacity='0.88'" onmouseout="this.style.opacity='1'">Cari</button>
                <a href="{{ route('admin.users') }}" style="padding:9px 20px; background:#f5f5f9; color:#697a8d; border:1px solid #d9dbe9; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center;">Reset</a>
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
                    <th style="padding:12px 24px; text-align:left; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#a8aabc; white-space:nowrap;">Pengguna</th>
                    <th style="padding:12px 16px; text-align:left; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#a8aabc;">Email</th>
                    <th style="padding:12px 16px; text-align:left; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#a8aabc;">Peran</th>
                    <th style="padding:12px 16px; text-align:left; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#a8aabc; white-space:nowrap;">Terdaftar</th>
                    <th style="padding:12px 24px; text-align:right; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#a8aabc;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr style="border-bottom:1px solid #f0f0f0; transition:background 0.15s;" onmouseover="this.style.background='#f8f8fb'" onmouseout="this.style.background='transparent'">
                    <td style="padding:14px 24px;">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="width:36px; height:36px; border-radius:50%; background:rgba(105,108,255,0.12); color:#696cff; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; flex-shrink:0;">
                                {{ strtoupper(substr($user->username ?? 'U', 0, 2)) }}
                            </div>
                            <div>
                                <div style="font-weight:600; color:#566a7f;" class="dark:text-gray-200">{{ $user->profile?->full_name ?? $user->username }}</div>
                                <div style="font-size:11px; color:#a8aabc;">@{{ $user->username }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding:14px 16px; color:#697a8d;">{{ $user->email }}</td>
                    <td style="padding:14px 16px;">
                        @if($user->role === 'student')
                            <span class="badge badge-primary">Mahasiswa</span>
                        @elseif($user->role === 'lecturer')
                            <span class="badge badge-info">Dosen</span>
                        @else
                            <span class="badge badge-danger">Super Admin</span>
                        @endif
                    </td>
                    <td style="padding:14px 16px; color:#a8aabc; white-space:nowrap; font-size:12px;">
                        {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                    </td>
                    <td style="padding:14px 24px; text-align:right; white-space:nowrap;">
                        <button type="button" onclick="openRoleModal('{{ $user->id }}', '{{ $user->email }}', '{{ $user->role }}')"
                            style="padding:6px 12px; background:#f5f5f9; color:#697a8d; border:1px solid #d9dbe9; border-radius:6px; font-size:12px; font-weight:600; cursor:pointer; margin-right:6px; font-family:'Public Sans',sans-serif; transition:all 0.2s;"
                            onmouseover="this.style.background='rgba(105,108,255,0.08)'; this.style.color='#696cff'; this.style.borderColor='#696cff';"
                            onmouseout="this.style.background='#f5f5f9'; this.style.color='#697a8d'; this.style.borderColor='#d9dbe9';">
                            Ubah Peran
                        </button>
                        @if($user->id !== Auth::id())
                        <button type="button" onclick="confirmDelete('{{ $user->id }}', '{{ $user->email }}')"
                            style="padding:6px 12px; background:rgba(234,84,85,0.08); color:#ea5455; border:1px solid rgba(234,84,85,0.25); border-radius:6px; font-size:12px; font-weight:600; cursor:pointer; font-family:'Public Sans',sans-serif; transition:all 0.2s;"
                            onmouseover="this.style.background='rgba(234,84,85,0.15)';"
                            onmouseout="this.style.background='rgba(234,84,85,0.08)';">
                            Hapus
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding:40px; text-align:center; color:#a8aabc; font-size:13px;">Tidak ada pengguna ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div style="padding:16px 24px; border-top:1px solid #f0f0f0; background:#f8f8fb;">
        {{ $users->links() }}
    </div>
    @endif
</div>

{{-- Role Modal --}}
<div id="roleModal" style="display:none; position:fixed; inset:0; z-index:200; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; padding:20px;">
    <div style="background:#fff; border-radius:12px; padding:28px; width:100%; max-width:420px; box-shadow:0 20px 60px rgba(0,0,0,0.15);" class="dark-card">
        <h5 style="font-size:16px; font-weight:700; color:#566a7f; margin:0 0 6px;" class="dark:text-gray-200">Ubah Peran Pengguna</h5>
        <p style="font-size:13px; color:#a8aabc; margin:0 0 20px;">Ubah peran untuk: <strong id="modalUserEmail" style="color:#566a7f;" class="dark:text-gray-200"></strong></p>
        <form id="roleForm" method="POST">
            @csrf
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; color:#a8aabc; margin-bottom:8px;">Peran Baru</label>
                <select name="role" id="modalRoleSelect" style="width:100%; padding:10px 14px; border:1px solid #d9dbe9; border-radius:8px; font-size:13px; color:#566a7f; background:#fff; outline:none; font-family:'Public Sans',sans-serif; box-sizing:border-box;">
                    <option value="student">Mahasiswa</option>
                    <option value="lecturer">Dosen</option>
                    <option value="super_admin">Super Admin</option>
                </select>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:8px;">
                <button type="button" onclick="closeRoleModal()" style="padding:9px 20px; background:#f5f5f9; color:#697a8d; border:1px solid #d9dbe9; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; font-family:'Public Sans',sans-serif;">Batal</button>
                <button type="submit" style="padding:9px 20px; background:#696cff; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; font-family:'Public Sans',sans-serif;">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Modal --}}
<div id="deleteModal" style="display:none; position:fixed; inset:0; z-index:200; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; padding:20px;">
    <div style="background:#fff; border-radius:12px; padding:28px; width:100%; max-width:420px; box-shadow:0 20px 60px rgba(0,0,0,0.15);" class="dark-card">
        <h5 style="font-size:16px; font-weight:700; color:#ea5455; margin:0 0 6px;">Hapus Akun</h5>
        <p style="font-size:13px; color:#a8aabc; margin:0 0 20px;">Yakin ingin menghapus <strong id="deleteUserEmail" style="color:#566a7f;"></strong>? Tindakan ini permanen.</p>
        <form id="deleteForm" method="POST">
            @csrf
            <div style="display:flex; justify-content:flex-end; gap:8px;">
                <button type="button" onclick="closeDeleteModal()" style="padding:9px 20px; background:#f5f5f9; color:#697a8d; border:1px solid #d9dbe9; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; font-family:'Public Sans',sans-serif;">Batal</button>
                <button type="submit" style="padding:9px 20px; background:#ea5455; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; font-family:'Public Sans',sans-serif;">Hapus</button>
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
        document.getElementById('roleModal').style.display = 'flex';
    }
    function closeRoleModal() { document.getElementById('roleModal').style.display = 'none'; }

    function confirmDelete(userId, email) {
        document.getElementById('deleteUserEmail').innerText = email;
        document.getElementById('deleteForm').action = "{{ route('admin.users.delete', ':id') }}".replace(':id', userId);
        document.getElementById('deleteModal').style.display = 'flex';
    }
    function closeDeleteModal() { document.getElementById('deleteModal').style.display = 'none'; }

    // Close on backdrop click
    document.getElementById('roleModal').addEventListener('click', function(e) { if(e.target===this) closeRoleModal(); });
    document.getElementById('deleteModal').addEventListener('click', function(e) { if(e.target===this) closeDeleteModal(); });
</script>
@endsection
