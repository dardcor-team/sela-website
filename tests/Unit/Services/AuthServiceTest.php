<?php
namespace Tests\Unit\Services;

use Tests\TestCase;
use Tests\Traits\ActsAsAuthenticatedUser;
use App\Services\AuthService;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AuthServiceTest extends TestCase
{
    use ActsAsAuthenticatedUser;

    private AuthService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AuthService();
    }

    public function test_role_from_email_returns_student_for_student_domain(): void
    {
        $this->assertEquals('student', AuthService::roleFromEmail('user@it.student.pens.ac.id'));
    }

    public function test_role_from_email_returns_lecturer_for_pens_domain(): void
    {
        $this->assertEquals('lecturer', AuthService::roleFromEmail('prof@pens.ac.id'));
    }

    public function test_register_creates_user_and_profile(): void
    {
        $data = [
            'username' => 'newuser',
            'email' => 'new@it.student.pens.ac.id',
            'password' => 'secret123',
        ];

        $user = $this->service->register($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('newuser', $user->username);
        $this->assertDatabaseHas('profiles', ['id' => $user->id]);
    }

    public function test_login_with_wrong_password_throws_validation_exception(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $this->expectException(ValidationException::class);
        $this->service->login(['email' => $user->email, 'password' => 'wrong']);
    }

    public function test_login_with_unverified_email_throws_validation_exception(): void
    {
        $user = User::factory()->unverified()->create();

        $this->expectException(ValidationException::class);
        $this->service->login(['email' => $user->email, 'password' => 'password']);
    }
}
