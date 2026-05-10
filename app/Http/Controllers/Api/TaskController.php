<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TaskFile;
use App\Models\TaskLink;
use App\Services\DashboardService;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TaskController extends Controller
{
    public function getByUser($user_id, TaskService $service)
    {
        $tasks = Cache::tags(["user_tasks_{$user_id}"])->remember("tasks_user_{$user_id}", 600, function () use ($user_id, $service) {
            return $service->getTasksByUser($user_id);
        });

        return response()->json([
            "tasks" => $tasks,
        ]);
    }

    public function detail($task_id, $user_id, TaskService $service)
    {
        $data = Cache::tags(["task_{$task_id}", "user_tasks_{$user_id}"])->remember("task_detail_{$task_id}_{$user_id}", 600, function () use ($task_id, $user_id, $service) {
            return $service->getTaskDetail($task_id, $user_id);
        });

        return response()->json($data);
    }

    public function store(Request $request, TaskService $service)
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'subject' => 'nullable|string',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'is_group' => 'nullable|boolean',
            'group_id' => 'nullable|uuid|exists:groups,id',
            'status' => 'nullable|string',
            'priority' => 'nullable|string',
            'link' => 'nullable|url',
            'link_label' => 'nullable|string',
        ]);

        $task = $service->createTask($request);

        $userId = auth()->id();
        Cache::tags(["user_tasks_{$userId}"])->flush();
        Cache::tags(["dashboard_{$userId}"])->flush();

        Cache::tags(["user_tasks_{$userId}"])->remember("tasks_user_{$userId}", 600, fn() => $service->getTasksByUser($userId));
        Cache::tags(["dashboard_{$userId}"])->remember("dashboard_{$userId}", 300, fn() => app(DashboardService::class)->getDashboard($userId));

        return response()->json([
            "message" => "Task created successfully",
            "data" => $task,
        ], 201);
    }

    public function update(Request $request, $id, TaskService $service)
    {
        $request->validate([
            'title' => 'nullable|string|max:150',
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'subject' => 'nullable|string',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'is_group' => 'nullable|boolean',
            'group_id' => 'nullable|uuid|exists:groups,id',
            'status' => 'nullable|string',
            'priority' => 'nullable|string',
        ]);

        $task = $service->updateTask($id, $request->all());

        $userId = auth()->id();
        Cache::tags(["task_{$id}"])->flush();
        Cache::tags(["user_tasks_{$userId}"])->flush();
        Cache::tags(["dashboard_{$userId}"])->flush();

        Cache::tags(["task_{$id}", "user_tasks_{$userId}"])->remember("task_detail_{$id}_{$userId}", 600, fn() => $service->getTaskDetail($id, $userId));
        Cache::tags(["user_tasks_{$userId}"])->remember("tasks_user_{$userId}", 600, fn() => $service->getTasksByUser($userId));
        Cache::tags(["dashboard_{$userId}"])->remember("dashboard_{$userId}", 300, fn() => app(DashboardService::class)->getDashboard($userId));

        return response()->json([
            "message" => "Task updated successfully",
            "data" => $task,
        ]);
    }

    public function destroy($id, TaskService $service)
    {
        $service->deleteTask($id);

        $userId = auth()->id();
        Cache::tags(["task_{$id}"])->flush();
        Cache::tags(["user_tasks_{$userId}"])->flush();
        Cache::tags(["dashboard_{$userId}"])->flush();

        Cache::tags(["user_tasks_{$userId}"])->remember("tasks_user_{$userId}", 600, fn() => $service->getTasksByUser($userId));
        Cache::tags(["dashboard_{$userId}"])->remember("dashboard_{$userId}", 300, fn() => app(DashboardService::class)->getDashboard($userId));

        return response()->json(null, 204);
    }

    public function links($taskId)
    {
        $links = Cache::tags(["task_{$taskId}"])->remember("task_links_{$taskId}", 600, function () use ($taskId) {
            return TaskLink::where('task_id', $taskId)->get();
        });

        return response()->json($links);
    }

    public function storeLink(Request $request, $taskId)
    {
        $request->validate([
            'url' => 'required|url',
            'label' => 'nullable|string',
        ]);

        $link = TaskLink::create([
            'task_id' => $taskId,
            'url' => $request->url,
            'label' => $request->label,
        ]);

        Cache::tags(["task_{$taskId}"])->flush();
        Cache::tags(["task_{$taskId}"])->remember("task_links_{$taskId}", 600, fn() => TaskLink::where('task_id', $taskId)->get());

        return response()->json($link, 201);
    }

    public function destroyAllLinks($taskId)
    {
        TaskLink::where('task_id', $taskId)->delete();

        Cache::tags(["task_{$taskId}"])->flush();
        Cache::tags(["task_{$taskId}"])->remember("task_links_{$taskId}", 600, fn() => TaskLink::where('task_id', $taskId)->get());

        return response()->json(null, 204);
    }

    public function destroyLink($id)
    {
        $link = TaskLink::findOrFail($id);
        $taskId = $link->task_id;
        $link->delete();

        Cache::tags(["task_{$taskId}"])->flush();
        Cache::tags(["task_{$taskId}"])->remember("task_links_{$taskId}", 600, fn() => TaskLink::where('task_id', $taskId)->get());

        return response()->json(null, 204);
    }

    public function files($taskId)
    {
        $files = Cache::tags(["task_{$taskId}"])->remember("task_files_{$taskId}", 600, function () use ($taskId) {
            return TaskFile::where('task_id', $taskId)->get();
        });

        return response()->json($files);
    }

    public function storeFile(Request $request, $taskId)
    {
        $request->validate([
            'file_name' => 'required|string',
            'file_path' => 'required|string',
            'file_type' => 'nullable|string',
            'file_size' => 'nullable|integer',
        ]);

        $file = TaskFile::create([
            'task_id' => $taskId,
            'file_name' => $request->file_name,
            'file_path' => $request->file_path,
            'file_type' => $request->file_type,
            'file_size' => $request->file_size ?? 0,
            'uploaded_by' => auth()->id(),
        ]);

        $task = \App\Models\Task::find($taskId);
        if ($task && $task->due_date && \Carbon\Carbon::now()->gt($task->due_date)) {
            if ($task->is_group && $task->group_id) {
                $group = \App\Models\Group::find($task->group_id);
                if ($group) {
                    app(\App\Services\NotificationService::class)->notifyLecturersByGroup(
                        $task->group_id,
                        "Pengumpulan Terlambat",
                        "Group '{$group->name}' mengumpulkan tugas '{$task->title}' untuk mata kuliah {$task->subject} melewati batas tenggat waktu.",
                        "task_submission",
                        $taskId
                    );
                }
            }
        }

        Cache::tags(["task_{$taskId}"])->flush();
        Cache::tags(["task_{$taskId}"])->remember("task_files_{$taskId}", 600, fn() => TaskFile::where('task_id', $taskId)->get());

        return response()->json($file, 201);
    }

    public function destroyAllFiles($taskId)
    {
        TaskFile::where('task_id', $taskId)->delete();

        Cache::tags(["task_{$taskId}"])->flush();
        Cache::tags(["task_{$taskId}"])->remember("task_files_{$taskId}", 600, fn() => TaskFile::where('task_id', $taskId)->get());

        return response()->json(null, 204);
    }

    public function destroyFile($id)
    {
        $file = TaskFile::findOrFail($id);
        $taskId = $file->task_id;
        $file->delete();

        Cache::tags(["task_{$taskId}"])->flush();
        Cache::tags(["task_{$taskId}"])->remember("task_files_{$taskId}", 600, fn() => TaskFile::where('task_id', $taskId)->get());

        return response()->json(null, 204);
    }
}
