<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Services\GroupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GroupController extends Controller
{
    public function getByUser(Request $request, $user_id, GroupService $service)
    {
        $search = $request->search;

        $cacheKey = "groups_user_{$user_id}" . ($search ? '_' . md5($search) : '');

        $groups = Cache::tags(["user_groups_{$user_id}"])->remember($cacheKey, 600, function () use ($user_id, $search, $service) {
            return $service->getGroupsByUser($user_id, $search);
        });

        return response()->json([
            "groups" => $groups,
        ]);
    }

    public function detail($group_id, GroupService $service)
    {
        $group = Cache::tags(["group_{$group_id}"])->remember("group_detail_{$group_id}", 600, function () use ($group_id, $service) {
            return $service->getGroupDetail($group_id);
        });

        return response()->json($group);
    }

    public function store(Request $request, GroupService $service)
    {
        $request->validate([
            'course_name' => 'required|string|max:100',
            'member_limit' => 'nullable|integer|min:1',
            'group_number' => 'required|integer|min:1',
            'class_name' => 'nullable|string|max:100',
            'lecture_code' => 'nullable|string|max:100',
        ]);

        $group = $service->createGroup($request);

        $userId = auth()->id();
        Cache::tags(["user_groups_{$userId}"])->flush();
        Cache::tags(["dashboard_{$userId}"])->flush();

        Cache::tags(["user_groups_{$userId}"])->remember("groups_user_{$userId}", 600, fn() => $service->getGroupsByUser($userId));
        Cache::tags(["dashboard_{$userId}"])->remember("dashboard_{$userId}", 300, fn() => app(DashboardService::class)->getDashboard($userId));

        return response()->json([
            "message" => "Group created",
            "data" => $group,
        ], 201);
    }

    public function join(Request $request, GroupService $service)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $data = $service->joinGroup(
            $request->code,
            auth()->id()
        );

        $userId = auth()->id();
        $groupId = $data->id ?? null;

        Cache::tags(["user_groups_{$userId}"])->flush();
        Cache::tags(["dashboard_{$userId}"])->flush();
        if ($groupId) {
            Cache::tags(["group_{$groupId}"])->flush();
            Cache::tags(["group_members_{$groupId}"])->flush();

            Cache::tags(["group_{$groupId}"])->remember("group_detail_{$groupId}", 600, fn() => $service->getGroupDetail($groupId));
        }

        Cache::tags(["user_groups_{$userId}"])->remember("groups_user_{$userId}", 600, fn() => $service->getGroupsByUser($userId));
        Cache::tags(["dashboard_{$userId}"])->remember("dashboard_{$userId}", 300, fn() => app(DashboardService::class)->getDashboard($userId));

        return response()->json([
            "message" => "Join group success",
            "data" => $data,
        ]);
    }

    public function destroy($id)
    {
        $group = \App\Models\Group::findOrFail($id);
        $group->delete();

        $userId = auth()->id();
        Cache::tags(["group_{$id}"])->flush();
        Cache::tags(["group_members_{$id}"])->flush();
        Cache::tags(["user_groups_{$userId}"])->flush();
        Cache::tags(["dashboard_{$userId}"])->flush();

        Cache::tags(["dashboard_{$userId}"])->remember("dashboard_{$userId}", 300, fn() => app(DashboardService::class)->getDashboard($userId));

        return response()->json(null, 204);
    }
}
