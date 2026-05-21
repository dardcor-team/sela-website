<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LecturerService;
use Illuminate\Http\Request;

class LecturerController extends Controller
{
    public function classes(Request $request, LecturerService $service)
    {
        $userId = $request->user()->id;

        $classes = $service->getClasses($userId);

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

        return response()->json(['data' => $classes, 'message' => 'Classes updated successfully']);
    }

    public function classTasks($id, LecturerService $service)
    {
        $tasks = $service->getClassTasks($id);

        return response()->json(['data' => $tasks]);
    }

    public function taskOverview($taskId, LecturerService $service)
    {
        $overview = $service->getTaskOverview($taskId);

        if (!$overview) {
            return response()->json(['message' => 'Task not found'], 404);
        }
        return response()->json(['data' => $overview]);
    }
}
