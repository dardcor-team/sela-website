<?php

namespace App\Services;

use App\Models\Subtask;
use App\Models\SubtaskProgress;

class SubTaskService
{
    public function createSubtask($task_id, $request)
    {
        $subtask = Subtask::create([
            'title' => $request->title,
            'description' => $request->description,
            'task_id' => $task_id,
        ]);

        if ($request->user_id) {
            SubtaskProgress::create([
                'subtask_id' => $subtask->id,
                'user_id' => $request->user_id,
                'progress' => 0,
            ]);

            try {
                $notificationService = app(\App\Services\NotificationService::class);

                $task = \App\Models\Task::find($task_id);
                $taskTitle = $task ? $task->title : 'Tugas';

                $notificationService->createNotification([
                    'user_id' => $request->user_id,
                    'title' => 'Subtask Baru Ditugaskan',
                    'message' => 'Anda ditugaskan mengerjakan "' . $subtask->title . '" pada tugas ' . $taskTitle . '.',
                    'type' => 'subtask',
                    'related_id' => $subtask->id,
                ]);

                if ($task && $task->is_group && $task->group_id) {
                    $notificationService->notifyLecturersByGroup(
                        $task->group_id,
                        'Subtask Baru Dibuat',
                        'Subtask "' . $subtask->title . '" ditambahkan pada tugas "' . $taskTitle . '".',
                        'lecturer_subtask',
                        $subtask->id
                    );
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Subtask FCM failed: ' . $e->getMessage());
            }
        }

        return $subtask->load('progressEntries');
    }

    public function updateProgress($subtask_id, $user_id, $progress)
    {
        $entry = SubtaskProgress::where('subtask_id', $subtask_id)
            ->where('user_id', $user_id)
            ->first();

        if ($entry) {
            $entry->update(['progress' => $progress]);
        } else {
            $entry = SubtaskProgress::create([
                'subtask_id' => $subtask_id,
                'user_id' => $user_id,
                'progress' => $progress,
            ]);
        }

        try {
            $subtask = Subtask::find($subtask_id);
            $task = $subtask ? \App\Models\Task::find($subtask->task_id) : null;

            if ($task && $task->is_group && $task->group_id) {
                $profile = \App\Models\Profile::find($user_id);
                $userName = $profile ? ($profile->full_name ?? $profile->username) : 'Anggota';
                $notificationService = app(\App\Services\NotificationService::class);

                $notificationService->notifyLecturersByGroup(
                    $task->group_id,
                    'Progress Update',
                    $userName . ' memperbarui progress "' . $subtask->title . '" menjadi ' . $progress . '%.',
                    'lecturer_progress',
                    $subtask->id
                );
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Lecturer progress notification failed: ' . $e->getMessage());
        }

        return $entry;
    }

    public function delete($subtask_id)
    {
        $subtask = Subtask::findOrFail($subtask_id);
        $subtask->delete();

        return true;
    }
}
