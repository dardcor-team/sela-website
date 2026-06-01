<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\Task;
use App\Models\Subtask;
use App\Models\SubtaskProgress;
use App\Models\TaskFile;
use App\Models\TaskLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AdminDashboardController extends Controller
{
    /**
     * Show admin login form.
     */
    public function showLogin()
    {

        if (Auth::guard('web')->check() && Auth::guard('web')->user()->role === 'super_admin') {
            return redirect()->route('admin.overview');
        }

        return view('admin.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::guard('web')->user();

            if ($user->role === 'super_admin') {
                $request->session()->regenerate();
                return redirect()->route('admin.overview')->with('success', 'Selamat datang kembali, Super Admin!');
            }

            // Not admin
            Auth::guard('web')->logout();
            return back()->withErrors([
                'email' => 'Akses ditolak. Akun Anda bukan Super Admin.',
            ])->withInput();
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput();
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Berhasil logout.');
    }

    /**
     * Show main dashboard overview.
     */
    public function index()
    {
        // Statistics
        $usersCount = User::count();
        $totalLecturers = User::where('role', 'lecturer')->count();
        $totalStudents = User::where('role', 'student')->count();
        $totalAdmins = User::where('role', 'super_admin')->count();

        $groupsCount = Group::count();
        $tasksCount = Task::count();
        $coursesCount = \App\Models\Course::count();
        
        // Task status counts
        $taskStatus = [
            'To Do' => Task::where('status', 'To Do')->count(),
            'In Progress' => Task::where('status', 'In Progress')->count(),
            'Done' => Task::where('status', 'Done')->count(),
        ];

        // Subtasks completion rate
        $totalSubtasks = Subtask::count();
        $completedSubtasks = SubtaskProgress::where('progress', 100)->count();
        $subtaskCompletionRate = $totalSubtasks > 0 
            ? round(($completedSubtasks / $totalSubtasks) * 100, 1) 
            : 0;

        // Recent Activities
        $recentUsers = User::with('profile')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentGroups = Group::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentTasks = Task::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get group count per course (distribution)
        $courseDistribution = DB::table('groups')
            ->select('course_name as name', DB::raw('count(id) as count'))
            ->groupBy('course_name')
            ->get();

        return view('admin.overview', compact(
            'usersCount', 'totalLecturers', 'totalStudents', 'totalAdmins',
            'groupsCount', 'tasksCount', 'coursesCount', 'taskStatus', 'totalSubtasks',
            'completedSubtasks', 'subtaskCompletionRate', 'recentUsers',
            'recentGroups', 'recentTasks', 'courseDistribution'
        ));
    }

    /**
     * Show user management.
     */
    public function users(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');

        $query = User::with('profile');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('email', 'ilike', "%{$search}%")
                  ->orWhere('username', 'ilike', "%{$search}%")
                  ->orWhereHas('profile', function($pq) use ($search) {
                      $pq->where('full_name', 'ilike', "%{$search}%");
                  });
            });
        }

        if ($role) {
            $query->where('role', $role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.users', compact('users', 'search', 'role'));
    }

    /**
     * Update user role.
     */
    public function updateUserRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:student,lecturer,super_admin'
        ]);

        $user = User::findOrFail($id);

        // Prevent self-demotion
        if ($user->id === Auth::id() && $request->role !== 'super_admin') {
            return back()->with('error', 'Anda tidak dapat mengubah peran Anda sendiri.');
        }

        DB::transaction(function () use ($user, $request) {
            $user->role = $request->role;
            $user->save();

            $profile = Profile::find($user->id);
            if ($profile) {
                $profile->role = $request->role;
                $profile->save();
            }
        });

        return back()->with('success', "Peran pengguna {$user->email} berhasil diubah menjadi {$request->role}.");
    }

    /**
     * Delete user.
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        DB::transaction(function () use ($user) {
            // Delete profile
            Profile::where('id', $user->id)->delete();
            // Delete user
            $user->delete();
        });

        return back()->with('success', "Pengguna {$user->email} berhasil dihapus.");
    }

    /**
     * Show groups list.
     */
    public function groups(Request $request)
    {
        $search = $request->input('search');
        $classFilter = $request->input('class');

        $query = Group::withCount('members');

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('invitation_code', 'like', "%{$search}%");
        }

        if ($classFilter) {
            $selectedClass = \App\Models\SchoolClass::find($classFilter);
            if ($selectedClass) {
                $query->where('class_name', $selectedClass->name);
            }
        }

        $groups = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $classes = \App\Models\SchoolClass::all();

        return view('admin.groups', compact('groups', 'search', 'classes', 'classFilter'));
    }

    /**
     * Show group details.
     */
    public function groupDetail($id)
    {
        $group = Group::with(['creator'])->findOrFail($id);
        
        $members = GroupMember::with('user.profile')
            ->where('group_id', $group->id)
            ->get();

        $tasks = Task::where('group_id', $group->id)
            ->withCount('subtasks')
            ->get();

        // Calculate progress for each task
        foreach ($tasks as $task) {
            $subtasksCount = Subtask::where('task_id', $task->id)->count();
            if ($subtasksCount > 0) {
                $completed = DB::table('subtasks')
                    ->join('subtask_progress', 'subtask_progress.subtask_id', '=', 'subtasks.id')
                    ->where('subtasks.task_id', $task->id)
                    ->where('subtask_progress.progress', 100)
                    ->count();
                $task->progress_percentage = round(($completed / $subtasksCount) * 100);
            } else {
                $task->progress_percentage = $task->status === 'Done' ? 100 : 0;
            }

            // Get uploaded files and links for this task
            $task->files = TaskFile::where('task_id', $task->id)->get();
            $task->links = TaskLink::where('task_id', $task->id)->get();
        }

        return view('admin.group_detail', compact('group', 'members', 'tasks'));
    }

    /**
     * Show system and health monitor.
     */
    public function system()
    {
        $tables = [
            'users' => 'Pengguna (Akun)',
            'profiles' => 'Profil Pengguna',
            'courses' => 'Mata Kuliah',
            'classes' => 'Kelas Kuliah',
            'groups' => 'Kelompok Tugas',
            'group_members' => 'Anggota Kelompok',
            'tasks' => 'Tugas',
            'subtasks' => 'Sub-tugas',
            'subtask_progress' => 'Progres Sub-tugas',
            'task_files' => 'Berkas Lampiran Tugas',
            'task_links' => 'Tautan Pendukung Tugas',
            'notifications' => 'Notifikasi',
            'device_tokens' => 'Token Perangkat (FCM)',
        ];

        $tableCounts = [];
        foreach ($tables as $table => $label) {
            try {
                $tableCounts[] = [
                    'table' => $table,
                    'label' => $label,
                    'count' => DB::table($table)->count()
                ];
            } catch (\Exception $e) {
                $tableCounts[] = [
                    'table' => $table,
                    'label' => $label,
                    'count' => 'Gagal membaca (Belum bermigrasi/Error)'
                ];
            }
        }

        // File storage stats
        $totalFilesCount = TaskFile::count();
        
        // System variables
        $phpVersion = PHP_VERSION;
        $laravelVersion = app()->version();
        $isMaintenance = Cache::get('app_maintenance', false);
        $dbDriver = DB::connection()->getDriverName();
        $appEnv = config('app.env');

        return view('admin.system', compact('tableCounts', 'totalFilesCount', 'phpVersion', 'laravelVersion', 'dbDriver', 'appEnv', 'isMaintenance'));
    }

    /**
     * Toggle maintenance mode.
     */
    public function toggleMaintenance(Request $request)
    {
        $status = $request->boolean('maintenance');
        Cache::put('app_maintenance', $status);

        $message = $status 
            ? 'Aplikasi berhasil dialihkan ke Mode Perbaikan (Maintenance Mode).' 
            : 'Aplikasi berhasil diaktifkan kembali secara normal.';

        return back()->with('success', $message);
    }
}
