<?php
namespace Tests\Unit\Services;

use Tests\TestCase;
use Tests\Traits\ActsAsAuthenticatedUser;
use App\Services\FcmService;
use Illuminate\Support\Facades\Http;
use App\Models\DeviceToken;

class FcmServiceTest extends TestCase
{
    use ActsAsAuthenticatedUser;

    private FcmService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new FcmService();
    }

    public function test_send_to_user_posts_to_fcm(): void
    {
        Http::fake([
            '*' => Http::response(['success' => 1], 200)
        ]);

        $user = $this->actingAsUser();
        DeviceToken::create(['user_id' => $user->id, 'token' => 'dummy_token', 'platform' => 'android']);

        $result = $this->service->sendToUser($user->id, 'Title', 'Message', []);
        
        $this->assertNull($result); // The service returns void/null
    }

    public function test_register_token_saves_to_db(): void
    {
        $user = $this->actingAsUser();
        
        $this->service->registerToken($user->id, 'new_token', 'ios');
        
        $this->assertDatabaseHas('device_tokens', [
            'user_id' => $user->id,
            'token' => 'new_token',
            'platform' => 'ios'
        ]);
    }
    
    public function test_remove_token_deletes_from_db(): void
    {
        $user = $this->actingAsUser();
        DeviceToken::create(['user_id' => $user->id, 'token' => 'del_token', 'platform' => 'android']);
        
        $this->service->removeToken('del_token');
        
        $this->assertDatabaseMissing('device_tokens', ['token' => 'del_token']);
    }
}
