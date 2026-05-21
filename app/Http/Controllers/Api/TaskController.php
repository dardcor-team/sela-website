<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TaskFile;
use App\Models\TaskLink;
use App\Services\DashboardService;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function getByUser($user_id, TaskService $service)
    {
        $tasks = $service->getTasksByUser($user_id);

        return response()->json([
            "tasks" => $tasks,
        ]);
    }

    public function detail($task_id, $user_id, TaskService $service)
    {
        $data = $service->getTaskDetail($task_id, $user_id);

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

        return response()->json([
            "message" => "Task updated successfully",
            "data" => $task,
        ]);
    }

    public function destroy($id, TaskService $service)
    {
        $service->deleteTask($id);

        $userId = auth()->id();

        return response()->json(null, 204);
    }

    public function links($taskId)
    {
        $links = TaskLink::where('task_id', $taskId)->get();

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

        return response()->json($link, 201);
    }

    public function destroyAllLinks($taskId)
    {
        TaskLink::where('task_id', $taskId)->delete();

        return response()->json(null, 204);
    }

    public function destroyLink($id)
    {
        $link = TaskLink::findOrFail($id);
        $taskId = $link->task_id;
        $link->delete();

        return response()->json(null, 204);
    }

    public function files($taskId)
    {
        $files = TaskFile::where('task_id', $taskId)->get();

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

        return response()->json($file, 201);
    }

    public function destroyAllFiles($taskId)
    {
        TaskFile::where('task_id', $taskId)->delete();

        return response()->json(null, 204);
    }

    public function destroyFile($id)
    {
        $file = TaskFile::findOrFail($id);
        $taskId = $file->task_id;
        $file->delete();

        return response()->json(null, 204);
    }
}
