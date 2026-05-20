<?php

namespace Tests\Feature\Api\Notification;

use App\Models\Notification;
use App\Services\FcmService;
use Tests\TestCase;
use Tests\Traits\ActsAsAuthenticatedUser;

class NotificationTest extends TestCase
{
    use ActsAsAuthenticatedUser;

    public function test_get_notifications_requires_auth(): void
    {
        $this->getJson('/api/notifications')
            ->assertUnauthorized();
    }

    public function test_get_notifications_returns_success(): void
    {
        $user = $this->actingAsUser();
        Notification::factory()->count(2)->create(['user_id' => $user->id]);

        $this->getJson('/api/notifications')
            ->assertOk();
    }

    public function test_create_notification_requires_auth(): void
    {
        $this->postJson('/api/notifications', [])
            ->assertUnauthorized();
    }

    public function test_create_notification_validates_input(): void
    {
        $this->actingAsUser();

        $this->postJson('/api/notifications', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['user_id', 'title', 'message']);
    }

    public function test_create_notification_returns_success(): void
    {
        $user = $this->actingAsUser();
        
        $this->mock(FcmService::class, function ($mock) {
            $mock->shouldReceive('sendNotification')->andReturn(true);
        });

        $this->postJson('/api/notifications', [
            'user_id' => $user->id,
            'title' => 'Test',
            'message' => 'Message',
            'type' => 'info'
        ])
            ->assertCreated();
    }

    public function test_delete_multiple_requires_auth(): void
    {
        $this->postJson('/api/notifications/delete-multiple', [])
            ->assertUnauthorized();
    }

    public function test_delete_multiple_returns_success(): void
    {
        $user = $this->actingAsUser();
        $notifications = Notification::factory()->count(2)->create(['user_id' => $user->id]);

        $this->postJson('/api/notifications/delete-multiple', [
            'ids' => $notifications->pluck('id')->toArray()
        ])
            ->assertOk();
    }

    public function test_mark_read_multiple_requires_auth(): void
    {
        $this->putJson('/api/notifications/mark-read-multiple', [])
            ->assertUnauthorized();
    }

    public function test_mark_read_multiple_returns_success(): void
    {
        $user = $this->actingAsUser();
        $notifications = Notification::factory()->count(2)->create(['user_id' => $user->id, 'is_read' => false]);

        $this->putJson('/api/notifications/mark-read-multiple', [
            'ids' => $notifications->pluck('id')->toArray()
        ])
            ->assertOk();
    }

    public function test_mark_all_read_requires_auth(): void
    {
        $this->putJson('/api/notifications/mark-all-read')
            ->assertUnauthorized();
    }

    public function test_mark_all_read_returns_success(): void
    {
        $user = $this->actingAsUser();
        Notification::factory()->count(2)->create(['user_id' => $user->id, 'is_read' => false]);

        $this->putJson('/api/notifications/mark-all-read')
            ->assertOk();
    }

    public function test_mark_read_single_requires_auth(): void
    {
        $this->putJson('/api/notifications/some-id/read')
            ->assertUnauthorized();
    }

    public function test_mark_read_single_returns_success(): void
    {
        $user = $this->actingAsUser();
        $notification = Notification::factory()->create(['user_id' => $user->id, 'is_read' => false]);

        $this->putJson("/api/notifications/{$notification->id}/read")
            ->assertOk();
    }

    public function test_delete_single_requires_auth(): void
    {
        $this->deleteJson('/api/notifications/some-id')
            ->assertUnauthorized();
    }

    public function test_delete_single_returns_success(): void
    {
        $user = $this->actingAsUser();
        $notification = Notification::factory()->create(['user_id' => $user->id]);

        $this->deleteJson("/api/notifications/{$notification->id}")
            ->assertOk();
    }
}
