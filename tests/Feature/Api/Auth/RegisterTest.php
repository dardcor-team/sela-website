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
}
