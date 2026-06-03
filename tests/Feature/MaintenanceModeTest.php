<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class MaintenanceModeTest extends TestCase
{
    protected function tearDown(): void
    {
        Cache::forget('app_maintenance');

        parent::tearDown();
    }

    public function test_landing_pages_render_maintenance_view_when_maintenance_is_enabled(): void
    {
        Cache::put('app_maintenance', true);

        foreach (['/', '/coming-soon'] as $path) {
            $response = $this->get($path);

            $response->assertStatus(503)
                ->assertSee('Halaman dalam Perbaikan')
                ->assertSee('Kami sedang meningkatkan fitur ini untuk memberikan pengalaman terbaik bagi Anda.');
        }
    }

    public function test_json_requests_return_maintenance_response_when_maintenance_is_enabled(): void
    {
        Cache::put('app_maintenance', true);

        $response = $this->getJson('/api/users');

        $response->assertStatus(503)
            ->assertJsonFragment([
                'status' => 'maintenance',
            ]);
    }

    public function test_maintenance_page_redirects_home_when_maintenance_is_disabled(): void
    {
        Cache::put('app_maintenance', false);

        $response = $this->get('/maintenance');

        $response->assertRedirect('/');
    }
}
