<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    public function uploadAvatar(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|image|max:5120',
        ]);

        $disk = env('FILESYSTEM_DISK', 'public');
        $path = $request->file('file')->store('avatars', $disk);

        return response()->json([
            'message' => 'Avatar uploaded successfully',
            'url' => Storage::disk($disk)->url($path),
        ]);
    }

    public function uploadTaskFile(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:20480',
        ]);

        $disk = env('FILESYSTEM_DISK', 'public');
        $path = $request->file('file')->store('task-files', $disk);

        return response()->json([
            'message' => 'Task file uploaded successfully',
            'url' => Storage::disk($disk)->url($path),
        ]);
    }
}
