<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmailOtpMail;
use App\Mail\ResetPasswordOtpMail;
use App\Models\PasswordHistory;

class AuthController extends Controller
{
    public function register(Request $request, AuthService $service): JsonResponse
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:profiles,username',
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
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah terdaftar.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
        ]);

        $user = $service->register($validated);
        
        // Generate and send OTP
        $otp = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put("register_otp_{$user->email}", $otp, now()->addMinutes(10));
        Cache::put("otp_limit_{$user->email}", true, 30);
        
        try {
            Mail::to($user->email)->send(new VerifyEmailOtpMail($user->username, $otp));
        } catch (\Exception $e) {
            \Log::error('Failed to send verification email: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Registrasi berhasil. Silakan cek email Anda untuk kode OTP.',
            'email' => $user->email
        ], 201);
    }

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

        if (Cache::has("otp_limit_{$user->email}")) {
            return response()->json(['message' => 'Tunggu 30 detik sebelum meminta OTP baru.'], 429);
        }

        $otp = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put("register_otp_{$user->email}", $otp, now()->addMinutes(10));
        Cache::put("otp_limit_{$user->email}", true, 30);
        
        try {
            Mail::to($user->email)->send(new VerifyEmailOtpMail($user->username, $otp));
        } catch (\Exception $e) {
            \Log::error('Failed to send verification email: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal mengirim email verifikasi.'], 500);
        }

        return response()->json(['message' => 'Kode OTP baru telah dikirim ke email Anda.']);
    }

    public function login(Request $request, AuthService $service): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'remember_me' => 'nullable|boolean',
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

        return response()->json(['message' => 'Profile updated']);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load('profile');
        $data = [
            'id' => $user->id,
            'email' => $user->email,
            'username' => $user->username,
            'full_name' => $user->profile?->full_name ?? $user->username,
            'class_name' => $user->profile?->class_name,
            'avatar_url' => $user->profile?->avatar_url,
            'role' => $user->role,
        ];

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

        $histories = PasswordHistory::where('user_id', $user->id)->get();
        foreach ($histories as $history) {
            if (\Illuminate\Support\Facades\Hash::check($request->new_password, $history->password)) {
                return response()->json(['message' => 'Anda tidak bisa menggunakan password yang pernah digunakan sebelumnya.'], 400);
            }
        }

        PasswordHistory::create([
            'user_id' => $user->id,
            'password' => $user->password,
        ]);

        $user->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password updated']);
    }

    public function forgot_password(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Alamat email tidak terdaftar.'], 404);
        }

        if (Cache::has("otp_limit_{$user->email}")) {
            return response()->json(['message' => 'Tunggu 30 detik sebelum meminta OTP baru.'], 429);
        }

        $otp = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put("reset_otp_{$user->email}", $otp, now()->addMinutes(10));
        Cache::put("otp_limit_{$user->email}", true, 30);
        
        try {
            Mail::to($user->email)->send(new ResetPasswordOtpMail($user->username, $otp));
        } catch (\Exception $e) {
            \Log::error('Failed to send reset password email: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal mengirim email OTP.'], 500);
        }

        return response()->json(['message' => 'Kode OTP untuk reset password telah dikirim ke email Anda.']);
    }

    public function verify_otp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6'
        ]);

        $cachedOtp = Cache::get("reset_otp_{$request->email}");

        if (!$cachedOtp) {
            return response()->json(['message' => 'Kode OTP telah kadaluarsa atau tidak ditemukan.'], 400);
        }

        if ($cachedOtp !== $request->otp) {
            return response()->json(['message' => 'Kode OTP salah.'], 400);
        }

        return response()->json(['message' => 'OTP verified successfully']);
    }

    public function reset_password(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $cachedOtp = Cache::get("reset_otp_{$request->email}");

        if (!$cachedOtp || $cachedOtp !== $request->otp) {
            return response()->json(['message' => 'Kode OTP salah atau telah kadaluarsa.'], 400);
        }

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Pengguna tidak ditemukan.'], 404);
        }

        if (\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Password harus berbeda dari password lama'], 400);
        }

        $histories = PasswordHistory::where('user_id', $user->id)->get();
        foreach ($histories as $history) {
            if (\Illuminate\Support\Facades\Hash::check($request->password, $history->password)) {
                return response()->json(['message' => 'Anda tidak bisa menggunakan password yang pernah digunakan sebelumnya.'], 400);
            }
        }

        PasswordHistory::create([
            'user_id' => $user->id,
            'password' => $user->password,
        ]);

        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();

        Cache::forget("reset_otp_{$request->email}");

        return response()->json(['message' => 'Password reset successfully']);
    }
}
