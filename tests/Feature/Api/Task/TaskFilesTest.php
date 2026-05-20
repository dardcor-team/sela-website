<?php

namespace Tests\Feature\Api\Task;

use App\Models\Task;
use App\Models\TaskFile;
use Tests\TestCase;
use Tests\Traits\ActsAsAuthenticatedUser;

class TaskFilesTest extends TestCase
{
    use ActsAsAuthenticatedUser;

    public function test_get_task_files_requires_auth(): void
    {
        $this->getJson('/api/tasks/some-id/files')
            ->assertUnauthorized();
    }

    public function test_get_task_files_returns_success(): void
    {
        $user = $this->actingAsUser();
        $task = Task::factory()->create(['created_by' => $user->id]);
        TaskFile::factory()->count(2)->create(['task_id' => $task->id, 'uploaded_by' => $user->id]);

        $this->getJson("/api/tasks/{$task->id}/files")
            ->assertOk();
    }

    public function test_create_task_file_requires_auth(): void
    {
        $this->postJson('/api/tasks/some-id/files', [])
            ->assertUnauthorized();
    }

    public function test_create_task_file_validates_input(): void
    {
        $user = $this->actingAsUser();
        $task = Task::factory()->create(['created_by' => $user->id]);

        $this->postJson("/api/tasks/{$task->id}/files", [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['file_name', 'file_path']);
    }

    public function test_create_task_file_returns_success(): void
    {
        $user = $this->actingAsUser();
        $task = Task::factory()->create(['created_by' => $user->id]);

        $this->postJson("/api/tasks/{$task->id}/files", [
            'file_name' => 'document.pdf',
            'file_path' => 'tasks/document.pdf'
        ])
            ->assertCreated();
    }

    public function test_delete_all_task_files_requires_auth(): void
    {
        $this->deleteJson('/api/tasks/some-id/files')
            ->assertUnauthorized();
    }

    public function test_delete_all_task_files_returns_success(): void
    {
        $user = $this->actingAsUser();
        $task = Task::factory()->create(['created_by' => $user->id]);
        TaskFile::factory()->count(2)->create(['task_id' => $task->id, 'uploaded_by' => $user->id]);

        $this->deleteJson("/api/tasks/{$task->id}/files")
            ->assertNoContent();
    }

    public function test_delete_single_task_file_requires_auth(): void
    {
        $this->deleteJson('/api/task-files/some-id')
            ->assertUnauthorized();
    }

    public function test_delete_single_task_file_returns_success(): void
    {
        $user = $this->actingAsUser();
        $task = Task::factory()->create(['created_by' => $user->id]);
        $file = TaskFile::factory()->create(['task_id' => $task->id, 'uploaded_by' => $user->id]);

        $this->deleteJson("/api/task-files/{$file->id}")
            ->assertNoContent();
    }
}
