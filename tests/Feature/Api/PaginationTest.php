<?php

namespace Tests\Feature\Api;

use App\Models\Notification;
use App\Models\Task;
use App\Models\Group;
use App\Models\Profile;
use App\Models\User;
use Tests\TestCase;
use Tests\Traits\ActsAsAuthenticatedUser;

class PaginationTest extends TestCase
{
    use ActsAsAuthenticatedUser;

    public function test_notifications_can_be_paginated(): void
    {
        $user = $this->actingAsUser();
        Notification::factory()->count(10)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/notifications?per_page=5');
        
        $response->assertOk();
        $response->assertJsonStructure(['data', 'current_page', 'last_page', 'total']);
        $this->assertEquals(5, count($response->json('data')));
        $this->assertEquals(2, $response->json('last_page'));
    }

    public function test_tasks_can_be_paginated(): void
    {
        $user = $this->actingAsUser();
        Task::factory()->count(10)->create(['created_by' => $user->id]);

        $response = $this->getJson("/api/tasks/user/{$user->id}?per_page=3");
        
        $response->assertOk();
        $response->assertJsonStructure(['data', 'current_page', 'last_page', 'total']);
        $this->assertEquals(3, count($response->json('data')));
    }

    public function test_groups_can_be_paginated(): void
    {
        $user = $this->actingAsUser();
        
        // Create 5 groups and add user as a member to each
        $groups = Group::factory()->count(5)->create(['created_by' => $user->id]);
        foreach ($groups as $group) {
            \App\Models\GroupMember::factory()->create(['group_id' => $group->id, 'user_id' => $user->id]);
        }

        $response = $this->getJson("/api/groups/user/{$user->id}?per_page=2");
        
        $response->assertOk();
        $response->assertJsonStructure(['data', 'current_page', 'last_page', 'total']);
        $this->assertEquals(2, count($response->json('data')));
    }

    public function test_users_can_be_paginated(): void
    {
        $this->actingAsUser();
        \App\Models\Profile::factory()->count(10)->create();

        $response = $this->getJson('/api/users?per_page=4');
        
        $response->assertOk();
        $response->assertJsonStructure(['data', 'current_page', 'last_page', 'total']);
        $this->assertEquals(4, count($response->json('data')));
    }
}
