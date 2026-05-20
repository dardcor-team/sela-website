<?php
namespace Tests\Unit\Services;

use Tests\TestCase;
use Tests\Traits\ActsAsAuthenticatedUser;
use App\Services\NotificationService;
use App\Models\Notification;

class NotificationServiceTest extends TestCase
{
    use ActsAsAuthenticatedUser;

    private NotificationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new NotificationService();
    }

    public function test_create_notification_stores_record(): void
    {
        $user = $this->actingAsUser();
        $this->mock(\App\Services\FcmService::class, function ($mock) {
            $mock->shouldReceive('sendToUser')->andReturn(true);
        });

        $notification = $this->service->createNotification([
            'user_id' => $user->id,
            'title' => 'Test',
            'message' => 'Msg',
            'type' => 'info'
        ]);

        $this->assertInstanceOf(Notification::class, $notification);
        $this->assertEquals('Test', $notification->title);
    }

    public function test_get_notifications_returns_collection(): void
    {
        $user = $this->actingAsUser();
        Notification::factory()->create(['user_id' => $user->id]);

        $notifications = $this->service->getNotifications($user->id);
        $this->assertNotEmpty($notifications);
    }
    
    public function test_mark_as_read_updates_status(): void
    {
        $user = $this->actingAsUser();
        $notification = Notification::factory()->create(['user_id' => $user->id, 'is_read' => false]);
        $this->service->markAsRead($notification->id);
        
        $this->assertTrue($notification->fresh()->is_read);
    }
}
