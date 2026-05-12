<?php

namespace App\Services;

use App\Models\DeviceToken;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Google\Auth\Credentials\ServiceAccountCredentials;

class FcmService
{
    private ?string $projectId = null;
    private ?array $credentials = null;

    /**
     * Load credentials from FIREBASE_CREDENTIALS env var (base64 or raw JSON),
     * or fall back to storage/app/firebase-credentials.json for local dev.
     */
    private function loadCredentials(): bool
    {
        $envJson = env('FIREBASE_CREDENTIALS');

        if ($envJson) {
            // Support both base64-encoded and raw JSON
            $decoded = base64_decode($envJson, true);
            $json = ($decoded && str_starts_with(trim($decoded), '{')) ? $decoded : $envJson;
            $this->credentials = json_decode($json, true);

            if ($this->credentials) {
                $this->projectId = $this->credentials['project_id'] ?? null;
                if (!$this->projectId) {
                    Log::error('FCM: project_id missing in FIREBASE_CREDENTIALS');
                    return false;
                }
                return true;
            }

            Log::error('FCM: FIREBASE_CREDENTIALS env var set but failed to parse as JSON');
            return false;
        }

        // Fallback: file path (local development only)
        $filePath = storage_path('app/firebase-credentials.json');
        if (!file_exists($filePath)) {
            Log::warning('FCM: No credentials found. Set FIREBASE_CREDENTIALS env var (production) or place firebase-credentials.json in storage/app/ (local).');
            return false;
        }

        $this->credentials = json_decode(file_get_contents($filePath), true);
        $this->projectId = $this->credentials['project_id'] ?? null;

        if (!$this->projectId) {
            Log::warning('FCM: project_id not found in credentials file');
            return false;
        }

        return true;
    }

    public function sendToUser($userId, $title, $message, $data = [])
    {
        $tokens = DeviceToken::where('user_id', $userId)->pluck('token')->all();

        if (empty($tokens)) {
            return;
        }

        if (!$this->loadCredentials()) {
            return;
        }

        $accessToken = $this->getCachedAccessToken();

        if (!$accessToken) {
            Log::error('FCM: Failed to obtain OAuth access token — push skipped');
            return;
        }

        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

        foreach ($tokens as $deviceToken) {
            $this->sendToToken($deviceToken, $title, $message, $data, $accessToken, $url);
        }
    }

    private function sendToToken($token, $title, $body, $data, $accessToken, $url)
    {
        try {
            $payload = [
                'message' => [
                    'token' => $token,
                    'notification' => [
                        'title' => $title,
                        'body'  => $body,
                    ],
                    'data' => array_map('strval', $data),
                    'android' => [
                        'priority' => 'high',
                        'ttl' => '0s',
                        'notification' => [
                            'channel_id' => 'sela_high_importance_channel',
                        ],
                    ],
                    'apns' => [
                        'headers' => [
                            'apns-priority' => '10',
                        ],
                    ],
                ],
            ];

            $response = Http::withToken($accessToken)
                ->timeout(5)
                ->post($url, $payload);

            if ($response->failed()) {
                Log::error('FCM send failed', [
                    'status'       => $response->status(),
                    'body'         => $response->body(),
                    'token_prefix' => substr($token, 0, 20),
                ]);

                // Stale / invalid token — remove it so we don't retry
                if ($response->status() === 404 || $response->status() === 400) {
                    DeviceToken::where('token', $token)->delete();
                }
            }
        } catch (\Exception $e) {
            Log::error('FCM exception: ' . $e->getMessage());
        }
    }

    /**
     * Cache the OAuth access token for 55 min.
     * Does NOT cache null — avoids poisoning the cache on transient errors.
     */
    private function getCachedAccessToken(): ?string
    {
        $cached = Cache::get('fcm_access_token');
        if ($cached) {
            return $cached;
        }

        $token = $this->fetchAccessToken();
        if ($token) {
            Cache::put('fcm_access_token', $token, 55 * 60);
        }

        return $token;
    }

    private function fetchAccessToken(): ?string
    {
        $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];

        // Use google/auth library if available (preferred)
        if (class_exists(ServiceAccountCredentials::class)) {
            try {
                $cred = new ServiceAccountCredentials($scopes, $this->credentials);
                $token = $cred->fetchAuthToken();
                return $token['access_token'] ?? null;
            } catch (\Exception $e) {
                Log::error('FCM: ServiceAccountCredentials failed: ' . $e->getMessage());
                return null;
            }
        }

        // Manual JWT flow (fallback when google/auth is not installed)
        try {
            $now    = time();
            $header = $this->base64UrlEncode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
            $claim  = $this->base64UrlEncode(json_encode([
                'iss'   => $this->credentials['client_email'],
                'scope' => implode(' ', $scopes),
                'aud'   => 'https://oauth2.googleapis.com/token',
                'iat'   => $now,
                'exp'   => $now + 3600,
            ]));

            $signature = '';
            openssl_sign("$header.$claim", $signature, $this->credentials['private_key'], 'SHA256');
            $jwt = "$header.$claim." . $this->base64UrlEncode($signature);

            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $jwt,
            ]);

            if (!$response->successful()) {
                Log::error('FCM OAuth token request failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return null;
            }

            return $response->json('access_token');
        } catch (\Exception $e) {
            Log::error('FCM: Manual JWT token fetch failed: ' . $e->getMessage());
            return null;
        }
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function registerToken($userId, $token, $platform = 'android')
    {
        return DeviceToken::updateOrCreate(
            ['token' => $token],
            ['user_id' => $userId, 'platform' => $platform]
        );
    }

    public function removeToken($token)
    {
        return DeviceToken::where('token', $token)->delete();
    }

    public function removeUserTokens($userId)
    {
        return DeviceToken::where('user_id', $userId)->delete();
    }
}
