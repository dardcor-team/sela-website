<?php
namespace Tests\Unit\Services;

use Tests\TestCase;
use Tests\Traits\ActsAsAuthenticatedUser;
use App\Services\GroupMemberService;
use App\Models\GroupMember;
use App\Models\Group;

class GroupMemberServiceTest extends TestCase
{
    use ActsAsAuthenticatedUser;

    private GroupMemberService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new GroupMemberService();
    }

    public function test_get_group_members_returns_collection(): void
    {
        $user = $this->actingAsUser();
        $group = Group::factory()->create();
        GroupMember::factory()->create(['group_id' => $group->id, 'user_id' => $user->id]);

        $members = $this->service->getGroupMembers($group->id);
        $this->assertNotEmpty($members);
    }

    public function test_add_member_creates_record(): void
    {
        $user = $this->actingAsUser();
        $group = Group::factory()->create();

        $member = $this->service->addMember([
            'group_id' => $group->id,
            'user_id' => $user->id,
            'role' => 'member'
        ]);

        $this->assertInstanceOf(GroupMember::class, $member);
    }
    
    public function test_remove_member_deletes_record(): void
    {
        $user = $this->actingAsUser();
        $group = Group::factory()->create();
        $this->service->addMember(['group_id' => $group->id, 'user_id' => $user->id, 'role' => 'member']);

        $result = $this->service->removeMember($group->id, $user->id);
        $this->assertTrue($result);
        $this->assertDatabaseMissing('group_members', ['group_id' => $group->id, 'user_id' => $user->id]);
    }
}
