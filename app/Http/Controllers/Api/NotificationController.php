<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{
    public function index(Request $request, NotificationService $service): JsonResponse
    {
        $userId = $request->query('user_id') ?? $request->user()->id;
        $isRead = $request->query('is_read');

        $cacheKey = "notifications_{$userId}" . ($isRead !== null ? "_read_{$isRead}" : '');

        $notifications = Cache::tags(["user_notifications_{$userId}"])->remember($cacheKey, 300, function () use ($userId, $isRead, $service) {
            return $service->getNotifications($userId, $isRead);
        });

        return response()->json([
            'notifications' => $notifications
        ]);
    }

    public function store(Request $request, NotificationService $service): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|uuid|exists:profiles,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|string',
            'related_id' => 'nullable|string'
        ]);

        $notification = $service->createNotification($request->all());

        Cache::tags(["user_notifications_{$request->user_id}"])->flush();

        return response()->json([
            'message' => 'Notification created successfully',
            'data' => $notification
        ], 201);
    }

    public function markAsRead($id, NotificationService $service): JsonResponse
    {
        $service->markAsRead($id);

        $userId = auth()->id();
        Cache::tags(["user_notifications_{$userId}"])->flush();

        return response()->json(['message' => 'Notification marked as read']);
    }

    public function markMultipleAsRead(Request $request, NotificationService $service): JsonResponse
    {
        $request->validate(['ids' => 'required|array']);
        $service->markMultipleAsRead($request->ids);

        $userId = auth()->id();
        Cache::tags(["user_notifications_{$userId}"])->flush();

        return response()->json(['message' => 'Notifications marked as read']);
    }

    public function markAllAsRead(Request $request, NotificationService $service): JsonResponse
    {
        $service->markAllAsRead($request->user()->id);

        Cache::tags(["user_notifications_{$request->user()->id}"])->flush();

        return response()->json(['message' => 'All notifications marked as read']);
    }

    public function destroy($id, NotificationService $service): JsonResponse
    {
        $service->deleteNotification($id);

        $userId = auth()->id();
        Cache::tags(["user_notifications_{$userId}"])->flush();

        return response()->json(['message' => 'Notification deleted']);
    }

    public function destroyMultiple(Request $request, NotificationService $service): JsonResponse
    {
        $request->validate(['ids' => 'required|array']);
        $service->deleteMultipleNotifications($request->ids);

        $userId = auth()->id();
        Cache::tags(["user_notifications_{$userId}"])->flush();

        return response()->json(['message' => 'Notifications deleted']);
    }
}
