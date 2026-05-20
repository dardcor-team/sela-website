<?php

namespace Tests\Feature\Api\GroupMember;

use Tests\TestCase;
use Tests\Traits\ActsAsAuthenticatedUser;
use Illuminate\Support\Str;

class GroupMemberTest extends TestCase
{
    use ActsAsAuthenticatedUser;

    public function test_get_group_members_unauthenticated_returns_401(): void
    {
        $uuid = Str::uuid()->toString();
        $response = $this->getJson("/api/groups/{$uuid}/members");
        $response->assertStatus(401);
    }

    public function test_get_group_members_authenticated_returns_200(): void
    {
        $this->actingAsUser();
        $uuid = Str::uuid()->toString();
        $response = $this->getJson("/api/groups/{$uuid}/members");
        $response->assertSuccessful();
    }

    public function test_add_group_member_returns_201(): void
    {
        $user = $this->actingAsUser();
        $group = \App\Models\Group::factory()->create(['created_by' => $user->id]);
        $newUser = $this->createAuthenticatedUser();
        
        $response = $this->postJson("/api/groups/{$group->id}/members", [
            'user_id' => $newUser->id,
            'role' => 'member'
        ]);
        
        $response->assertSuccessful();
    }

    public function test_update_group_member_returns_200(): void
    {
        $user = $this->actingAsUser();
        $group = \App\Models\Group::factory()->create(['created_by' => $user->id]);
        $member = \App\Models\GroupMember::factory()->create([
            'group_id' => $group->id,
            'user_id' => $user->id,
        ]);
        
        $response = $this->putJson("/api/group-members/{$member->id}", [
            'role' => 'leader'
        ]);
        
        $response->assertSuccessful();
    }

    public function test_delete_group_member_returns_200_or_204(): void
    {
        $user = $this->actingAsUser();
        $group = \App\Models\Group::factory()->create(['created_by' => $user->id]);
        $member = \App\Models\GroupMember::factory()->create([
            'group_id' => $group->id,
            'user_id' => $user->id,
        ]);
        
        $response = $this->deleteJson("/api/groups/{$group->id}/members/{$user->id}");
        $response->assertSuccessful();
    }
}
