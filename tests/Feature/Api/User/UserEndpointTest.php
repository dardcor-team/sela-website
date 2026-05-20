<?php

namespace Tests\Feature\Api\User;

use Tests\TestCase;
use Tests\Traits\ActsAsAuthenticatedUser;
use Illuminate\Support\Str;

class UserEndpointTest extends TestCase
{
    use ActsAsAuthenticatedUser;

    public function test_get_users_unauthenticated_returns_401(): void
    {
        $response = $this->getJson('/api/users');
        $response->assertStatus(401);
    }

    public function test_get_users_authenticated_returns_200(): void
    {
        $this->actingAsUser();
        $response = $this->getJson('/api/users');
        $response->assertStatus(200);
    }

    public function test_search_users_returns_200(): void
    {
        $this->actingAsUser();
        $response = $this->getJson('/api/users/search?q=test');
        $response->assertStatus(200);
    }

    public function test_get_user_by_id_returns_200(): void
    {
        $user = $this->actingAsUser();
        $response = $this->getJson("/api/users/{$user->id}");
        $response->assertStatus(200);
    }

    public function test_update_user_returns_200(): void
    {
        $user = $this->actingAsUser();
        $response = $this->putJson("/api/users/{$user->id}", [
            'email' => 'updated@it.student.pens.ac.id'
        ]);
        $response->assertStatus(200);
    }

    public function test_delete_user_returns_200_or_204(): void
    {
        $user = $this->actingAsUser();
        $response = $this->deleteJson("/api/users/{$user->id}");
        $response->assertSuccessful();
    }

    public function test_get_user_abilities_returns_200(): void
    {
        $user = $this->actingAsUser();
        $response = $this->getJson("/api/users/{$user->id}/abilities");
        $response->assertStatus(200);
    }

    public function test_post_user_abilities_returns_200_or_201(): void
    {
        $user = $this->actingAsUser();
        $response = $this->postJson("/api/users/{$user->id}/abilities", [
            'ability' => 'view_dashboard'
        ]);
        $response->assertSuccessful();
    }

    public function test_delete_profile_abilities_returns_200_or_204(): void
    {
        $user = $this->actingAsUser();
        $ability = \App\Models\ProfileAbility::factory()->create(['user_id' => $user->id]);
        $response = $this->deleteJson("/api/profile-abilities/{$ability->id}");
        $response->assertSuccessful();
    }
}
