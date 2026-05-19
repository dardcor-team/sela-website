<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(UserService $service): JsonResponse
    {
        $users = Cache::tags(['users'])->remember('all_users', 600, function () use ($service) {
            return $service->getAll();
        });

        return response()->json($users);
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return response()->json([]);
        }

        $cacheKey = 'user_search_' . md5($query);

        $users = Cache::tags(['users'])->remember($cacheKey, 300, function () use ($query) {
            return \App\Models\User::where('username', 'ILIKE', "%{$query}%")
                ->orWhere('email', 'ILIKE', "%{$query}%")
                ->get();
        });

        return response()->json($users);
    }

    public function show(UserService $service, string $id): JsonResponse
    {
        $user = Cache::tags(["user_{$id}"])->remember("user_detail_{$id}", 600, function () use ($service, $id) {
            return $service->getById($id);
        });

        return response()->json($user);
    }

    public function update(Request $request, UserService $service, string $id): JsonResponse
    {
        $profile = $service->getById($id);

        $validated = $request->validate([
            'username' => 'sometimes|required|string|max:50',
            'full_name' => 'sometimes|required|string|max:100',
            'avatar_url' => 'nullable|string',
            'class_name' => 'nullable|string|max:100',
        ]);

        $result = $service->update($profile, $validated);

        Cache::tags(["user_{$id}"])->flush();
        Cache::tags(['users'])->flush();

        Cache::tags(["user_{$id}"])->remember("user_detail_{$id}", 600, fn() => $service->getById($id));
        Cache::tags(['users'])->remember('all_users', 600, fn() => $service->getAll());

        return response()->json($result);
    }

    public function destroy(UserService $service, string $id): JsonResponse
    {
        $profile = $service->getById($id);
        $service->delete($profile);

        Cache::tags(["user_{$id}"])->flush();
        Cache::tags(['users'])->flush();
        Cache::tags(["user_abilities_{$id}"])->flush();

        Cache::tags(['users'])->remember('all_users', 600, fn() => $service->getAll());

        return response()->json(null, 204);
    }

    public function getProfileAbilities(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $abilities = Cache::tags(["user_abilities_{$userId}"])->remember("profile_abilities_{$userId}", 600, function () use ($userId) {
            return \App\Models\ProfileAbility::where('user_id', $userId)->get();
        });

        return response()->json(['abilities' => $abilities]);
    }

    public function updateProfileAbilities(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $validated = $request->validate([
            'abilities' => 'required|array',
            'abilities.*' => 'string|max:100',
        ]);

        \App\Models\ProfileAbility::where('user_id', $userId)->delete();

        foreach ($validated['abilities'] as $ability) {
            \App\Models\ProfileAbility::create([
                'user_id' => $userId,
                'ability' => $ability,
            ]);
        }

        Cache::tags(["user_abilities_{$userId}"])->flush();
        Cache::tags(["user_abilities_{$userId}"])->remember("profile_abilities_{$userId}", 600, function () use ($userId) {
            return \App\Models\ProfileAbility::where('user_id', $userId)->get();
        });

        return response()->json(['message' => 'Abilities updated successfully']);
    }

    public function abilities(UserService $service, string $userId): JsonResponse
    {
        $abilities = Cache::tags(["user_abilities_{$userId}"])->remember("abilities_{$userId}", 600, function () use ($service, $userId) {
            return $service->getAbilities($userId);
        });

        return response()->json($abilities);
    }

    public function storeAbility(Request $request, UserService $service, string $userId): JsonResponse
    {
        $validated = $request->validate([
            'ability' => 'required|string|max:100',
        ]);

        $result = $service->createAbility($userId, $validated['ability']);

        Cache::tags(["user_abilities_{$userId}"])->flush();
        Cache::tags(["user_abilities_{$userId}"])->remember("abilities_{$userId}", 600, fn() => $service->getAbilities($userId));

        return response()->json($result, 201);
    }

    public function destroyAbility(UserService $service, string $id): JsonResponse
    {
        $service->deleteAbility($id);

        $userId = auth()->id();
        Cache::tags(["user_abilities_{$userId}"])->flush();
        Cache::tags(["user_abilities_{$userId}"])->remember("abilities_{$userId}", 600, fn() => $service->getAbilities($userId));

        return response()->json(null, 204);
    }

}
