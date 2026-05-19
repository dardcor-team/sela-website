<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmailOtpMail;

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
    public function verify_register_otp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6'
        ]);

        $cachedOtp = Cache::get("register_otp_{$request->email}");

        if (!$cachedOtp) {
            return response()->json(['message' => 'Kode OTP telah kadaluarsa atau tidak ditemukan. Silakan minta ulang.'], 400);
        }

        if ($cachedOtp !== $request->otp) {
            return response()->json(['message' => 'Kode OTP salah.'], 400);
        }

        $user = \App\Models\User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'Pengguna tidak ditemukan.'], 404);
        }

        $user->email_verified_at = now();
        $user->save();

        Cache::forget("register_otp_{$request->email}");

        return response()->json(['message' => 'Email berhasil diverifikasi. Silakan login.']);
    }

    public function resend_register_otp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user->email_verified_at !== null) {
            return response()->json(['message' => 'Email ini sudah diverifikasi.'], 400);
        }

        $otp = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put("register_otp_{$user->email}", $otp, now()->addMinutes(10));
        
        try {
            Mail::to($user->email)->send(new VerifyEmailOtpMail($user->username, $otp));
        } catch (\Exception $e) {
            \Log::error('Failed to send verification email: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal mengirim email verifikasi.'], 500);
        }

        return response()->json(['message' => 'Kode OTP baru telah dikirim ke email Anda.']);
    }
}
                },
            ],
            'password' => 'required|string|min:6',
            'class_name' => 'nullable|string|max:100',
        ]);

        $user = $service->register($validated);
        
        // Generate and send OTP
        $otp = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put("register_otp_{$user->email}", $otp, now()->addMinutes(10));
        
        try {
            Mail::to($user->email)->send(new VerifyEmailOtpMail($user->username, $otp));
        } catch (\Exception $e) {
            \Log::error('Failed to send verification email: ' . $e->getMessage());
        }

        Cache::tags(['users'])->flush();

        return response()->json([
            'message' => 'Registrasi berhasil. Silakan cek email Anda untuk kode OTP.',
            'email' => $user->email
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
