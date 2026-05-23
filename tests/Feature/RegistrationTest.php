<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_must_be_from_campus_domain()
    {
        $response = $this->postJson('/api/register', [
            'username' => 'testuser',
            'email' => 'test@gmail.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
                 ->assertJsonFragment(['Gunakan email kampus: @it.student.pens.ac.id (Mahasiswa) atau @pens.ac.id (Dosen)']);
    }

    public function test_registration_validation_messages_are_indonesian()
    {
        $response = $this->postJson('/api/register', [
            'username' => '',
            'email' => 'invalid-email',
            'password' => '123',
        ]);

        $response->assertStatus(422)
                 ->assertJsonFragment(['Username wajib diisi.'])
                 ->assertJsonFragment(['Format email tidak valid.'])
                 ->assertJsonFragment(['Kata sandi minimal 6 karakter.']);
    }
}
