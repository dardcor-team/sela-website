<?php

namespace Tests\Feature\Api\Subtask;

use App\Models\Subtask;
use App\Models\Task;
use Tests\TestCase;
use Tests\Traits\ActsAsAuthenticatedUser;

class SubtaskTest extends TestCase
{
    use ActsAsAuthenticatedUser;

    public function test_create_subtask_requires_auth(): void
    {
        $this->postJson('/api/tasks/some-id/subtasks', [])
            ->assertUnauthorized();
    }

    public function test_create_subtask_returns_success(): void
    {
        $user = $this->actingAsUser();
        $task = Task::factory()->create(['created_by' => $user->id]);

        $this->postJson("/api/tasks/{$task->id}/subtasks", [
            'title' => 'Test Subtask'
        ])
            ->assertCreated();
    }

    public function test_update_subtask_progress_requires_auth(): void
    {
        $this->patchJson('/api/subtasks/some-id/progress', [])
            ->assertUnauthorized();
    }

    public function test_update_subtask_progress_returns_success(): void
    {
        $user = $this->actingAsUser();
        $task = Task::factory()->create(['created_by' => $user->id]);
        $subtask = Subtask::factory()->create(['task_id' => $task->id]);

        $this->patchJson("/api/subtasks/{$subtask->id}/progress", [
            'user_id' => $user->id,
            'progress' => 50
        ])
            ->assertOk();
    }

    public function test_delete_subtask_requires_auth(): void
    {
        $this->deleteJson('/api/subtasks/some-id')
            ->assertUnauthorized();
    }

    public function test_delete_subtask_returns_success(): void
    {
        $user = $this->actingAsUser();
        $task = Task::factory()->create(['created_by' => $user->id]);
        $subtask = Subtask::factory()->create(['task_id' => $task->id]);

        $this->deleteJson("/api/subtasks/{$subtask->id}")
            ->assertOk();
    }
}
