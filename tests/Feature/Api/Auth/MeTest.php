<?php

namespace Tests\Feature\Api\Auth;

use Tests\TestCase;
use Tests\Traits\ActsAsAuthenticatedUser;

class MeTest extends TestCase
{
    use ActsAsAuthenticatedUser;

    public function test_get_me_unauthenticated_returns_401(): void
    {
        $response = $this->getJson('/api/me');
        $response->assertStatus(401);
    }

    public function test_get_me_authenticated_returns_200(): void
    {
        $this->actingAsUser();
        $response = $this->getJson('/api/me');
        
        $response->assertStatus(200)
            ->assertStatus(200)->assertJsonStructure(['user' => ['id', 'email']]);
    }

    public function test_put_me_unauthenticated_returns_401(): void
    {
        $response = $this->putJson('/api/me', [
            'full_name' => 'New Name'
        ]);
        $response->assertStatus(401);
    }

    public function test_put_me_authenticated_returns_200(): void
    {
        $this->actingAsUser();
        
        $response = $this->putJson('/api/me', [
            'full_name' => 'New Name',
            'class_name' => '2 D4 IT A',
        ]);
        
        $response->assertStatus(200);
    }
}
