<?php
namespace Tests\Unit\Services;

use Tests\TestCase;
use Tests\Traits\ActsAsAuthenticatedUser;
use App\Services\TaskService;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TaskServiceTest extends TestCase
{
    use ActsAsAuthenticatedUser;

    private TaskService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TaskService();
    }

    public function test_get_tasks_by_user_returns_collection(): void
    {
        $user = $this->actingAsUser();
        $task = Task::factory()->create(['created_by' => $user->id]);

        $tasks = $this->service->getTasksByUser($user->id);
        $this->assertNotEmpty($tasks);
    }

    public function test_get_task_detail_returns_array(): void
    {
        $user = $this->actingAsUser();
        $task = Task::factory()->create(['created_by' => $user->id]);

        $detail = $this->service->getTaskDetail($task->id, $user->id);
        $this->assertIsArray($detail);
        $this->assertEquals($task->id, $detail['task']['id']);
    }

    public function test_create_task_creates_record_and_returns_it(): void
    {
        $user = $this->actingAsUser();
        $request = new \Illuminate\Http\Request();
        $request->merge([
            'title' => 'New Task',
            'category' => 'Test',
            'created_by' => $user->id
        ]);

        $task = $this->service->createTask($request);
        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('New Task', $task->title);
    }

    public function test_update_task_updates_and_returns_it(): void
    {
        $user = $this->actingAsUser();
        $task = Task::factory()->create(['created_by' => $user->id]);

        $updatedTask = $this->service->updateTask($task->id, ['title' => 'Updated Title']);
        $this->assertEquals('Updated Title', $updatedTask->title);
    }

    public function test_delete_task_removes_record(): void
    {
        $user = $this->actingAsUser();
        $task = Task::factory()->create(['created_by' => $user->id]);

        $this->service->deleteTask($task->id);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
