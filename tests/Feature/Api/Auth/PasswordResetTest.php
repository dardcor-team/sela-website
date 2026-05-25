<?php

namespace Tests\Feature\Api\Auth;

use Tests\TestCase;
use Illuminate\Support\Facades\Mail;

class PasswordResetTest extends TestCase
{
    public function test_forgot_password_with_valid_email_returns_200(): void
    {
        Mail::fake();
        $user = \App\Models\User::factory()->create(['email' => 'test@it.student.pens.ac.id']);
        
        $response = $this->postJson('/api/forgot-password', [
            'email' => 'test@it.student.pens.ac.id',
        ]);
        
        $response->assertStatus(200);
    }

    public function test_forgot_password_without_email_returns_422(): void
    {
        $response = $this->postJson('/api/forgot-password', []);
        
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_verify_otp_returns_400_for_invalid_otp(): void
    {
        $response = $this->postJson('/api/verify-otp', [
            'email' => 'test@it.student.pens.ac.id',
            'otp' => '123456',
        ]);
        
        $response->assertStatus(400); // or 400 depending on if email exists, let's see. If email doesn't exist it might return 404
    }

    public function test_reset_password_with_invalid_otp_returns_400(): void
    {
        $response = $this->postJson('/api/reset-password', [
            'email' => 'test@it.student.pens.ac.id',
            'otp' => '123456',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);
        
        // Either 404 for email or 400 for otp. Let's assert successful or expected failure
        $response->assertStatus(400);
    }

    public function test_reset_password_without_required_fields_returns_422(): void
    {
        $response = $this->postJson('/api/reset-password', [
            'email' => 'test@it.student.pens.ac.id',
        ]);
        
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['otp', 'password']);
    }

    public function test_forgot_password_with_unverified_email_returns_404(): void
    {
        Mail::fake();
        $user = \App\Models\User::factory()->unverified()->create(['email' => 'unverified@it.student.pens.ac.id']);
        
        $response = $this->postJson('/api/forgot-password', [
            'email' => 'unverified@it.student.pens.ac.id',
        ]);
        
        $response->assertStatus(404)
            ->assertJson(['message' => 'Akun belum terdaftar.']);
    }
}
