@extends('layouts.admin_layout')
@section('page_title', 'Data Akademik')
@section('title', 'Data Akademik | SELA')

@section('content')

<div class="mb-8 flex items-center gap-3">
    <div class="p-3 bg-violet-100 text-violet-700 rounded-xl">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
    </div>
    <div>
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Data Akademik</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400">Kelola kelas dan mata kuliah</p>
    </div>
</div>

{{-- Alert --}}
@if(session('success'))
<div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl dark:bg-emerald-900/20 dark:border-emerald-700 dark:text-emerald-300">
    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-6 flex items-center gap-3 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl dark:bg-rose-900/20 dark:border-rose-700 dark:text-rose-300">
    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('error') }}
</div>
@endif

<div class="grid grid-cols-1 xl:grid-cols-2 gap-8">

    {{-- ============================================================ --}}
    {{-- SECTION: KELAS --}}
    {{-- ============================================================ --}}
    <div>
        <div class="card mb-4">
            <div class="flex items-center gap-2 mb-4">
                <div class="p-2 bg-sky-100 text-sky-700 rounded-lg"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
                <h3 class="font-bold text-slate-800 dark:text-white text-lg">Tambah Kelas Baru</h3>
            </div>
            <form action="{{ route('admin.classes.store') }}" method="POST" class="flex gap-3">
                @csrf
                <input type="text" name="name" placeholder="Nama kelas (misal: TI-2A)" required
                    class="flex-1 px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-sky-500">
                <button type="submit" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded-lg transition">Tambah</button>
            </form>
            @error('name') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-slate-800 dark:text-white">Daftar Kelas <span class="ml-2 text-sm font-normal text-slate-500">({{ $classes->total() }} kelas)</span></h3>
                <form action="{{ route('admin.academic') }}" method="GET" class="flex gap-2">
                    <input type="hidden" name="course_search" value="{{ $courseSearch }}">
                    <input type="text" name="class_search" value="{{ $classSearch }}" placeholder="Cari kelas..."
                        class="px-3 py-1.5 text-sm border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-sky-500">
                    <button type="submit" class="px-3 py-1.5 bg-sky-600 text-white text-sm rounded-lg hover:bg-sky-700">Cari</button>
                </form>
            </div>

            <div class="space-y-2">
                @forelse($classes as $class)
                <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-slate-800 rounded-xl">
                    <div class="w-8 h-8 bg-sky-100 text-sky-700 rounded-lg flex items-center justify-center font-bold text-sm shrink-0">
                        {{ strtoupper(substr($class->name, 0, 2)) }}
                    </div>
                    <span class="flex-1 font-medium text-slate-800 dark:text-white text-sm">{{ $class->name }}</span>
                    <div class="flex gap-2">
                        <button onclick="openEditClass('{{ $class->id }}', '{{ addslashes($class->name) }}')"
                            class="p-1.5 text-slate-400 hover:text-sky-600 hover:bg-sky-50 dark:hover:bg-sky-900/30 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </button>
                        <button onclick="openDeleteClass('{{ $class->id }}', '{{ addslashes($class->name) }}')"
                            class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-slate-400">
                    <svg class="w-10 h-10 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <p class="text-sm">Belum ada kelas</p>
                </div>
                @endforelse
            </div>

            @if($classes->hasPages())
            <div class="mt-4">{{ $classes->links() }}</div>
            @endif
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- SECTION: MATA KULIAH --}}
    {{-- ============================================================ --}}
    <div>
        <div class="card mb-4">
            <div class="flex items-center gap-2 mb-4">
                <div class="p-2 bg-violet-100 text-violet-700 rounded-lg"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg></div>
                <h3 class="font-bold text-slate-800 dark:text-white text-lg">Tambah Mata Kuliah</h3>
            </div>
            <form action="{{ route('admin.courses.store') }}" method="POST" class="space-y-3">
                @csrf
                <input type="text" name="name" placeholder="Nama mata kuliah (misal: Pemrograman Web)" required
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-violet-500">
                <div class="flex gap-3">
                    <input type="text" name="description" placeholder="Deskripsi (opsional)"
                        class="flex-1 px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-violet-500">
                    <button type="submit" class="px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-lg transition">Tambah</button>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-slate-800 dark:text-white">Daftar Mata Kuliah <span class="ml-2 text-sm font-normal text-slate-500">({{ $courses->total() }} matkul)</span></h3>
                <form action="{{ route('admin.academic') }}" method="GET" class="flex gap-2">
                    <input type="hidden" name="class_search" value="{{ $classSearch }}">
                    <input type="text" name="course_search" value="{{ $courseSearch }}" placeholder="Cari matkul..."
                        class="px-3 py-1.5 text-sm border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-violet-500">
                    <button type="submit" class="px-3 py-1.5 bg-violet-600 text-white text-sm rounded-lg hover:bg-violet-700">Cari</button>
                </form>
            </div>

            <div class="space-y-2">
                @forelse($courses as $course)
                <div class="flex items-start gap-3 p-3 bg-slate-50 dark:bg-slate-800 rounded-xl">
                    <div class="w-8 h-8 bg-violet-100 text-violet-700 rounded-lg flex items-center justify-center font-bold text-sm shrink-0 mt-0.5">
                        {{ strtoupper(substr($course->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-slate-800 dark:text-white text-sm truncate">{{ $course->name }}</p>
                        @if($course->description)
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 truncate">{{ $course->description }}</p>
                        @endif
                    </div>
                    <div class="flex gap-2 shrink-0">
                        <button onclick="openEditCourse('{{ $course->id }}', '{{ addslashes($course->name) }}', '{{ addslashes($course->description ?? '') }}')"
                            class="p-1.5 text-slate-400 hover:text-violet-600 hover:bg-violet-50 dark:hover:bg-violet-900/30 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </button>
                        <button onclick="openDeleteCourse('{{ $course->id }}', '{{ addslashes($course->name) }}')"
                            class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-slate-400">
                    <svg class="w-10 h-10 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/></svg>
                    <p class="text-sm">Belum ada mata kuliah</p>
                </div>
                @endforelse
            </div>

            @if($courses->hasPages())
            <div class="mt-4">{{ $courses->links() }}</div>
            @endif
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODALS --}}
{{-- ============================================================ --}}

{{-- Edit Kelas --}}
<div id="editClassModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('editClassModal')"></div>
    <div class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-sm p-6 border border-slate-200 dark:border-slate-700">
        <h3 class="font-bold text-slate-900 dark:text-white text-lg mb-4">Edit Kelas</h3>
        <form id="editClassForm" method="POST">
            @csrf
            <input type="text" id="editClassName" name="name" required
                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-sky-500 mb-4">
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeModal('editClassModal')" class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-semibold hover:bg-slate-100 dark:hover:bg-slate-800 transition">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-sky-600 hover:bg-sky-700 text-white font-semibold transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Kelas --}}
<div id="deleteClassModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('deleteClassModal')"></div>
    <div class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-sm p-6 border border-slate-200 dark:border-slate-700 text-center">
        <div class="p-3 bg-rose-100 text-rose-600 rounded-2xl w-fit mx-auto mb-3"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></div>
        <h3 class="font-bold text-slate-900 dark:text-white text-lg">Hapus Kelas?</h3>
        <p class="text-sm text-slate-500 mt-1">Kelas <strong id="deleteClassName" class="text-rose-600"></strong> akan dihapus permanen.</p>
        <form id="deleteClassForm" method="POST" class="flex gap-3 mt-5">
            @csrf
            <button type="button" onclick="closeModal('deleteClassModal')" class="flex-1 px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">Batal</button>
            <button type="submit" class="flex-1 px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-semibold transition">Hapus</button>
        </form>
    </div>
</div>

{{-- Edit Mata Kuliah --}}
<div id="editCourseModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('editCourseModal')"></div>
    <div class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-sm p-6 border border-slate-200 dark:border-slate-700">
        <h3 class="font-bold text-slate-900 dark:text-white text-lg mb-4">Edit Mata Kuliah</h3>
        <form id="editCourseForm" method="POST" class="space-y-3">
            @csrf
            <input type="text" id="editCourseName" name="name" placeholder="Nama mata kuliah" required
                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-violet-500">
            <input type="text" id="editCourseDesc" name="description" placeholder="Deskripsi (opsional)"
                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-violet-500">
            <div class="flex gap-3 justify-end pt-1">
                <button type="button" onclick="closeModal('editCourseModal')" class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-semibold hover:bg-slate-100 dark:hover:bg-slate-800 transition">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-violet-600 hover:bg-violet-700 text-white font-semibold transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Mata Kuliah --}}
<div id="deleteCourseModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('deleteCourseModal')"></div>
    <div class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-sm p-6 border border-slate-200 dark:border-slate-700 text-center">
        <div class="p-3 bg-rose-100 text-rose-600 rounded-2xl w-fit mx-auto mb-3"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></div>
        <h3 class="font-bold text-slate-900 dark:text-white text-lg">Hapus Mata Kuliah?</h3>
        <p class="text-sm text-slate-500 mt-1">Mata kuliah <strong id="deleteCourseName" class="text-rose-600"></strong> akan dihapus permanen.</p>
        <form id="deleteCourseForm" method="POST" class="flex gap-3 mt-5">
            @csrf
            <button type="button" onclick="closeModal('deleteCourseModal')" class="flex-1 px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">Batal</button>
            <button type="submit" class="flex-1 px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-semibold transition">Hapus</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openModal(id) {
    const m = document.getElementById(id);
    m.classList.remove('hidden');
    m.classList.add('flex');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    const m = document.getElementById(id);
    m.classList.add('hidden');
    m.classList.remove('flex');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') ['editClassModal','deleteClassModal','editCourseModal','deleteCourseModal'].forEach(closeModal); });

function openEditClass(id, name) {
    document.getElementById('editClassName').value = name;
    document.getElementById('editClassForm').action = `/admin/academic/classes/${id}/update`;
    openModal('editClassModal');
}
function openDeleteClass(id, name) {
    document.getElementById('deleteClassName').textContent = name;
    document.getElementById('deleteClassForm').action = `/admin/academic/classes/${id}/delete`;
    openModal('deleteClassModal');
}
function openEditCourse(id, name, desc) {
    document.getElementById('editCourseName').value = name;
    document.getElementById('editCourseDesc').value = desc;
    document.getElementById('editCourseForm').action = `/admin/academic/courses/${id}/update`;
    openModal('editCourseModal');
}
function openDeleteCourse(id, name) {
    document.getElementById('deleteCourseName').textContent = name;
    document.getElementById('deleteCourseForm').action = `/admin/academic/courses/${id}/delete`;
    openModal('deleteCourseModal');
}
</script>
@endpush

@endsection
