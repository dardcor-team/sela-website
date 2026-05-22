<?php

namespace App\Services;

use App\Models\Notification;
use App\Services\FcmService;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    public function getNotifications($userId, $isRead = null, $perPage = null)
    {
        $query = Notification::where('user_id', $userId);

        if ($isRead !== null) {
            $isReadBool = filter_var($isRead, FILTER_VALIDATE_BOOLEAN);
            $query->where('is_read', $isReadBool);
        }

        $query->orderBy('created_at', 'desc');

        if ($perPage) {
            return $query->paginate((int) $perPage);
        }

        return $query->get();
    }

    public function createNotification(array $data)
    {
        $notification = Notification::create([
            'user_id' => $data['user_id'],
            'title' => $data['title'],
            'message' => $data['message'],
            'type' => $data['type'] ?? 'system',
            'related_id' => $data['related_id'] ?? null,
            'is_read' => false,
            'created_at' => now(),
        ]);

        try {
            $fcmService = app(FcmService::class);
            $fcmService->sendToUser(
                $data['user_id'],
                $data['title'],
                $data['message'],
                ['notification_id' => $notification->id, 'type' => $data['type'] ?? 'system']
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('FCM push failed: ' . $e->getMessage());
        }

        return $notification;
    }

    public function notifyLecturersByGroup($groupId, $title, $message, $type, $relatedId = null)
    {
        $group = \App\Models\Group::find($groupId);
        if (!$group || !$group->class_name) return;

        $lecturerIds = DB::table('lecturer_classes')->where('class_name', $group->class_name)->pluck('lecturer_id');

        foreach ($lecturerIds as $lecturerId) {
            $this->createNotification([
                'user_id' => $lecturerId,
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'related_id' => $relatedId,
            ]);
        }
    }

    public function markAsRead($id)
    {
        return Notification::where('id', $id)->update(['is_read' => true]);
    }

    public function markMultipleAsRead(array $ids)
    {
        return Notification::whereIn('id', $ids)->update(['is_read' => true]);
    }

    public function markAllAsRead($userId)
    {
        return Notification::where('user_id', $userId)->update(['is_read' => true]);
    }

    public function deleteNotification($id)
    {
        return Notification::where('id', $id)->delete();
    }

    public function deleteMultipleNotifications(array $ids)
    {
        return Notification::whereIn('id', $ids)->delete();
    }
}
