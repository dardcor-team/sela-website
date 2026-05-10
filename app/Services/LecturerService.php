<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\LecturerClass;
use Carbon\Carbon;

class LecturerService
{
    public function getClasses($lecturerId)
    {
        $lecturerClasses = LecturerClass::where('lecturer_id', $lecturerId)->get();

        return $lecturerClasses->map(function ($lc) {
            $className = $lc->class_name;

            // Fetch exact class details if needed, though class_name is direct now
            $class = DB::table('classes')->where('name', $className)->first();

            $groupIds = collect();
            $totalGroups = 0;
            if ($className) {
                $groupIds = DB::table('groups')->where('class_name', $className)->pluck('id');
                $totalGroups = $groupIds->count();
            }

            $totalTasks = $groupIds->isNotEmpty()
                ? DB::table('tasks')->whereIn('group_id', $groupIds)->where('is_group', true)->count()
                : 0;

            $lastTask = $groupIds->isNotEmpty()
                ? DB::table('tasks')
                    ->whereIn('group_id', $groupIds)
                    ->orderBy('created_at', 'desc')
                    ->first()
                : null;

            $lastUpdated = $lastTask ? Carbon::parse($lastTask->created_at)->diffForHumans() : 'No activity';

            return [
                'id' => $class ? $class->id : $lc->id,
                'name' => $className,
                'total_groups' => $totalGroups,
                'total_tasks' => $totalTasks,
                'last_updated' => $lastUpdated,
            ];
        })->values()->toArray();
    }

    public function getClassTasks($classId)
    {
        $class = DB::table('classes')->where('id', $classId)->first();
        $className = $class ? $class->name : null;

        $groups = $className
            ? DB::table('groups')->where('class_name', $className)->get()
            : collect();

        $result = [];
        foreach ($groups as $group) {
            $tasks = DB::table('tasks')
                ->where('group_id', $group->id)
                ->where('is_group', true)
                ->get();

            foreach ($tasks as $task) {
                $subtasks = DB::table('subtasks')->where('task_id', $task->id)->get();
                $totalSubtasks = $subtasks->count();

                $completedSubtasks = 0;
                foreach ($subtasks as $st) {
                    $progress = DB::table('subtask_progress')
                        ->where('subtask_id', $st->id)
                        ->orderBy('updated_at', 'desc')
                        ->first();
                    if ($progress && $progress->progress >= 100) {
                        $completedSubtasks++;
                    }
                }

                $progressPercent = $totalSubtasks > 0 ? round(($completedSubtasks / $totalSubtasks) * 100) : 0;

                $members = DB::table('group_members')
                    ->join('profiles', 'profiles.id', '=', 'group_members.user_id')
                    ->where('group_members.group_id', $group->id)
                    ->select('profiles.id', 'profiles.full_name as name', 'profiles.avatar_url as avatar')
                    ->get();

                $countdown = $task->due_date
                    ? (int) Carbon::now()->diffInDays(Carbon::parse($task->due_date), false)
                    : 0;

                $dateRange = '';
                if ($task->start_date && $task->due_date) {
                    $dateRange = Carbon::parse($task->start_date)->format('d M') . ' - ' . Carbon::parse($task->due_date)->format('d M');
                }

                $result[] = [
                    'id' => $task->id,
                    'class_id' => $classId,
                    'group_name' => $group->name,
                    'task_name' => $task->title,
                    'progress' => (int)$progressPercent,
                    'date_range' => $dateRange,
                    'subject' => $task->subject ?? $group->course_name ?? '',
                    'completed_tasks' => $completedSubtasks,
                    'unfinished_tasks' => $totalSubtasks - $completedSubtasks,
                    'countdown' => $countdown,
                    'member_count' => $members->count(),
                    'members' => $members->map(function ($m) use ($task) {
                        $memberSubtasks = DB::table('subtasks')
                            ->join('subtask_progress', 'subtask_progress.subtask_id', '=', 'subtasks.id')
                            ->where('subtasks.task_id', $task->id)
                            ->where('subtask_progress.user_id', $m->id)
                            ->select('subtasks.title as name', 'subtask_progress.progress')
                            ->get()
                            ->map(function ($s) {
                                $s->status = $s->progress >= 100 ? 'Done' : ($s->progress > 0 ? 'In Progress' : 'To Do');
                                unset($s->progress);
                                return $s;
                            })
                            ->toArray();

                        return [
                            'name' => $m->name,
                            'avatar_url' => $m->avatar,
                            'task_count' => count($memberSubtasks) . ' SubTask',
                            'subtasks' => $memberSubtasks,
                        ];
                    })->toArray(),
                ];
            }
        }

        return $result;
    }

    public function getTaskOverview($taskId)
    {
        $task = DB::table('tasks')->where('id', $taskId)->first();
        if (!$task) return null;

        $group = DB::table('groups')->where('id', $task->group_id)->first();

        $subtasks = DB::table('subtasks')->where('task_id', $taskId)->get();
        $totalSubtasks = $subtasks->count();
        $completedSubtasks = 0;

        foreach ($subtasks as $st) {
            $progress = DB::table('subtask_progress')
                ->where('subtask_id', $st->id)
                ->orderBy('updated_at', 'desc')
                ->first();
            if ($progress && $progress->progress >= 100) {
                $completedSubtasks++;
            }
        }

        $progressPercent = $totalSubtasks > 0 ? round(($completedSubtasks / $totalSubtasks) * 100) : 0;

        $members = DB::table('group_members')
            ->join('profiles', 'profiles.id', '=', 'group_members.user_id')
            ->where('group_members.group_id', $task->group_id)
            ->select('profiles.id', 'profiles.full_name as name', 'profiles.avatar_url as avatar')
            ->get();

        $countdown = $task->due_date
            ? (int) Carbon::now()->diffInDays(Carbon::parse($task->due_date), false)
            : 0;

        $dateRange = '';
        if ($task->start_date && $task->due_date) {
            $dateRange = Carbon::parse($task->start_date)->format('d M') . ' - ' . Carbon::parse($task->due_date)->format('d M');
        }

        $contributorCounts = [];
        foreach ($members as $m) {
            $doneCount = DB::table('subtask_progress')
                ->join('subtasks', 'subtasks.id', '=', 'subtask_progress.subtask_id')
                ->where('subtasks.task_id', $taskId)
                ->where('subtask_progress.user_id', $m->id)
                ->where('subtask_progress.progress', '>=', 100)
                ->count();
            $contributorCounts[$m->name] = $doneCount;
        }
        arsort($contributorCounts);
        $topContributors = implode(', ', array_slice(array_keys($contributorCounts), 0, 2));

        $recentProgress = DB::table('subtask_progress')
            ->join('subtasks', 'subtasks.id', '=', 'subtask_progress.subtask_id')
            ->join('profiles', 'profiles.id', '=', 'subtask_progress.user_id')
            ->where('subtasks.task_id', $taskId)
            ->orderBy('subtask_progress.updated_at', 'desc')
            ->select('profiles.full_name', 'subtasks.title', 'subtask_progress.progress')
            ->first();

        $recentUpdate = $recentProgress
            ? $recentProgress->full_name . ' ' . ($recentProgress->progress >= 100 ? 'done' : 'in progress') . ' "' . $recentProgress->title . '"'
            : 'No recent activity';

        return [
            'id' => $task->id,
            'task_name' => $task->title,
            'group_name' => $group->name ?? '',
            'class_name' => $group->class_name ?? '',
            'progress' => (int)$progressPercent,
            'completed_tasks' => $completedSubtasks,
            'unfinished_tasks' => $totalSubtasks - $completedSubtasks,
            'countdown' => $countdown,
            'date_range' => $dateRange,
            'subject' => $task->subject ?? $group->course_name ?? '',
            'top_contributors' => $topContributors,
            'recent_update' => $recentUpdate,
            'members' => $members->map(function ($m) use ($taskId) {
                $memberSubtasks = DB::table('subtasks')
                    ->leftJoin('subtask_progress', function ($join) use ($m) {
                        $join->on('subtask_progress.subtask_id', '=', 'subtasks.id')
                             ->where('subtask_progress.user_id', '=', $m->id);
                    })
                    ->where('subtasks.task_id', $taskId)
                    ->whereNotNull('subtask_progress.id')
                    ->select('subtasks.title as name', 'subtask_progress.progress')
                    ->get()
                    ->map(function ($s) {
                        $s->status = $s->progress >= 100 ? 'Done' : ($s->progress > 0 ? 'In Progress' : 'To Do');
                        unset($s->progress);
                        return $s;
                    })
                    ->toArray();

                return [
                    'name' => $m->name,
                    'avatar_url' => $m->avatar,
                    'task_count' => count($memberSubtasks) . ' SubTask',
                    'subtasks' => $memberSubtasks,
                ];
            })->toArray(),
        ];
    }

    public function updateClasses($lecturerId, array $classNames)
    {
        LecturerClass::where('lecturer_id', $lecturerId)->delete();

        foreach ($classNames as $className) {
            LecturerClass::create([
                'id' => (string) \Illuminate\Support\Str::uuid(),
                'lecturer_id' => $lecturerId,
                'class_name' => $className,
            ]);
        }

        return $this->getClasses($lecturerId);
    }
}
