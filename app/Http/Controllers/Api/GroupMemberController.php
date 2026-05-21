<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GroupMemberService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GroupMemberController extends Controller
{
    public function index(GroupMemberService $service, string $groupId): JsonResponse
    {
        $members = $service->getGroupMembers($groupId);

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

        return response()->json($result, 201);
    }

    public function update(Request $request, GroupMemberService $service, string $id): JsonResponse
    {
        $member = $service->getById($id);

        $validated = $request->validate([
            'role' => 'required|string',
        ]);

        $result = $service->update($member, $validated);

        return response()->json($result);
    }

    public function destroy(GroupMemberService $service, string $groupId, string $userId): JsonResponse
    {
        $service->removeMember($groupId, $userId);

        return response()->json(null, 204);
    }
}
