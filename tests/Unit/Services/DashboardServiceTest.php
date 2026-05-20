<?php
namespace Tests\Unit\Services;

use Tests\TestCase;
use Tests\Traits\ActsAsAuthenticatedUser;
use App\Services\DashboardService;

class DashboardServiceTest extends TestCase
{
    use ActsAsAuthenticatedUser;

    private DashboardService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new DashboardService();
    }

    public function test_get_dashboard_returns_data_array(): void
    {
        // Note: ilike is PostgreSQL-specific, test may fail on SQLite
        $user = $this->actingAsUser();
        
        $dashboard = $this->service->getDashboard($user->id);
        
        $this->assertIsArray($dashboard);
        $this->assertArrayHasKey('user', $dashboard);
        $this->assertArrayHasKey('overview', $dashboard);
    }
}
