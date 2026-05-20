<?php

namespace Tests\Feature\Api\Task;

use App\Models\Group;
use App\Models\Task;
use Tests\TestCase;
use Tests\Traits\ActsAsAuthenticatedUser;

class TaskCrudTest extends TestCase
{
    use ActsAsAuthenticatedUser;

    public function test_get_user_tasks_requires_auth(): void
    {
        $this->getJson('/api/tasks/user/some-id')
            ->assertUnauthorized();
    }

    public function test_get_user_tasks_returns_success(): void
    {
        $user = $this->actingAsUser();
        Task::factory()->count(3)->create(['created_by' => $user->id]);

        $response = $this->getJson("/api/tasks/user/{$user->id}");
        $response->assertOk();
        $this->assertIsArray($response->json());
    }

    public function test_get_task_detail_requires_auth(): void
    {
        $this->getJson('/api/tasks/some-task-id/detail/some-user-id')
            ->assertUnauthorized();
    }

    public function test_get_task_detail_returns_success(): void
    {
        $user = $this->actingAsUser();
        $task = Task::factory()->create(['created_by' => $user->id]);

        $this->getJson("/api/tasks/{$task->id}/detail/{$user->id}")
            ->assertOk();
    }

    public function test_create_task_requires_auth(): void
    {
        $this->postJson('/api/tasks', [])
            ->assertUnauthorized();
    }

    public function test_create_task_validates_input(): void
    {
        $this->actingAsUser();

        $this->postJson('/api/tasks', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['title']);
    }

    public function test_create_task_returns_success(): void
    {
        $this->actingAsUser();

        $payload = [
            'title' => 'Test Task',
            'description' => 'Test Description',
        ];

        $this->postJson('/api/tasks', $payload)
            ->assertCreated();
    }

    public function test_update_task_requires_auth(): void
    {
        $this->putJson('/api/tasks/some-id', [])
            ->assertUnauthorized();
    }

    public function test_update_task_returns_success(): void
    {
        $user = $this->actingAsUser();
        $task = Task::factory()->create(['created_by' => $user->id]);

        $payload = [
            'title' => 'Updated Title',
        ];

        $this->putJson("/api/tasks/{$task->id}", $payload)
            ->assertOk();
    }

    public function test_delete_task_requires_auth(): void
    {
        $this->deleteJson('/api/tasks/some-id')
            ->assertUnauthorized();
    }

    public function test_delete_task_returns_success(): void
    {
        $user = $this->actingAsUser();
        $task = Task::factory()->create(['created_by' => $user->id]);

        $this->deleteJson("/api/tasks/{$task->id}")
            ->assertNoContent();
    }
}
