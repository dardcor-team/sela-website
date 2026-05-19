<?php

namespace App\Services;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class AuthService
{
    /**
     * Determine role based on email domain.
     *
     * @return string 'lecturer' for @pens.ac.id, 'student' otherwise.
     */
    public static function roleFromEmail(string $email): string
    {
        // Must check student subdomain first (it.student.pens.ac.id is also @pens.ac.id)
        if (str_ends_with($email, '@it.student.pens.ac.id')) {
            return 'student';
        }

        if (str_ends_with($email, '@pens.ac.id')) {
            return 'lecturer';
        }

        return 'student';
    }

    public function register(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $data['role'] = self::roleFromEmail($data['email']);

            $user = User::create($data);

            Profile::create([
                'id' => $user->id,
                'username' => $data['username'],
                'full_name' => $data['username'],
                'class_name' => $data['class_name'] ?? $data['class'] ?? null,
            ]);

            return $user;
        });
    }

    public function login(array $credentials): array
    {
        $user = User::with('profile')->where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (is_null($user->email_verified_at)) {
            throw ValidationException::withMessages([
                'email' => ['Email belum diverifikasi. Silakan periksa email Anda untuk kode OTP verifikasi.'],
            ]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function logout($user): void
    {
        $user->currentAccessToken()->delete();
    }
}