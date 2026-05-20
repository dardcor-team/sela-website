<?php

namespace Tests\Feature\Api\Auth;

use Tests\TestCase;
use Tests\Traits\ActsAsAuthenticatedUser;

class LogoutTest extends TestCase
{
    use ActsAsAuthenticatedUser;

    public function test_logout_unauthenticated_returns_401(): void
    {
        $response = $this->postJson('/api/logout');

        $response->assertStatus(401);
    }

    public function test_logout_authenticated_returns_200(): void
    {
        $user = $this->createAuthenticatedUser();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withToken($token)->postJson('/api/logout');

        $response->assertStatus(200);
    }
}
