<?php
namespace Tests\Unit\Services;

use Tests\TestCase;
use Tests\Traits\ActsAsAuthenticatedUser;
use App\Services\SubTaskService;
use App\Models\Task;
use App\Models\Subtask;
use App\Models\SubtaskProgress;

class SubTaskServiceTest extends TestCase
{
    use ActsAsAuthenticatedUser;

    private SubTaskService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SubTaskService();
    }

    public function test_create_subtask_creates_record(): void
    {
        $user = $this->actingAsUser();
        $task = Task::factory()->create();

        $request = new \Illuminate\Http\Request();
        $request->merge([
            'title' => 'Subtask 1',
            'weight' => 50,
            'description' => 'Test desc'
        ]);

        $subtask = $this->service->createSubtask($task->id, $request);

        $this->assertInstanceOf(Subtask::class, $subtask);
        $this->assertEquals('Subtask 1', $subtask->title);
    }

    public function test_update_progress_upserts_record(): void
    {
        $user = $this->actingAsUser();
        $subtask = Subtask::factory()->create();

        $progress = $this->service->updateProgress($subtask->id, $user->id, 100);
        $this->assertInstanceOf(SubtaskProgress::class, $progress);
        $this->assertEquals(100, $progress->progress);
    }

    public function test_delete_removes_subtask(): void
    {
        $subtask = Subtask::factory()->create();
        $this->service->delete($subtask->id);

        $this->assertDatabaseMissing('subtasks', ['id' => $subtask->id]);
    }
}
