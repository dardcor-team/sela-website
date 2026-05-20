<?php

namespace Tests\Feature\Api\Lecturer;

use App\Models\LecturerClass;
use App\Models\SchoolClass;
use App\Models\Task;
use App\Models\User;
use Tests\TestCase;
use Tests\Traits\ActsAsAuthenticatedUser;

class LecturerTest extends TestCase
{
    use ActsAsAuthenticatedUser;

    public function test_get_classes_requires_auth(): void
    {
        $this->getJson('/api/lecturer/classes')
            ->assertUnauthorized();
    }

    public function test_get_classes_returns_success(): void
    {
        $user = $this->actingAsUser([], ['role' => 'lecturer']);
        \App\Models\LecturerClass::factory()->create(['lecturer_id' => $user->id]);

        $this->getJson('/api/lecturer/classes')
            ->assertOk();
    }

    public function test_update_classes_requires_auth(): void
    {
        $this->putJson('/api/lecturer/classes', [])
            ->assertUnauthorized();
    }

    public function test_update_classes_validates_input(): void
    {
        $user = $this->actingAsUser([], ['role' => 'lecturer']);

        $this->putJson('/api/lecturer/classes', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['classes']);
    }

    public function test_update_classes_returns_success(): void
    {
        $user = $this->actingAsUser([], ['role' => 'lecturer']);

        $this->putJson('/api/lecturer/classes', [
            'classes' => ['D4-IT-A']
        ])
            ->assertOk();
    }

    public function test_get_class_tasks_requires_auth(): void
    {
        $this->getJson('/api/lecturer/classes/1/tasks')
            ->assertUnauthorized();
    }

    public function test_get_class_tasks_returns_success(): void
    {
        $user = $this->actingAsUser([], ['role' => 'lecturer']);
        $class = \App\Models\LecturerClass::factory()->create(['lecturer_id' => $user->id]);

        $this->getJson("/api/lecturer/classes/{$class->id}/tasks")
            ->assertOk();
        $this->actingAs($user, 'sanctum');
        $lecturerClass = LecturerClass::factory()->create(['lecturer_id' => $user->id, 'class_name' => 'D4-IT-A']);

        $this->getJson("/api/lecturer/classes/{$lecturerClass->id}/tasks")
            ->assertOk();
    }

    public function test_get_task_overview_requires_auth(): void
    {
        $this->getJson('/api/lecturer/tasks/some-id/overview')
            ->assertUnauthorized();
    }

    public function test_get_task_overview_returns_success(): void
    {
        $user = User::factory()->lecturer()->create();
        $this->actingAs($user, 'sanctum');
        $task = Task::factory()->create(['created_by' => $user->id]);

        $this->getJson("/api/lecturer/tasks/{$task->id}/overview")
            ->assertOk();
    }
}
