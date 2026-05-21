<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GroupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function getByUser(Request $request, $user_id, GroupService $service)
    {
        $search = $request->search;

        $groups = $service->getGroupsByUser($user_id, $search);

        return response()->json([
            "groups" => $groups,
        ]);
    }

    public function detail($group_id, GroupService $service)
    {
        $group = $service->getGroupDetail($group_id);

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

        return response()->json([
            "message" => "Join group success",
            "data" => $data,
        ]);
    }

    public function destroy($id)
    {
        $group = \App\Models\Group::findOrFail($id);
        $group->delete();

        return response()->json(null, 204);
    }
}
