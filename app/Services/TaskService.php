<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TaskFile;
use App\Models\TaskLink;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;

class TaskService
{
    public function getTasksByUser($user_id)
    {
        $tasks = DB::table('group_members')
            ->join('groups', 'groups.id', '=', 'group_members.group_id')
            ->join('tasks', 'tasks.group_id', '=', 'groups.id')
            ->where('group_members.user_id', $user_id)
            ->select(
                'tasks.id',
                'tasks.title',
                'tasks.description',
                'tasks.start_date',
                'tasks.due_date',
                'tasks.status',
                'tasks.priority',
                'tasks.is_group',
                'tasks.created_by',
                'groups.name as group_name',
                'groups.class_name',
                'groups.course_name',
                'groups.id as group_id'
            )
            ->get();

        foreach ($tasks as $task) {
            $total = DB::table('subtasks')
                ->where('task_id', $task->id)
                ->count();

            $progressEntries = DB::table('subtask_progress')
                ->join('subtasks', 'subtasks.id', '=', 'subtask_progress.subtask_id')
                ->join('group_members', function ($join) use ($task) {
                    $join->on('group_members.user_id', '=', 'subtask_progress.user_id')
                         ->where('group_members.group_id', '=', $task->group_id);
                })
                ->where('subtasks.task_id', $task->id)
                ->avg('subtask_progress.progress');

            $task->progress = $total > 0 ? round($progressEntries ?? 0) : 0;

            $task->members = DB::table('group_members')
                ->join('profiles', 'profiles.id', '=', 'group_members.user_id')
                ->where('group_members.group_id', $task->group_id)
                ->select('profiles.id', 'profiles.full_name', 'profiles.avatar_url', 'profiles.username')
                ->limit(5)
                ->get();
        }

        $personalTasks = DB::table('tasks')
            ->where('created_by', $user_id)
            ->where(function ($q) {
                $q->where('is_group', false)->orWhereNull('group_id');
            })
            ->select(
                'tasks.id',
                'tasks.title',
                'tasks.due_date',
                'tasks.status',
                'tasks.priority',
                'tasks.is_group',
                'tasks.created_by'
            )
            ->get();

        foreach ($personalTasks as $task) {
            $task->group_name = null;
            $task->group_id = null;
            $task->progress = 0;
        }

        return $tasks->merge($personalTasks)->unique('id')->values();
    }

    public function getTaskDetail($task_id, $user_id)
    {
        $task = DB::table('tasks')
            ->leftJoin('groups', 'groups.id', '=', 'tasks.group_id')
            ->where('tasks.id', $task_id)
            ->select(
                'tasks.id',
                'tasks.title',
                'tasks.description',
                'tasks.category',
                'tasks.subject',
                'tasks.start_date',
                'tasks.due_date',
                'tasks.is_group',
                'tasks.status',
                'tasks.priority',
                'tasks.group_id',
                'groups.name as group_name',
                'groups.class_name',
                'groups.course_name'
            )
            ->first();

        $subtasks = DB::table('subtasks')
            ->where('task_id', $task_id)
            ->select('id', 'title', 'description', 'created_at')
            ->get();

        foreach ($subtasks as $subtask) {
            $subtask->progress_entries = DB::table('subtask_progress')
                ->join('profiles', 'profiles.id', '=', 'subtask_progress.user_id')
                ->where('subtask_progress.subtask_id', $subtask->id)
                ->select(
                    'subtask_progress.id',
                    'subtask_progress.user_id',
                    'profiles.username',
                    'profiles.full_name',
                    'subtask_progress.progress',
                    'subtask_progress.updated_at'
                )
                ->get();
        }

        $yourProgress = DB::table('subtask_progress')
            ->join('subtasks', 'subtasks.id', '=', 'subtask_progress.subtask_id')
            ->where('subtasks.task_id', $task_id)
            ->where('subtask_progress.user_id', $user_id)
            ->select(
                'subtasks.id',
                'subtasks.title',
                'subtask_progress.progress'
            )
            ->get();

        $members = collect();
        if ($task && $task->group_id) {
            $members = DB::table('group_members')
                ->join('profiles', 'profiles.id', '=', 'group_members.user_id')
                ->where('group_members.group_id', $task->group_id)
                ->select(
                    'profiles.id',
                    'profiles.username',
                    'profiles.full_name',
                    'profiles.avatar_url',
                    'group_members.role'
                )
                ->get();

            foreach ($members as $member) {
                $member->subtasks = DB::table('subtask_progress')
                    ->join('subtasks', 'subtasks.id', '=', 'subtask_progress.subtask_id')
                    ->where('subtasks.task_id', $task_id)
                    ->where('subtask_progress.user_id', $member->id)
                    ->select(
                        'subtasks.title',
                        'subtask_progress.progress'
                    )
                    ->get();
            }
        }

        $links = TaskLink::where('task_id', $task_id)->get();
        $files = TaskFile::where('task_id', $task_id)->get();

        $total = $subtasks->count();
        $avgProgress = $total > 0
            ? round(DB::table('subtask_progress')
                ->join('subtasks', 'subtasks.id', '=', 'subtask_progress.subtask_id')
                ->leftJoin('group_members', function ($join) use ($task) {
                    $join->on('group_members.user_id', '=', 'subtask_progress.user_id')
                         ->where('group_members.group_id', '=', $task->group_id);
                })
                ->where('subtasks.task_id', $task_id)
                ->where(function ($q) use ($task) {
                    if ($task->is_group) {
                        $q->whereNotNull('group_members.id');
                    }
                })
                ->avg('subtask_progress.progress') ?? 0)
            : 0;

        return [
            "task" => [
                "id" => $task->id,
                "title" => $task->title,
                "description" => $task->description,
                "category" => $task->category,
                "subject" => $task->subject,
                "start_date" => $task->start_date,
                "due_date" => $task->due_date,
                "is_group" => $task->is_group,
                "status" => $task->status,
                "priority" => $task->priority,
                "group_name" => $task->group_name,
                "class_name" => $task->class_name,
                "course_name" => $task->course_name,
                "progress" => $avgProgress,
            ],
            "subtasks" => $subtasks,
            "your_progress" => $yourProgress,
            "members_progress" => $members,
            "links" => $links,
            "files" => $files,
        ];
    }

    public function createTask($request)
    {
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'subject' => $request->subject,
            'start_date' => $request->start_date ?? now(),
            'due_date' => $request->due_date,
            'is_group' => $request->is_group ?? false,
            'group_id' => $request->group_id,
            'created_by' => auth()->id(),
            'status' => $request->status ?? 'To Do',
            'priority' => $request->priority ?? 'Medium',
        ]);

        if ($request->link) {
            TaskLink::create([
                'task_id' => $task->id,
                'url' => $request->link,
                'label' => $request->link_label,
            ]);
        }

        try {
            $notificationService = app(NotificationService::class);
            if (!$task->is_group) {
                $notificationService->createNotification([
                    'user_id' => auth()->id(),
                    'title' => 'Tugas Baru Dibuat',
                    'message' => 'Task "' . $task->title . '" berhasil ditambahkan.',
                    'type' => 'task',
                    'related_id' => $task->id,
                ]);
            } else {
                $groupMembers = \App\Models\GroupMember::where('group_id', $task->group_id)->get();

                $group = \App\Models\Group::find($task->group_id);
                $groupName = $group ? $group->name : 'Grup';

                foreach ($groupMembers as $member) {
                    $notificationService->createNotification([
                        'user_id' => $member->user_id,
                        'title' => 'Tugas Grup Baru',
                        'message' => 'Tugas baru "' . $task->title . '" ditambahkan di grup ' . $groupName . '.',
                        'type' => 'group_task',
                        'related_id' => $task->id,
                    ]);
                }

                $notificationService->notifyLecturersByGroup(
                    $task->group_id,
                    'Tugas Baru di Kelas',
                    'Tugas baru "' . $task->title . '" ditambahkan di grup ' . $groupName . '.',
                    'lecturer_task',
                    $task->id
                );
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Task FCM failed: ' . $e->getMessage());
        }

        return $task;
    }

    public function updateTask($id, array $data)
    {
        $task = Task::findOrFail($id);
        $task->update($data);
        return $task;
    }

    public function deleteTask($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
    }
}
