<?php

namespace Tests\Feature\Api\Auth;

use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    public function test_login_with_valid_credentials_returns_200(): void
    {
        $user = User::factory()->create([
            'email' => 'login@it.student.pens.ac.id',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'login@it.student.pens.ac.id',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'user']);
    }

    public function test_login_without_email_returns_422(): void
    {
        $response = $this->postJson('/api/login', [
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_login_with_invalid_credentials_returns_401(): void
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'login@it.student.pens.ac.id',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'login@it.student.pens.ac.id',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422);
    }
}
