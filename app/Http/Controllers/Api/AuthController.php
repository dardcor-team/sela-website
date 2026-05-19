<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    public function register(Request $request, AuthService $service): JsonResponse
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50',
            'email' => [
                'required', 'email', 'max:100', 'unique:users,email',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if (!str_ends_with($value, '@it.student.pens.ac.id') && !str_ends_with($value, '@pens.ac.id')) {
                        $fail('Gunakan email kampus: @it.student.pens.ac.id (Mahasiswa) atau @pens.ac.id (Dosen)');
                    }
                },
            ],
            'password' => 'required|string|min:6',
            'class_name' => 'nullable|string|max:100',
        ]);

        $user = $service->register($validated);
        $token = $user->createToken('auth-token')->plainTextToken;

        Cache::tags(['users'])->flush();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'username' => $user->username,
                'full_name' => $user->username,
                'class_name' => $request->class_name,
                'role' => $user->role,
            ],
            'token' => $token,
        ], 201);
    }

    public function login(Request $request, AuthService $service): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $result = $service->login($validated);

        return response()->json($result);
    }

    public function logout(Request $request, AuthService $service): JsonResponse
    {
        $service->logout($request->user());

        return response()->json(['message' => 'Logged out successfully.']);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();
        $profile = \App\Models\Profile::where('id', $user->id)->first();
        if ($profile) {
            if ($request->has('full_name')) {
                $profile->full_name = $request->full_name;
            }
            if ($request->has('class_name')) {
                $profile->class_name = $request->class_name;
            }
            if ($request->has('avatar_url')) {
                $profile->avatar_url = $request->avatar_url;
            }
            $profile->save();
        }

        Cache::tags(["user_{$user->id}"])->flush();

        return response()->json(['message' => 'Profile updated']);
    }

    public function me(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $data = Cache::tags(["user_{$userId}"])->remember("auth_me_{$userId}", 300, function () use ($request) {
            $user = $request->user()->load('profile');
            return [
                'id' => $user->id,
                'email' => $user->email,
                'username' => $user->username,
                'full_name' => $user->profile?->full_name ?? $user->username,
                'class_name' => $user->profile?->class_name,
                'avatar_url' => $user->profile?->avatar_url,
                'role' => $user->role,
            ];
        });

        return response()->json([
            'user' => $data
        ]);
    }

    public function verifyPassword(Request $request): JsonResponse
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = $request->user();

        if (!\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Password salah'], 400);
        }

        return response()->json(['message' => 'Password correct']);
    }

    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:6',
        ]);

        $user = $request->user();

        if (!\Illuminate\Support\Facades\Hash::check($request->old_password, $user->password)) {
            return response()->json(['message' => 'Password salah'], 400);
        }

        if (\Illuminate\Support\Facades\Hash::check($request->new_password, $user->password)) {
            return response()->json(['message' => 'Password harus berbeda dari password lama'], 400);
        }

        $user->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password updated']);
    }

    public function forgot_password(Request $request): JsonResponse
    {
        // Dummy implementation
        return response()->json(['message' => 'OTP sent successfully']);
    }

    public function verify_otp(Request $request): JsonResponse
    {
        // Dummy implementation
        return response()->json(['message' => 'OTP verified successfully']);
    }

    public function reset_password(Request $request): JsonResponse
    {
        // Dummy implementation
        return response()->json(['message' => 'Password reset successfully']);
    }
}
