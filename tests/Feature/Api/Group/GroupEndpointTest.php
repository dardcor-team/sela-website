<?php

namespace Tests\Feature\Api\Group;

use Tests\TestCase;
use Tests\Traits\ActsAsAuthenticatedUser;
use Illuminate\Support\Str;

class GroupEndpointTest extends TestCase
{
    use ActsAsAuthenticatedUser;

    public function test_get_groups_by_user_unauthenticated_returns_401(): void
    {
        $uuid = Str::uuid()->toString();
        $response = $this->getJson("/api/groups/user/{$uuid}");
        $response->assertStatus(401);
    }

    public function test_get_groups_by_user_authenticated_returns_200(): void
    {
        $user = $this->actingAsUser();
        $response = $this->getJson("/api/groups/user/{$user->id}");
        $response->assertStatus(200);
    }

    public function test_get_group_by_id_returns_200(): void
    {
        $this->actingAsUser();
        $uuid = Str::uuid()->toString();
        $response = $this->getJson("/api/groups/{$uuid}");
        $response->assertSuccessful();
    }

    public function test_create_group_returns_201(): void
    {
        $this->actingAsUser();
        $response = $this->postJson('/api/groups', [
            'course_name' => 'Web Dev',
            'group_number' => 1,
        ]);
        $response->assertSuccessful();
    }

    public function test_create_group_validation_returns_422(): void
    {
        $this->actingAsUser();
        $response = $this->postJson('/api/groups', []);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['course_name', 'group_number']);
    }

    public function test_join_group_returns_200_or_201(): void
    {
        $user = $this->actingAsUser();
        $group = \App\Models\Group::factory()->create(['created_by' => $user->id, 'member_limit' => 5, 'invitation_code' => 'GROUP123']);
        
        $newUser = $this->createAuthenticatedUser();
        $this->actingAs($newUser, 'sanctum');

        $response = $this->postJson('/api/groups/join', [
            'code' => 'GROUP123'
        ]);
        $response->assertSuccessful();
    }

    public function test_delete_group_returns_200_or_204(): void
    {
        $user = $this->actingAsUser();
        $group = \App\Models\Group::factory()->create(['created_by' => $user->id]);
        $response = $this->deleteJson("/api/groups/{$group->id}");
        $response->assertSuccessful();
    }
}
