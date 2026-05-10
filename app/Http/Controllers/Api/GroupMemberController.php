<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GroupMemberService;
use App\Services\GroupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GroupMemberController extends Controller
{
    public function index(GroupMemberService $service, string $groupId): JsonResponse
    {
        $members = Cache::tags(["group_members_{$groupId}"])->remember("group_members_{$groupId}", 600, function () use ($groupId, $service) {
            return $service->getGroupMembers($groupId);
        });

        return response()->json($members);
    }

    public function store(Request $request, GroupMemberService $service, string $groupId): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|uuid|exists:profiles,id',
            'role' => 'nullable|string',
        ]);

        $validated['group_id'] = $groupId;

        $result = $service->addMember($validated);

        Cache::tags(["group_members_{$groupId}"])->flush();
        Cache::tags(["group_{$groupId}"])->flush();
        Cache::tags(["user_groups_{$validated['user_id']}"])->flush();

        Cache::tags(["group_members_{$groupId}"])->remember("group_members_{$groupId}", 600, fn() => $service->getGroupMembers($groupId));
        Cache::tags(["group_{$groupId}"])->remember("group_detail_{$groupId}", 600, fn() => app(GroupService::class)->getGroupDetail($groupId));

        return response()->json($result, 201);
    }

    public function update(Request $request, GroupMemberService $service, string $id): JsonResponse
    {
        $member = $service->getById($id);

        $validated = $request->validate([
            'role' => 'required|string',
        ]);

        $result = $service->update($member, $validated);

        $groupId = $member->group_id;
        Cache::tags(["group_members_{$groupId}"])->flush();
        Cache::tags(["group_{$groupId}"])->flush();

        Cache::tags(["group_members_{$groupId}"])->remember("group_members_{$groupId}", 600, fn() => $service->getGroupMembers($groupId));
        Cache::tags(["group_{$groupId}"])->remember("group_detail_{$groupId}", 600, fn() => app(GroupService::class)->getGroupDetail($groupId));

        return response()->json($result);
    }

    public function destroy(GroupMemberService $service, string $groupId, string $userId): JsonResponse
    {
        $service->removeMember($groupId, $userId);

        Cache::tags(["group_members_{$groupId}"])->flush();
        Cache::tags(["group_{$groupId}"])->flush();
        Cache::tags(["user_groups_{$userId}"])->flush();
        Cache::tags(["dashboard_{$userId}"])->flush();

        Cache::tags(["group_members_{$groupId}"])->remember("group_members_{$groupId}", 600, fn() => $service->getGroupMembers($groupId));
        Cache::tags(["group_{$groupId}"])->remember("group_detail_{$groupId}", 600, fn() => app(GroupService::class)->getGroupDetail($groupId));

        return response()->json(null, 204);
    }
}
