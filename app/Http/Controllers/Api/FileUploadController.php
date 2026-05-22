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

        if (!$path) {
            return response()->json(['message' => 'Failed to upload avatar to storage'], 500);
        }

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

        if (!$path) {
            return response()->json(['message' => 'Failed to upload task file to storage'], 500);
        }

        return response()->json([
            'message' => 'Task file uploaded successfully',
            'url' => Storage::disk($disk)->url($path),
            'path' => $path, // Return the raw path so Flutter can save it and use it for deletion
        ]);
    }

    public function deleteTaskFile(Request $request): JsonResponse
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        $disk = env('FILESYSTEM_DISK', 'public');
        $path = $request->input('path');

        // If the frontend sends the full URL, extract the relative path
        $baseUrl = Storage::disk($disk)->url('');
        if (str_starts_with($path, $baseUrl)) {
            $path = str_replace($baseUrl, '', $path);
        }

        if (Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
            return response()->json(['message' => 'File deleted successfully']);
        }

        return response()->json(['message' => 'File not found'], 404);
    }
}
