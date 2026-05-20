<?php

namespace Tests\Feature\Api\Dashboard;

use Tests\TestCase;
use Tests\Traits\ActsAsAuthenticatedUser;
use Illuminate\Support\Str;

class DashboardTest extends TestCase
{
    use ActsAsAuthenticatedUser;

    public function test_dashboard_unauthenticated_returns_401(): void
    {
        $uuid = Str::uuid()->toString();
        $response = $this->getJson("/api/dashboard/{$uuid}");
        $response->assertStatus(401);
    }

    public function test_dashboard_authenticated_returns_200(): void
    {
        $user = $this->actingAsUser();
        
        $response = $this->getJson("/api/dashboard/{$user->id}");
        $response->assertStatus(200);
    }
}
