<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardService
{
    public function getDashboard($user_id, $search = null)
    {
        return $this->buildDashboard($user_id, $search);
    }

    private function buildDashboard($user_id, $search = null)
    {
        $groupTasks = DB::table('group_members')
            ->join('groups', 'groups.id', '=', 'group_members.group_id')
            ->join('tasks', 'tasks.group_id', '=', 'groups.id')
            ->where('group_members.user_id', $user_id)
            ->when($search, function ($query) use ($search) {
                $query->where('tasks.title', 'ilike', "%$search%");
            })
            ->select(
                'tasks.id',
                'tasks.title',
                'tasks.status',
                'tasks.due_date',
                'tasks.is_group',
                'groups.name as group_name',
                'groups.id as group_id'
            )
            ->get();

        $personalTasks = DB::table('tasks')
            ->where('created_by', $user_id)
            ->where(function ($q) {
                $q->where('is_group', false)->orWhereNull('group_id');
            })
            ->when($search, function ($query) use ($search) {
                $query->where('tasks.title', 'ilike', "%$search%");
            })
            ->select(
                'tasks.id',
                'tasks.title',
                'tasks.status',
                'tasks.due_date',
                'tasks.is_group'
            )
            ->get()
            ->map(function ($t) {
                $t->group_name = null;
                $t->group_id = null;
                return $t;
            });

        $tasks = $groupTasks->merge($personalTasks)->unique('id')->values();

        $now = Carbon::now();

        $done = $tasks->where('status', 'Done')->count();
        $inProgress = $tasks->where('status', 'In Progress')->count();
        $upcoming = $tasks->filter(function ($task) use ($now) {
            return $task->due_date && Carbon::parse($task->due_date)->gt($now);
        })->count();
        $all = $tasks->count();

        $groupedTasks = $groupTasks->groupBy('group_name');

        $profile = DB::table('profiles')
            ->where('id', $user_id)
            ->select('username', 'full_name', 'class_name', 'avatar_url')
            ->first();

        return [
            "user" => $profile,
            "overview" => [
                "all_tasks" => $all,
                "done" => $done,
                "in_progress" => $inProgress,
                "upcoming" => $upcoming,
            ],
            "group_tasks" => $groupedTasks,
        ];
    }
}
