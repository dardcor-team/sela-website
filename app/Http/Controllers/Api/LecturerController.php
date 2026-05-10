<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LecturerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LecturerController extends Controller
{
    public function classes(Request $request, LecturerService $service)
    {
        $userId = $request->user()->id;

        $classes = Cache::tags(["lecturer_{$userId}"])->remember("lecturer_classes_{$userId}", 600, function () use ($userId, $service) {
            return $service->getClasses($userId);
        });

        return response()->json(['data' => $classes]);
    }

    public function updateClasses(Request $request, LecturerService $service)
    {
        $request->validate([
            'classes' => 'required|array',
            'classes.*' => 'string'
        ]);

        $userId = $request->user()->id;
        $classes = $service->updateClasses($userId, $request->classes);

        Cache::tags(["lecturer_{$userId}"])->flush();

        return response()->json(['data' => $classes, 'message' => 'Classes updated successfully']);
    }

    public function classTasks($id, LecturerService $service)
    {
        $tasks = Cache::tags(["lecturer_class_{$id}"])->remember("lecturer_class_tasks_{$id}", 600, function () use ($id, $service) {
            return $service->getClassTasks($id);
        });

        return response()->json(['data' => $tasks]);
    }

    public function taskOverview($taskId, LecturerService $service)
    {
        $overview = Cache::tags(["task_{$taskId}"])->remember("lecturer_task_overview_{$taskId}", 600, function () use ($taskId, $service) {
            return $service->getTaskOverview($taskId);
        });

        if (!$overview) {
            return response()->json(['message' => 'Task not found'], 404);
        }
        return response()->json(['data' => $overview]);
    }
}
