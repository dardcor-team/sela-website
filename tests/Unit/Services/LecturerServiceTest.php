<?php
namespace Tests\Unit\Services;

use Tests\TestCase;
use Tests\Traits\ActsAsAuthenticatedUser;
use App\Services\LecturerService;

class LecturerServiceTest extends TestCase
{
    use ActsAsAuthenticatedUser;

    private LecturerService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new LecturerService();
    }

    public function test_get_classes_returns_collection(): void
    {
        $user = $this->actingAsUser();
        $classes = $this->service->getClasses($user->id);
        $this->assertIsArray($classes);
    }
    
    public function test_update_classes_replaces_old_classes(): void
    {
        $user = $this->actingAsUser();
        $this->service->updateClasses($user->id, ['Class A', 'Class B']);
        
        $this->assertDatabaseHas('lecturer_classes', [
            'lecturer_id' => $user->id,
            'class_name' => 'Class A'
        ]);
    }
}
