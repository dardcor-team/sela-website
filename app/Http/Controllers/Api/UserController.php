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

    public function requestLecturerAccess(Request $request): JsonResponse
    {
        $user = $request->user();

        $adminEmail = env('MAIL_USERNAME', 'administratorsela@gmail.com');
        if (!$adminEmail || $adminEmail === 'null') {
            $adminEmail = 'administratorsela@gmail.com';
        }

        try {
            $approveUrl = \Illuminate\Support\Facades\URL::signedRoute(
                'users.approve-lecturer',
                ['user' => $user->id]
            );

            \Illuminate\Support\Facades\Mail::to($adminEmail)->send(
                new \App\Mail\LecturerAccessRequestMail($user, $approveUrl)
            );
            return response()->json(['message' => 'Lecturer access request sent successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to send request.', 'error' => $e->getMessage()], 500);
        }
    }

    public function approveLecturerAccess(Request $request, \App\Models\User $user)
    {
        if ($user->role === 'lecturer') {
            return response()->view('emails.lecturer_approved', [
                'name' => $user->profile->full_name ?? $user->username,
                'alreadyApproved' => true,
            ]);
        }

        $userId = $user->id;

        $groupIds = DB::table('group_members')->where('user_id', $userId)->pluck('group_id');
        $createdGroupIds = DB::table('groups')->where('created_by', $userId)->pluck('id');

        DB::table('subtask_progress')->where('user_id', $userId)->delete();

        $personalTaskIds = DB::table('tasks')
            ->where('created_by', $userId)
            ->where(function ($q) {
                $q->where('is_group', false)->orWhereNull('group_id');
            })
            ->pluck('id');

        if ($personalTaskIds->isNotEmpty()) {
            DB::table('subtask_progress')
                ->whereIn('subtask_id', function ($q) use ($personalTaskIds) {
                    $q->select('id')->from('subtasks')->whereIn('task_id', $personalTaskIds);
                })
                ->delete();
            DB::table('subtasks')->whereIn('task_id', $personalTaskIds)->delete();
            DB::table('task_links')->whereIn('task_id', $personalTaskIds)->delete();
            DB::table('task_files')->whereIn('task_id', $personalTaskIds)->delete();
            DB::table('tasks')->whereIn('id', $personalTaskIds)->delete();
        }

        DB::table('group_members')->where('user_id', $userId)->delete();

        foreach ($createdGroupIds as $gid) {
            $remainingMembers = DB::table('group_members')->where('group_id', $gid)->count();
            if ($remainingMembers === 0) {
                $taskIds = DB::table('tasks')->where('group_id', $gid)->pluck('id');
                if ($taskIds->isNotEmpty()) {
                    DB::table('subtask_progress')
                        ->whereIn('subtask_id', function ($q) use ($taskIds) {
                            $q->select('id')->from('subtasks')->whereIn('task_id', $taskIds);
                        })
                        ->delete();
                    DB::table('subtasks')->whereIn('task_id', $taskIds)->delete();
                    DB::table('task_links')->whereIn('task_id', $taskIds)->delete();
                    DB::table('task_files')->whereIn('task_id', $taskIds)->delete();
                    DB::table('tasks')->whereIn('id', $taskIds)->delete();
                }
                DB::table('groups')->where('id', $gid)->delete();
            } else {
                $newLeader = DB::table('group_members')->where('group_id', $gid)->first();
                if ($newLeader) {
                    DB::table('group_members')->where('id', $newLeader->id)->update(['role' => 'leader']);
                }
            }
        }

        DB::table('profile_abilities')->where('user_id', $userId)->delete();
        DB::table('notifications')->where('user_id', $userId)->delete();

        $user->update(['role' => 'lecturer']);
        $user->tokens()->delete();

        Cache::tags(["user_{$userId}"])->flush();
        Cache::tags(['users'])->flush();
        Cache::tags(["user_tasks_{$userId}"])->flush();
        Cache::tags(["user_groups_{$userId}"])->flush();
        Cache::tags(["dashboard_{$userId}"])->flush();
        Cache::tags(["user_abilities_{$userId}"])->flush();
        Cache::tags(["user_notifications_{$userId}"])->flush();

        foreach ($groupIds as $gid) {
            Cache::tags(["group_{$gid}"])->flush();
            Cache::tags(["group_members_{$gid}"])->flush();
        }

        return response()->view('emails.lecturer_approved', [
            'name' => $user->profile->full_name ?? $user->username,
            'alreadyApproved' => false,
        ]);
    }
}
