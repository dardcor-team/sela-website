<?php
namespace Tests\Unit\Services;

use Tests\TestCase;
use Tests\Traits\ActsAsAuthenticatedUser;
use App\Services\UserService;
use App\Models\Profile;

class UserServiceTest extends TestCase
{
    use ActsAsAuthenticatedUser;

    private UserService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new UserService();
    }

    public function test_get_all_returns_profiles(): void
    {
        Profile::factory()->count(3)->create();
        
        $profiles = $this->service->getAll();
        $this->assertNotEmpty($profiles);
    }

    public function test_get_by_id_returns_profile(): void
    {
        $profile = Profile::factory()->create();
        $result = $this->service->getById($profile->id);
        
        $this->assertEquals($profile->id, $result->id);
    }
    
    public function test_update_profile_changes_data(): void
    {
        $profile = Profile::factory()->create();
        $updated = $this->service->update($profile, ['full_name' => 'Updated Name']);
        
        $this->assertEquals('Updated Name', $updated->full_name);
    }
}
