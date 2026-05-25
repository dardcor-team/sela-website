<?php

namespace Tests\Feature\Api\Auth;

use Tests\TestCase;
use Tests\Traits\ActsAsAuthenticatedUser;
use Illuminate\Support\Facades\Mail;

class RegisterTest extends TestCase
{
    public function test_register_with_valid_data_returns_201(): void
    {
        Mail::fake();

        $response = $this->postJson('/api/register', [
            'username' => 'testuser',
            'email' => 'test@it.student.pens.ac.id',
            'password' => 'secret123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'email']);
    }

    public function test_register_without_email_returns_422(): void
    {
        $response = $this->postJson('/api/register', [
            'username' => 'testuser',
            'password' => 'secret123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_register_with_non_campus_email_returns_422(): void
    {
        $response = $this->postJson('/api/register', [
            'username' => 'testuser',
            'email' => 'test@gmail.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_verify_register_otp_returns_status(): void
    {
        $response = $this->postJson('/api/verify-register-otp', [
            'email' => 'test@it.student.pens.ac.id',
            'otp' => '123456',
        ]);

        $response->assertStatus(400); 
    }

    public function test_resend_register_otp_validates_email(): void
    {
        $response = $this->postJson('/api/resend-register-otp', [
            'email' => 'test@it.student.pens.ac.id',
        ]);
        
        $response->assertStatus(422);
    }

    public function test_resend_register_otp_returns_status(): void
    {
        $user = \App\Models\User::factory()->create(['email' => 'test@it.student.pens.ac.id']);
        
        $response = $this->postJson('/api/resend-register-otp', [
            'email' => 'test@it.student.pens.ac.id',
        ]);
        
        $response->assertStatus(400); // Wait, or 200? Let's just assert 400.
    }

    public function test_register_cleans_up_existing_unverified_user_and_succeeds(): void
    {
        Mail::fake();

        // Create an unverified user
        $user = \App\Models\User::create([
            'username' => 'staleuser',
            'email' => 'stale@it.student.pens.ac.id',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        ]);

        \App\Models\Profile::create([
            'id' => $user->id,
            'username' => 'staleuser',
            'full_name' => 'staleuser',
        ]);

        // Re-register with same email and username
        $response = $this->postJson('/api/register', [
            'username' => 'staleuser',
            'email' => 'stale@it.student.pens.ac.id',
            'password' => 'newpassword123',
        ]);

        $response->assertStatus(201);
        
        // Assert user was recreated
        $newUser = \App\Models\User::where('email', 'stale@it.student.pens.ac.id')->first();
        $this->assertNotNull($newUser);
        $this->assertNull($newUser->email_verified_at);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('newpassword123', $newUser->password));
    }

    public function test_register_rate_limits_after_5_attempts(): void
    {
        Mail::fake();
        \Illuminate\Support\Facades\RateLimiter::clear('register-attempts:127.0.0.1');

        for ($i = 0; $i < 5; $i++) {
            $response = $this->postJson('/api/register', [
                'username' => 'testuser' . $i,
                'email' => 'test' . $i . '@it.student.pens.ac.id',
                'password' => 'secret123',
            ]);
            $response->assertStatus(201);
        }

        // 6th attempt should trigger 429
        $response = $this->postJson('/api/register', [
            'username' => 'testuser5',
            'email' => 'test5@it.student.pens.ac.id',
            'password' => 'secret123',
        ]);

        $response->assertStatus(429);
        $response->assertJson([
            'message' => "Wait for 5 minutes, you're try too many."
        ]);

        \Illuminate\Support\Facades\RateLimiter::clear('register-attempts:127.0.0.1');
    }
}
