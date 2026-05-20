<?php

namespace Tests\Feature\Api\DeviceToken;

use App\Models\DeviceToken;
use Tests\TestCase;
use Tests\Traits\ActsAsAuthenticatedUser;

class DeviceTokenTest extends TestCase
{
    use ActsAsAuthenticatedUser;

    public function test_create_device_token_requires_auth(): void
    {
        $this->postJson('/api/device-tokens', [])
            ->assertUnauthorized();
    }

    public function test_create_device_token_validates_input(): void
    {
        $this->actingAsUser();

        $this->postJson('/api/device-tokens', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['token']);
    }

    public function test_create_device_token_returns_success(): void
    {
        $user = $this->actingAsUser();

        $this->postJson('/api/device-tokens', [
            'token' => 'some-fcm-token',
            'platform' => 'android'
        ])
            ->assertOk();
    }

    public function test_delete_device_token_requires_auth(): void
    {
        $this->deleteJson('/api/device-tokens', [])
            ->assertUnauthorized();
    }

    public function test_delete_device_token_validates_input(): void
    {
        $this->actingAsUser();

        $this->deleteJson('/api/device-tokens', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['token']);
    }

    public function test_delete_device_token_returns_success(): void
    {
        $user = $this->actingAsUser();
        DeviceToken::factory()->create(['user_id' => $user->id, 'token' => 'some-token']);

        $this->deleteJson('/api/device-tokens', [
            'token' => 'some-token'
        ])
            ->assertOk();
    }
}
