<?php

namespace Tests\Feature\Api\Task;

use App\Models\Task;
use App\Models\TaskLink;
use Tests\TestCase;
use Tests\Traits\ActsAsAuthenticatedUser;

class TaskLinksTest extends TestCase
{
    use ActsAsAuthenticatedUser;

    public function test_get_task_links_requires_auth(): void
    {
        $this->getJson('/api/tasks/some-id/links')
            ->assertUnauthorized();
    }

    public function test_get_task_links_returns_success(): void
    {
        $user = $this->actingAsUser();
        $task = Task::factory()->create(['created_by' => $user->id]);
        TaskLink::factory()->count(2)->create(['task_id' => $task->id]);

        $this->getJson("/api/tasks/{$task->id}/links")
            ->assertOk();
    }

    public function test_create_task_link_requires_auth(): void
    {
        $this->postJson('/api/tasks/some-id/links', [])
            ->assertUnauthorized();
    }

    public function test_create_task_link_validates_input(): void
    {
        $user = $this->actingAsUser();
        $task = Task::factory()->create(['created_by' => $user->id]);

        $this->postJson("/api/tasks/{$task->id}/links", [
            'url' => 'not-a-url'
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['url']);
    }

    public function test_create_task_link_returns_success(): void
    {
        $user = $this->actingAsUser();
        $task = Task::factory()->create(['created_by' => $user->id]);

        $this->postJson("/api/tasks/{$task->id}/links", [
            'url' => 'https://example.com'
        ])
            ->assertCreated();
    }

    public function test_delete_all_task_links_requires_auth(): void
    {
        $this->deleteJson('/api/tasks/some-id/links')
            ->assertUnauthorized();
    }

    public function test_delete_all_task_links_returns_success(): void
    {
        $user = $this->actingAsUser();
        $task = Task::factory()->create(['created_by' => $user->id]);
        TaskLink::factory()->count(2)->create(['task_id' => $task->id]);

        $this->deleteJson("/api/tasks/{$task->id}/links")
            ->assertNoContent();
    }

    public function test_delete_single_task_link_requires_auth(): void
    {
        $this->deleteJson('/api/task-links/some-id')
            ->assertUnauthorized();
    }

    public function test_delete_single_task_link_returns_success(): void
    {
        $user = $this->actingAsUser();
        $task = Task::factory()->create(['created_by' => $user->id]);
        $link = TaskLink::factory()->create(['task_id' => $task->id]);

        $this->deleteJson("/api/task-links/{$link->id}")
            ->assertNoContent();
    }
}
