<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subtask;
use App\Services\DashboardService;
use App\Services\SubTaskService;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SubTaskController extends Controller
{
    public function store(Request $request, $task_id, SubTaskService $service)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:150',
                'description' => 'nullable|string',
                'user_id' => 'nullable|uuid|exists:profiles,id',
            ]);

            $data = $service->createSubtask($task_id, $request);

            $userId = auth()->id();
            Cache::tags(["task_{$task_id}"])->flush();
            Cache::tags(["user_tasks_{$userId}"])->flush();
            Cache::tags(["dashboard_{$userId}"])->flush();

            Cache::tags(["user_tasks_{$userId}"])->remember("tasks_user_{$userId}", 600, fn() => app(TaskService::class)->getTasksByUser($userId));
            Cache::tags(["dashboard_{$userId}"])->remember("dashboard_{$userId}", 300, fn() => app(DashboardService::class)->getDashboard($userId));

            return response()->json([
                "message" => "Subtask created",
                "data" => $data,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return response()->json([
                "message" => "Server error inside store: " . $e->getMessage(),
                "file" => $e->getFile(),
                "line" => $e->getLine(),
                "trace" => $e->getTraceAsString()
            ], 500);
        }
    }

    public function updateProgress(Request $request, $subtask_id, SubTaskService $service)
    {
        $request->validate([
            'progress' => 'required|integer|min:0|max:100',
            'user_id' => 'required|uuid|exists:profiles,id',
        ]);

        $subtask = Subtask::findOrFail($subtask_id);
        $taskId = $subtask->task_id;

        $data = $service->updateProgress($subtask_id, $request->user_id, $request->progress);

        if ($request->progress == 100) {
            $task = \App\Models\Task::find($taskId);
            if ($task && $task->due_date && \Carbon\Carbon::now()->gt($task->due_date)) {
                if ($task->is_group && $task->group_id) {
                    $group = \App\Models\Group::find($task->group_id);
                    if ($group) {
                        app(\App\Services\NotificationService::class)->notifyLecturersByGroup(
                            $task->group_id,
                            "Subtask Terlambat",
                            "Group '{$group->name}' menyelesaikan subtask dari '{$task->title}' untuk mata kuliah {$task->subject} melewati batas tenggat waktu.",
                            "task_submission",
                            $taskId
                        );
                    }
                }
            }
        }

        $userId = auth()->id();
        Cache::tags(["task_{$taskId}"])->flush();
        Cache::tags(["user_tasks_{$userId}"])->flush();
        Cache::tags(["dashboard_{$userId}"])->flush();

        Cache::tags(["user_tasks_{$userId}"])->remember("tasks_user_{$userId}", 600, fn() => app(TaskService::class)->getTasksByUser($userId));
        Cache::tags(["dashboard_{$userId}"])->remember("dashboard_{$userId}", 300, fn() => app(DashboardService::class)->getDashboard($userId));

        return response()->json([
            "message" => "Progress updated",
            "data" => $data,
        ]);
    }

    public function destroy($subtask_id, SubTaskService $service)
    {
        $subtask = Subtask::findOrFail($subtask_id);
        $taskId = $subtask->task_id;

        $service->delete($subtask_id);

        $userId = auth()->id();
        Cache::tags(["task_{$taskId}"])->flush();
        Cache::tags(["user_tasks_{$userId}"])->flush();
        Cache::tags(["dashboard_{$userId}"])->flush();

        Cache::tags(["user_tasks_{$userId}"])->remember("tasks_user_{$userId}", 600, fn() => app(TaskService::class)->getTasksByUser($userId));
        Cache::tags(["dashboard_{$userId}"])->remember("dashboard_{$userId}", 300, fn() => app(DashboardService::class)->getDashboard($userId));

        return response()->json([
            "message" => "Subtask deleted",
        ]);
    }
}
