<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use Carbon\Carbon;
use App\Services\NotificationService;

class CheckTaskDeadlines extends Command
{
    protected $signature = 'tasks:check-deadlines';

    protected $description = 'Check for tasks that have just passed their deadline and notify lecturers';

    public function handle(NotificationService $notificationService)
    {
        $now = Carbon::now();
        $oneMinuteAgo = $now->copy()->subMinute();

        $tasks = Task::whereNotNull('due_date')
            ->where('due_date', '<=', $now)
            ->where('due_date', '>', $oneMinuteAgo)
            ->where('is_group', true)
            ->whereNotNull('group_id')
            ->get();

        foreach ($tasks as $task) {
            $group = \App\Models\Group::find($task->group_id);
            if ($group) {
                $notificationService->notifyLecturersByGroup(
                    $task->group_id,
                    "Tugas Jatuh Tenggat",
                    "Tugas '{$task->title}' untuk mata kuliah {$task->subject} sudah jatuh tenggat.",
                    "task_deadline",
                    $task->id
                );
            }
        }

        $this->info("Checked deadlines. Found and notified for {$tasks->count()} tasks.");
        return 0;
    }
}
