<?php

namespace App\Services;

use App\Models\Group;
use App\Models\GroupMember;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GroupService
{
    public function getGroupsByUser($user_id, $search = null, $perPage = null)
    {
        $query = DB::table('group_members')
            ->join('groups', 'groups.id', '=', 'group_members.group_id')
            ->where('group_members.user_id', $user_id)
            ->where('groups.is_active', true)
            ->when($search, function ($query) use ($search) {
                $query->where('groups.name', DB::connection()->getDriverName() === 'pgsql' ? 'ilike' : 'like', "%$search%");
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
            );

        if ($perPage) {
            $groups = $query->paginate((int) $perPage);
            $items = $groups->items();
        } else {
            $groups = $query->get();
            $items = $groups;
        }

        foreach ($items as $group) {
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
                'profiles.avatar_url',
                'profiles.class_name',
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
        $group = Group::where('invitation_code', $code)
                      ->where('is_active', true)
                      ->first();
                      
        if (!$group) {
            abort(404, "Kode grup tidak valid atau grup tidak ditemukan.");
        }

        $count = GroupMember::where('group_id', $group->id)->count();

        if ($count >= $group->member_limit) {
            abort(400, "Group is full");
        }

        $existing = GroupMember::where('group_id', $group->id)
            ->where('user_id', $user_id)
            ->first();

        if ($existing) {
            abort(400, "Already a member of this group");
        }

        // Check if user was kicked
        $wasKicked = \Illuminate\Support\Facades\DB::table('group_kicked_members')
            ->where('group_id', $group->id)
            ->where('user_id', $user_id)
            ->exists();

        if ($wasKicked) {
            // Check rate limit (1 request per 24 hours)
            $recentRequest = \Illuminate\Support\Facades\DB::table('group_join_requests')
                ->where('group_id', $group->id)
                ->where('user_id', $user_id)
                ->where('status', 'pending')
                ->where('created_at', '>=', now()->subHours(24))
                ->first();

            if ($recentRequest) {
                abort(400, "Anda sudah mengirim permintaan bergabung dalam 24 jam terakhir. Silakan tunggu.");
            }

            // Create join request
            $requestId = (string) \Illuminate\Support\Str::uuid();
            \Illuminate\Support\Facades\DB::table('group_join_requests')->updateOrInsert(
                ['group_id' => $group->id, 'user_id' => $user_id],
                [
                    'id' => $requestId,
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );

            // Notify leader(s)
            $leaders = GroupMember::where('group_id', $group->id)->where('role', 'leader')->get();
            $notificationService = app(\App\Services\NotificationService::class);
            $requester = \App\Models\Profile::find($user_id);
            $requesterName = $requester ? ($requester->full_name ?? $requester->username) : 'Seseorang';

            foreach ($leaders as $leader) {
                $notificationService->createNotification([
                    'user_id' => $leader->user_id,
                    'title' => 'Permintaan Bergabung',
                    'message' => "{$requesterName} meminta untuk bergabung kembali ke grup {$group->name}.",
                    'type' => 'join_request',
                    'related_id' => $requestId, // We pass the request ID so the leader can accept/reject
                ]);
            }

            return ['status' => 'pending_approval', 'group' => $group];
        }

        GroupMember::create([
            'group_id' => $group->id,
            'user_id' => $user_id,
            'role' => 'member',
            'joined_at' => now(),
        ]);

        return ['status' => 'joined', 'group' => $group];
    }
}
