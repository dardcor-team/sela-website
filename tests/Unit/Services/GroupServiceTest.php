<?php
namespace Tests\Unit\Services;

use Tests\TestCase;
use Tests\Traits\ActsAsAuthenticatedUser;
use App\Services\GroupService;
use App\Models\Group;

class GroupServiceTest extends TestCase
{
    use ActsAsAuthenticatedUser;

    private GroupService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new GroupService();
    }

    public function test_get_groups_by_user_returns_collection(): void
    {
        // Note: ilike is PostgreSQL-specific, test may fail on SQLite
        $user = $this->actingAsUser();
        $group = Group::factory()->create(['created_by' => $user->id]);
        $group->members()->create(['user_id' => $user->id, 'role' => 'leader']);

        $groups = $this->service->getGroupsByUser($user->id);
        $this->assertNotEmpty($groups);
    }

    public function test_get_group_detail_returns_group_with_members(): void
    {
        $user = $this->actingAsUser();
        $group = Group::factory()->create(['created_by' => $user->id]);

        $detail = $this->service->getGroupDetail($group->id);
        $this->assertEquals($group->id, $detail['group']->id);
    }

    public function test_create_group_generates_code_and_adds_leader(): void
    {
        $user = $this->actingAsUser();
        $request = new \Illuminate\Http\Request();
        $request->merge([
            'course_name' => 'Math',
            'group_number' => 1,
            'member_limit' => 5,
            'lecture_code' => 'MATH101'
        ]);

        $group = $this->service->createGroup($request, $user->id);
        
        $this->assertStringContainsString('Math Kelompok 1', $group->name);
        $this->assertNotNull($group->invitation_code);
        $this->assertDatabaseHas('group_members', [
            'group_id' => $group->id,
            'user_id' => $user->id,
            'role' => 'leader'
        ]);
    }

    public function test_join_group_adds_member(): void
    {
        $leader = $this->createAuthenticatedUser();
        $this->actingAs($leader, 'sanctum');
        $group = Group::factory()->create(['created_by' => $leader->id, 'member_limit' => 5, 'invitation_code' => 'ABCDEF']);

        $newUser = $this->createAuthenticatedUser();
        $result = $this->service->joinGroup('ABCDEF', $newUser->id);

        $this->assertEquals($group->id, $result['group']->id);
        $this->assertDatabaseHas('group_members', [
            'group_id' => $group->id,
            'user_id' => $newUser->id
        ]);
    }
}
