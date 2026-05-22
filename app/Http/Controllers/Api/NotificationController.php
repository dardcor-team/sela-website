<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request, NotificationService $service): JsonResponse
    {
        $userId = $request->query('user_id') ?? $request->user()->id;
        $isRead = $request->query('is_read');
        $perPage = $request->query('per_page');

        $notifications = $service->getNotifications($userId, $isRead, $perPage);

        if ($perPage) {
            return response()->json($notifications);
        }

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

        return response()->json([
            'message' => 'Notification created successfully',
            'data' => $notification
        ], 201);
    }

    public function markAsRead($id, NotificationService $service): JsonResponse
    {
        $service->markAsRead($id);

        return response()->json(['message' => 'Notification marked as read']);
    }

    public function markMultipleAsRead(Request $request, NotificationService $service): JsonResponse
    {
        $request->validate(['ids' => 'required|array']);
        $service->markMultipleAsRead($request->ids);

        return response()->json(['message' => 'Notifications marked as read']);
    }

    public function markAllAsRead(Request $request, NotificationService $service): JsonResponse
    {
        $service->markAllAsRead($request->user()->id);

        return response()->json(['message' => 'All notifications marked as read']);
    }

    public function destroy($id, NotificationService $service): JsonResponse
    {
        $service->deleteNotification($id);

        return response()->json(['message' => 'Notification deleted']);
    }

    public function destroyMultiple(Request $request, NotificationService $service): JsonResponse
    {
        $request->validate(['ids' => 'required|array']);
        $service->deleteMultipleNotifications($request->ids);

        return response()->json(['message' => 'Notifications deleted']);
    }
}
