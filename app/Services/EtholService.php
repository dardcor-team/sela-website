<?php
namespace App\Services;
use App\Models\UserEtholSession;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
class EtholService
{
    private string $baseUrl;
    private const USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36';
    private const MAX_REDIRECTS = 15;
    public function __construct()
    {
        $this->baseUrl = config('services.ethol.base_url');
    }
    /**
     * Perform CAS login and return the extracted JWT token + cookies.
     * Does NOT persist to database — the caller is responsible for that.
     *
     * @return array{token: string, cookies: string}
     * @throws \Exception
     */
    public function loginCas(string $email, string $password): array
    {
        $cookieJar = new CookieJar();
        // Step 1: GET the CAS login page to capture the initial cookies and form tokens.
        $casResult = $this->fetchWithRedirects("{$this->baseUrl}/cas", $cookieJar);
        $casHtml = $casResult['body'];
        $casUrl = $casResult['finalUrl'];
        $cookieJar = $casResult['cookieJar'];
        // Step 2: Extract hidden form fields required by CAS.
        if (! preg_match('/name="lt"\s+value="([^"]+)"/', $casHtml, $ltMatches)) {
            throw new \Exception('CAS login page did not return expected "lt" field. The CAS endpoint may have changed.');
        }
        // "execution" is optional — some CAS servers omit it.
        $execution = null;
        if (preg_match('/name="execution"\s+value="([^"]+)"/', $casHtml, $executionMatches)) {
            $execution = $executionMatches[1];
        }
        $lt = $ltMatches[1];
        // Step 3: POST credentials to the CAS login URL.
        $postResult = $this->fetchWithRedirects($casUrl, $cookieJar, 'POST', [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => array_filter([
                'username'   => $email,
                'password'   => $password,
                'lt'         => $lt,
                'execution'  => $execution,
                '_eventId'   => 'submit',
            ], fn ($v) => $v !== null),
        ]);
        $postHtml = $postResult['body'];
        $postUrl = $postResult['finalUrl'];
        $cookieJar = $postResult['cookieJar'];
        // Step 4: Detect failed login before attempting token extraction.
        if (str_contains($postUrl, 'login.pens.ac.id') || str_contains($postHtml, 'class="errors"')) {
            throw new \Exception('Invalid credentials. Please check your username and password.');
        }
        // Step 4 (continued): Extract the JWT token from the page JavaScript.
        if (! preg_match("/localStorage\.setItem\\(['\"]token['\"]\\s*,\\s*['\"]([A-Za-z0-9._-]+)['\"]\\)/", $postHtml, $tokenMatches)) {
            throw new \Exception('Could not extract authentication token from the ETHOL response. The login flow may have changed.');
        }
        $token = $tokenMatches[1];
        // Step 5: Verify API access with the extracted token.
        $apiClient = new Client([
            'headers' => [
                'User-Agent' => self::USER_AGENT,
                'token'      => $token,
            ],
            'http_errors' => false,
        ]);
        $apiResponse = $apiClient->get("{$this->baseUrl}/api/kuliah?tahun=2025&semester=2");
        $apiBody = (string) $apiResponse->getBody();
        $apiData = json_decode($apiBody, true);
        if (! is_array($apiData)) {
            throw new \Exception('ETHOL API verification failed: expected a JSON array response but received unexpected data.');
        }
        return [
            'token'   => $token,
            'cookies' => json_encode($cookieJar->toArray()),
        ];
    }
    /**
     * Persist ETHOL session to the database for a given user.
     */
    public function saveSession(int $userId, string $etholToken, string $etholCookies): void
    {
        // Delete any existing session first to avoid decrypting stale data
        // encrypted with a different APP_KEY (causes MAC invalid errors).
        UserEtholSession::where('user_id', $userId)->delete();
        UserEtholSession::create([
            'user_id'       => $userId,
            'ethol_token'   => $etholToken,
            'ethol_cookies' => $etholCookies,
        ]);
    }
    /**
     * Decode the JWT payload and return it as an associative array.
     * Returns null if the token is malformed.
     */
    public function decodeJwtPayload(string $token): ?array
    {
        $parts = explode('.', $token);
        if (count($parts) < 2) {
            return null;
        }
        $padded  = str_pad(strtr($parts[1], '-_', '+/'), (int) (ceil(strlen($parts[1]) / 4) * 4), '=');
        $payload = json_decode(base64_decode($padded), true);
        return is_array($payload) ? $payload : null;
    }
    /**
     * Remove the stored ETHOL session for the given user.
     */
    public function logout(int $userId): void
    {
        UserEtholSession::where('user_id', $userId)->delete();
    }
    /**
     * Check whether a stored ETHOL session exists for the given user.
     */
    public function isLoggedIn(int $userId): bool
    {
        return UserEtholSession::where('user_id', $userId)->exists();
    }
    /**
     * Retrieve the ETHOL session for the given user, or throw if not found.
     *
     * @throws \Exception
     */
    public function getSession(int $userId): UserEtholSession
    {
        $session = UserEtholSession::where('user_id', $userId)->first();
        if (! $session) {
            throw new \Exception("No ETHOL session found for user {$userId}. Please log in first.");
        }
        // Verify encrypted fields are still readable (APP_KEY may have changed).
        try {
            $session->ethol_token;
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            $session->delete();
            throw new \Exception('ETHOL session expired or corrupted. Please log in again.');
        }
        return $session;
    }
    /**
     * Return the raw JWT token from the stored session, or null if none exists.
     */
    public function getAuthToken(int $userId): ?string
    {
        $session = UserEtholSession::where('user_id', $userId)->first();
        if (! $session) {
            return null;
        }
        try {
            return $session->ethol_token;
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            $session->delete();
            return null;
        }
    }
    /**
     * Perform an HTTP request manually following redirects, maintaining the provided cookie jar.
     *
     * Using raw Guzzle with `allow_redirects => false` is intentional here:
     * Laravel's Http facade auto-follows redirects and does not expose per-hop cookie
     * merging or method switching (POST → GET on 302/303), which CAS requires.
     *
     * @param  array<string, mixed>  $options  Additional Guzzle request options.
     * @return array{body: string, finalUrl: string, cookieJar: CookieJar}
     *
     * @throws \Exception
     */
    public function fetchWithRedirects(string $url, CookieJar $cookieJar, string $method = 'GET', array $options = []): array
    {
        $client = new Client([
            'cookies'         => $cookieJar,
            'allow_redirects' => false,
            'http_errors'     => false,
        ]);
        // Always attach the User-Agent to every request in the loop.
        $options['headers'] = array_merge(
            ['User-Agent' => self::USER_AGENT],
            $options['headers'] ?? []
        );
        $body = '';
        $redirectCount = 0;
        while ($redirectCount < self::MAX_REDIRECTS) {
            $response = $client->request($method, $url, $options);
            $statusCode = $response->getStatusCode();
            $body = (string) $response->getBody();
            // Not a redirect — we have the final response.
            if ($statusCode < 300 || $statusCode >= 400) {
                break;
            }
            $location = $response->getHeaderLine('Location');
            if (empty($location)) {
                // Redirect with no Location header; treat as final response.
                break;
            }
            // Resolve relative redirects against the current URL.
            if (! str_starts_with($location, 'http')) {
                $parsed = parse_url($url);
                $scheme = $parsed['scheme'] ?? 'https';
                $host = $parsed['host'] ?? '';
                $port = isset($parsed['port']) ? ":{$parsed['port']}" : '';
                $location = str_starts_with($location, '/')
                    ? "{$scheme}://{$host}{$port}{$location}"
                    : "{$scheme}://{$host}{$port}/" . ltrim($location, '/');
            }
            $url = $location;
            // 302/303 converts POST to GET and drops the body options.
            if (in_array($statusCode, [302, 303], true) && $method === 'POST') {
                $method = 'GET';
                unset($options['form_params'], $options['body'], $options['json']);
                // Keep only User-Agent in headers (drop Content-Type for GET).
                $options['headers'] = ['User-Agent' => self::USER_AGENT];
            }
            $redirectCount++;
        }
        if ($redirectCount >= self::MAX_REDIRECTS) {
            throw new \Exception("Exceeded maximum redirect limit (" . self::MAX_REDIRECTS . ") while following {$url}");
        }
        return [
            'body'      => $body,
            'finalUrl'  => $url,
            'cookieJar' => $cookieJar,
        ];
    }
    // Private Helpers
    /**
     * Build the standard ETHOL API request headers.
     */
    private function makeEtholHeaders(string $token): array
    {
        return [
            'User-Agent' => self::USER_AGENT,
            'token'      => $token,
        ];
    }
    /**
     * Extract the student nomor from the JWT payload.
     * Returns null if the token is malformed.
     */
    private function getUserNomorFromToken(string $token): ?int
    {
        $parts = explode('.', $token);
        if (count($parts) < 2) {
            return null;
        }
        // Base64url → base64 padding fix, then decode.
        $padded  = str_pad(strtr($parts[1], '-_', '+/'), (int) (ceil(strlen($parts[1]) / 4) * 4), '=');
        $payload = json_decode(base64_decode($padded), true);
        return isset($payload['nomor']) ? (int) $payload['nomor']
             : (isset($payload['sub'])  ? (int) $payload['sub'] : null);
    }
    /**
     * Build the array of semester descriptors for the past three years.
     * Yields 6 entries: (currentYear-2, currentYear-1, currentYear) × (1, 2).
     */
    private function buildSemesters(): array
    {
        $currentYear = (int) date('Y');
        $semesters   = [];
        for ($y = $currentYear - 2; $y <= $currentYear; $y++) {
            $semesters[] = ['tahun' => $y, 'semester' => 1];
            $semesters[] = ['tahun' => $y, 'semester' => 2];
        }
        return $semesters;
    }
    // Data Fetching Methods
    /**
     * Fetch the current semester schedule for a user.
     *
     * @throws \Exception
     */
    public function getSchedule(int $userId): array
    {
        return Cache::remember("ethol_schedule_{$userId}", 900, function () use ($userId) {
            return $this->fetchSchedule($userId);
        });
    }

    private function fetchSchedule(int $userId): array
    {
        $session = $this->getSession($userId);
        $token   = $session->ethol_token;
        $headers = $this->makeEtholHeaders($token);
        // Fetch the list of subjects for the current semester.
        $subjectResponse = Http::withHeaders($headers)
            ->get("{$this->baseUrl}/api/kuliah?tahun=2025&semester=2");
        if (! $subjectResponse->successful()) {
            UserEtholSession::where('user_id', $userId)->delete();
            throw new \Exception('Session expired');
        }
        $subjects = $subjectResponse->json();
        if (! is_array($subjects) || empty($subjects)) {
            return [];
        }
        // Build the minimal kuliahs payload for the schedule endpoint.
        $kuliahs = array_map(fn ($s) => [
            'nomor'      => $s['nomor'],
            'jenisSchema' => $s['jenisSchema'],
        ], $subjects);
        // Index subjects by nomor for O(1) lookup when joining.
        $subjectMap = [];
        foreach ($subjects as $s) {
            $subjectMap[$s['nomor']] = $s;
        }
        // Fetch the schedule (hari kuliah) entries.
        $scheduleResponse = Http::withHeaders($headers)
            ->post("{$this->baseUrl}/api/kuliah/hari-kuliah-in", [
                'kuliahs'  => $kuliahs,
                'tahun'    => 2025,
                'semester' => 2,
            ]);
        if (! $scheduleResponse->successful()) {
            UserEtholSession::where('user_id', $userId)->delete();
            throw new \Exception('Session expired');
        }
        $entries = $scheduleResponse->json();
        if (! is_array($entries)) {
            return [];
        }
        // Join schedule entries with their subject data and build the output shape.
        $result = [];
        foreach ($entries as $entry) {
            $subjectNomor = $entry['kuliah'] ?? null;
            $subject      = $subjectNomor ? ($subjectMap[$subjectNomor] ?? null) : null;
            if (! $subject) {
                continue;
            }
            // Build the dosen title by joining prefix + name + suffix.
            $dosenParts = array_filter([
                trim($subject['gelar_dpn'] ?? ''),
                trim($subject['dosen'] ?? ''),
                trim($subject['gelar_blk'] ?? ''),
            ]);
            $dosenTitle = implode(' ', $dosenParts);
            $result[] = [
                'id'          => $entry['kuliah'],
                'subjectName' => $subject['matakuliah']['nama'] ?? '',
                'dosen'       => $subject['dosen'] ?? '',
                'dosenTitle'  => $dosenTitle ?: '-',
                'kodeKelas'   => $subject['kode_kelas'] ?? '',
                'pararel'     => $subject['pararel'] ?? '',
                'hari'        => $entry['hari'] ?? '',
                'jamAwal'     => $entry['jam_awal'] ?? '',
                'jamAkhir'    => $entry['jam_akhir'] ?? '',
                'nomorHari'   => $entry['nomor_hari'] ?? null,
                'ruang'       => $entry['ruang'] ?? '',
            ];
        }
        return $result;
    }
    /**
     * Fetch homework (tugas) across the last three academic years for a user.
     *
     * @throws \Exception
     */
    public function getHomework(int $userId): array
    {
        return Cache::remember("ethol_homework_{$userId}", 900, function () use ($userId) {
            return $this->fetchHomework($userId);
        });
    }

    private function fetchHomework(int $userId): array
    {
        $session   = $this->getSession($userId);
        $token     = $session->ethol_token;
        $headers   = $this->makeEtholHeaders($token);
        $semesters = $this->buildSemesters();
        $baseUrl   = $this->baseUrl;
        // Wave 1: fetch all subjects concurrently across 6 semesters.
        $subjectResponses = Http::pool(fn (Pool $pool) => array_map(
            fn ($s) => $pool
                ->withHeaders($headers)
                ->get("{$baseUrl}/api/kuliah?tahun={$s['tahun']}&semester={$s['semester']}"),
            $semesters
        ));
        // Collect all subjects, tagging each with its semester context.
        $allSubjects = [];
        foreach ($subjectResponses as $i => $response) {
            if (! ($response instanceof \Illuminate\Http\Client\Response) || ! $response->successful()) {
                continue; // Graceful degradation: skip failed semesters.
            }
            $data = $response->json();
            if (! is_array($data)) {
                continue;
            }
            foreach ($data as $subject) {
                $allSubjects[] = [
                    'subject'  => $subject,
                    'tahun'    => $semesters[$i]['tahun'],
                    'semester' => $semesters[$i]['semester'],
                ];
            }
        }
        if (empty($allSubjects)) {
            return [];
        }
        // Wave 2: fetch tugas for every subject concurrently.
        $tugasResponses = Http::pool(fn (Pool $pool) => array_map(
            fn ($item) => $pool
                ->withHeaders($headers)
                ->get(
                    "{$baseUrl}/api/tugas",
                    [
                        'kuliah'      => $item['subject']['nomor'],
                        'jenisSchema' => $item['subject']['jenisSchema'],
                    ]
                ),
            $allSubjects
        ));
        // Build the flat homework list.
        $result = [];
        foreach ($tugasResponses as $i => $response) {
            if (! ($response instanceof \Illuminate\Http\Client\Response) || ! $response->successful()) {
                continue;
            }
            $tugasList = $response->json();
            if (! is_array($tugasList)) {
                continue;
            }
            $subjectContext = $allSubjects[$i];
            $subject        = $subjectContext['subject'];
            $tahun          = $subjectContext['tahun'];
            $semester       = $subjectContext['semester'];
            foreach ($tugasList as $tugas) {
                // Determine submission status.
                $submissionTime = $tugas['submission_time'] ?? null;
                $deadline       = $tugas['deadline'] ?? null;
                if ($submissionTime === null) {
                    $status = 'not_submitted';
                } elseif ($deadline !== null && strtotime($submissionTime) > strtotime($deadline)) {
                    $status = 'late';
                } else {
                    $status = 'on_time';
                }
                $result[] = [
                    'id'                      => $tugas['id'] ?? null,
                    'title'                   => $tugas['title'] ?? '',
                    'description'             => $tugas['description'] ?? '',
                    'deadline'                => $deadline,
                    'deadlineIndonesia'       => $tugas['deadline_indonesia'] ?? null,
                    'submissionTime'          => $submissionTime,
                    'submissionTimeIndonesia' => $tugas['submission_time_indonesia'] ?? null,
                    'status'                  => $status,
                    'subjectName'             => $subject['matakuliah']['nama'] ?? '',
                    'subjectNomor'            => $subject['nomor'] ?? null,
                    'tahun'                   => $tahun,
                    'semester'                => $semester,
                    'fileCount'               => count($tugas['file'] ?? []),
                ];
            }
        }
        // Sort by deadline descending (nulls last).
        usort($result, function ($a, $b) {
            if ($a['deadline'] === null && $b['deadline'] === null) return 0;
            if ($a['deadline'] === null) return 1;
            if ($b['deadline'] === null) return -1;
            return strtotime($b['deadline']) <=> strtotime($a['deadline']);
        });
        return $result;
    }
    /**
     * Fetch attendance (presensi) across the last three academic years for a user.
     *
     * @throws \Exception
     */
    public function getAttendance(int $userId): array
    {
        return Cache::remember("ethol_attendance_{$userId}", 900, function () use ($userId) {
            return $this->fetchAttendance($userId);
        });
    }

    private function fetchAttendance(int $userId): array
    {
        $session    = $this->getSession($userId);
        $token      = $session->ethol_token;
        $headers    = $this->makeEtholHeaders($token);
        $semesters  = $this->buildSemesters();
        $baseUrl    = $this->baseUrl;
        $userNomor  = $this->getUserNomorFromToken($token);
        // Wave 1: fetch subjects for all semesters concurrently.
        $subjectResponses = Http::pool(fn (Pool $pool) => array_map(
            fn ($s) => $pool
                ->withHeaders($headers)
                ->get("{$baseUrl}/api/kuliah?tahun={$s['tahun']}&semester={$s['semester']}"),
            $semesters
        ));
        $allSubjects = [];
        foreach ($subjectResponses as $i => $response) {
            if (! ($response instanceof \Illuminate\Http\Client\Response) || ! $response->successful()) {
                continue;
            }
            $data = $response->json();
            if (! is_array($data)) {
                continue;
            }
            foreach ($data as $subject) {
                $allSubjects[] = [
                    'subject'  => $subject,
                    'tahun'    => $semesters[$i]['tahun'],
                    'semester' => $semesters[$i]['semester'],
                ];
            }
        }
        if (empty($allSubjects)) {
            return [];
        }
        // Wave 2: fetch presensi for every subject concurrently.
        $presensiResponses = Http::pool(fn (Pool $pool) => array_map(
            fn ($item) => $pool
                ->withHeaders($headers)
                ->get(
                    "{$baseUrl}/api/presensi/riwayat",
                    [
                        'kuliah'       => $item['subject']['nomor'],
                        'jenis_schema' => $item['subject']['jenisSchema'],
                        'nomor'        => $userNomor,
                    ]
                ),
            $allSubjects
        ));
        // Build attendance items, filtering subjects with zero presensi.
        $result = [];
        foreach ($presensiResponses as $i => $response) {
            if (! ($response instanceof \Illuminate\Http\Client\Response) || ! $response->successful()) {
                continue;
            }
            $presensiList = $response->json();
            if (! is_array($presensiList) || count($presensiList) === 0) {
                continue; // Filter subjects with 0 presensi records.
            }
            $subjectContext = $allSubjects[$i];
            $subject        = $subjectContext['subject'];
            $tahun          = $subjectContext['tahun'];
            $semester       = $subjectContext['semester'];
            $result[] = [
                'subjectName'      => $subject['matakuliah']['nama'] ?? '',
                'subjectNomor'     => $subject['nomor'] ?? null,
                'tahun'            => $tahun,
                'semester'         => $semester,
                'date'             => $presensiList[0]['waktu_indonesia'] ?? '',
                'totalSessions'    => count($presensiList),
                'attendedSessions' => count($presensiList),
                'attendanceRate'   => 100,
                'history'          => array_map(
                    fn ($p) => ['date' => $p['waktu_indonesia'], 'key' => $p['key']],
                    $presensiList
                ),
            ];
        }
        // Sort: year desc → semester desc → subject name asc.
        usort($result, function ($a, $b) {
            if ($a['tahun'] !== $b['tahun']) {
                return $b['tahun'] <=> $a['tahun'];
            }
            if ($a['semester'] !== $b['semester']) {
                return $b['semester'] <=> $a['semester'];
            }
            return strcmp($a['subjectName'], $b['subjectName']);
        });
        return $result;
    }
}
