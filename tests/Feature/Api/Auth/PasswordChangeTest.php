<?php

namespace Tests\Feature\Api\Auth;

use Tests\TestCase;
use Tests\Traits\ActsAsAuthenticatedUser;

class PasswordChangeTest extends TestCase
{
    use ActsAsAuthenticatedUser;

    public function test_change_password_unauthenticated_returns_401(): void
    {
        $response = $this->postJson('/api/change-password', [
            'old_password' => 'old123',
            'new_password' => 'new123',
        ]);
        $response->assertStatus(401);
    }

    public function test_change_password_authenticated_valid_data_returns_200(): void
    {
        $this->actingAsUser();

        $response = $this->postJson('/api/change-password', [
            'old_password' => 'password',
            'new_password' => 'newpassword123',
        ]);
        
        $response->assertStatus(200);
    }

    public function test_verify_password_unauthenticated_returns_401(): void
    {
        $response = $this->postJson('/api/verify-password', [
            'password' => 'password123',
        ]);
        $response->assertStatus(401);
    }

    public function test_verify_password_authenticated_valid_data_returns_200(): void
    {
        $this->actingAsUser();

        $response = $this->postJson('/api/verify-password', [
            'password' => 'password',
        ]);
        
        $response->assertStatus(200);
    }
}
