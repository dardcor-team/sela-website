<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\EtholService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class EtholController extends Controller
{
    /**
     * Log in via ETHOL CAS (public route — no Sanctum token required).
     * Auto-creates or finds the user from the ETHOL JWT, then returns a Sanctum token.
     */
    public function login(Request $request, EtholService $service): JsonResponse
    {
        $validated = $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            // Step 1: CAS login — get ETHOL JWT + cookies.
            $cas = $service->loginCas($validated['email'], $validated['password']);
            $etholToken  = $cas['token'];
            $etholCookies = $cas['cookies'];

            // Step 2: Decode JWT to extract user info.
            $payload = $service->decodeJwtPayload($etholToken);

            if (! $payload || empty($payload['nama'])) {
                throw new \Exception('Could not extract user information from ETHOL token.');
            }

            $nrp   = $payload['nipnrp'] ?? null;
            $nama  = $payload['nama'];
            // Build email: use the provided login email.
            $email = $validated['email'];

            // Step 3: Find or create the user.
            $user = User::where('email', $email)->first();

            if (! $user) {
                $user = User::create([
                    'username' => $nrp ?? Str::slug($nama, '_'),
                    'email'    => $email,
                    'password' => bcrypt(Str::random(32)),
                    'class'    => null,
                    'role'     => 'student',
                ]);
            }

            // Step 4: Persist the ETHOL session.
            $service->saveSession($user->id, $etholToken, $etholCookies);

            // Step 5: Issue a Sanctum token.
            $token = $user->createToken('ethol-auth')->plainTextToken;

            return response()->json([
                'user'  => $user,
                'token' => $token,
            ]);
        } catch (\Throwable $e) {
            $status = str_contains($e->getMessage(), 'Invalid credentials') ? 401 : 500;

            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], $status);
        }
    }

    /**
     * Remove the stored ETHOL session.
     */
    public function logout(Request $request, EtholService $service): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $service->logout($userId);

            Cache::tags(["ethol_{$userId}"])->flush();

            return response()->json([
                'success' => true,
                'message' => 'Logged out from ETHOL successfully.',
            ]);
        } catch (\Throwable $e) {
            $status = (str_contains($e->getMessage(), 'expired') || str_contains($e->getMessage(), 'not logged in')) ? 401 : 500;

            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], $status);
        }
    }

    /**
     * Get the current semester schedule.
     */
    public function schedule(Request $request, EtholService $service): JsonResponse
    {
        try {
            $userId = $request->user()->id;

            $data = Cache::tags(["ethol_{$userId}"])->remember("ethol_schedule_{$userId}", 900, function () use ($userId, $service) {
                return $service->getSchedule($userId);
            });

            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        } catch (\Throwable $e) {
            $status = (str_contains($e->getMessage(), 'expired') || str_contains($e->getMessage(), 'not logged in') || str_contains($e->getMessage(), 'No ETHOL session')) ? 401 : 500;

            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], $status);
        }
    }

    /**
     * Get homework assignments across semesters.
     */
    public function homework(Request $request, EtholService $service): JsonResponse
    {
        try {
            $userId = $request->user()->id;

            $data = Cache::tags(["ethol_{$userId}"])->remember("ethol_homework_{$userId}", 900, function () use ($userId, $service) {
                return $service->getHomework($userId);
            });

            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        } catch (\Throwable $e) {
            $status = (str_contains($e->getMessage(), 'expired') || str_contains($e->getMessage(), 'not logged in') || str_contains($e->getMessage(), 'No ETHOL session')) ? 401 : 500;

            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], $status);
        }
    }

    /**
     * Get attendance history across semesters.
     */
    public function attendance(Request $request, EtholService $service): JsonResponse
    {
        try {
            $userId = $request->user()->id;

            $data = Cache::tags(["ethol_{$userId}"])->remember("ethol_attendance_{$userId}", 900, function () use ($userId, $service) {
                return $service->getAttendance($userId);
            });

            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        } catch (\Throwable $e) {
            $status = (str_contains($e->getMessage(), 'expired') || str_contains($e->getMessage(), 'not logged in') || str_contains($e->getMessage(), 'No ETHOL session')) ? 401 : 500;

            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], $status);
        }
    }

    /**
     * Get the stored ETHOL JWT token.
     */
    public function token(Request $request, EtholService $service): JsonResponse
    {
        try {
            $token = $service->getAuthToken($request->user()->id);

            if (! $token) {
                return response()->json([
                    'success' => false,
                    'error'   => 'not logged in',
                ], 401);
            }

            return response()->json([
                'success' => true,
                'data'    => $token,
            ]);
        } catch (\Throwable $e) {
            $status = (str_contains($e->getMessage(), 'expired') || str_contains($e->getMessage(), 'not logged in')) ? 401 : 500;

            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], $status);
        }
    }
}
