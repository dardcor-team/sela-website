<?php

namespace App\Services;

use App\Models\Group;
use App\Models\GroupMember;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GroupService
{
    public function getGroupsByUser($user_id, $search = null)
    {
        $groups = DB::table('group_members')
            ->join('groups', 'groups.id', '=', 'group_members.group_id')
            ->where('group_members.user_id', $user_id)
            ->when($search, function ($query) use ($search) {
                $query->where('groups.name', 'ilike', "%$search%");
            })
            ->select(
                'groups.id',
                'groups.name',
                'groups.course_name',
                'groups.class_name',
                'groups.group_number',
                'groups.member_limit',
                'groups.invitation_code',
                'groups.lecture_code',
                'group_members.role'
            )
            ->get();

        foreach ($groups as $group) {
            $group->total_member = DB::table('group_members')
                ->where('group_id', $group->id)
                ->count();

            $group->members = DB::table('group_members')
                ->join('profiles', 'profiles.id', '=', 'group_members.user_id')
                ->where('group_members.group_id', $group->id)
                ->select(
                    'profiles.id',
                    'profiles.username',
                    'profiles.avatar_url'
                )
                ->limit(5)
                ->get();
        }

        return $groups;
    }

    public function getGroupDetail($group_id)
    {
        $group = DB::table('groups')
            ->where('id', $group_id)
            ->select(
                'id',
                'name',
                'course_name',
                'class_name',
                'group_number',
                'member_limit',
                'invitation_code',
                'lecture_code'
            )
            ->first();

        $members = DB::table('group_members')
            ->join('profiles', 'profiles.id', '=', 'group_members.user_id')
            ->where('group_members.group_id', $group_id)
            ->select(
                'profiles.id',
                'profiles.username',
                'profiles.full_name',
                'group_members.role'
            )
            ->get();

        return [
            "group" => $group,
            "members" => $members,
        ];
    }

    public function createGroup($request)
    {
        $profile = Profile::findOrFail(auth()->id());

        $code = strtoupper(Str::random(6));

        $userClass = $profile->class_name ?? 'Kelas Default';

        $groupName = $userClass
            . " "
            . $request->course_name
            . " Kelompok "
            . $request->group_number;

        $group = Group::create([
            'name' => $groupName,
            'course_name' => $request->course_name,
            'class_name' => $request->class_name ?? $userClass,
            'group_number' => $request->group_number,
            'member_limit' => $request->member_limit ?? 4,
            'invitation_code' => $code,
            'lecture_code' => $request->lecture_code,
            'created_by' => auth()->id(),
        ]);

        GroupMember::create([
            'group_id' => $group->id,
            'user_id' => auth()->id(),
            'role' => 'leader',
            'joined_at' => now(),
        ]);

        try {
            $notificationService = app(\App\Services\NotificationService::class);
            $notificationService->createNotification([
                'user_id' => auth()->id(),
                'title' => 'Grup Baru Dibuat',
                'message' => 'Grup "' . $group->name . '" berhasil dibuat. Silakan bagikan kode undangan.',
                'type' => 'group',
                'related_id' => $group->id,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Group FCM failed: ' . $e->getMessage());
        }

        return $group;
    }

    public function joinGroup($code, $user_id)
    {
        $group = Group::where('invitation_code', $code)->firstOrFail();

        $count = GroupMember::where('group_id', $group->id)->count();

        if ($count >= $group->member_limit) {
            throw new \Exception("Group is full");
        }

        $existing = GroupMember::where('group_id', $group->id)
            ->where('user_id', $user_id)
            ->first();

        if ($existing) {
            throw new \Exception("Already a member of this group");
        }

        GroupMember::create([
            'group_id' => $group->id,
            'user_id' => $user_id,
            'role' => 'member',
            'joined_at' => now(),
        ]);

        return $group;
    }
}
