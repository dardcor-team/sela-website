<?php

namespace App\Services;

use App\Models\GroupMember;
use Illuminate\Database\Eloquent\Collection;

class GroupMemberService
{
    public function getGroupMembers(string $groupId): Collection
    {
        return GroupMember::with('user')->where('group_id', $groupId)->get();
    }

    public function addMember(array $data): GroupMember
    {
        $data['joined_at'] = now();
        $member = GroupMember::create($data);

        try {
            $notificationService = app(\App\Services\NotificationService::class);
            
            $group = \App\Models\Group::find($member->group_id);
            $groupName = $group ? $group->name : 'Grup';
            
            $notificationService->createNotification([
                'user_id' => $member->user_id,
                'title' => 'Undangan Grup',
                'message' => 'Anda telah ditambahkan ke grup "' . $groupName . '".',
                'type' => 'group_invite',
                'related_id' => $member->group_id,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Group Member FCM failed: ' . $e->getMessage());
        }

        return $member;
    }

    public function getById(string $id): GroupMember
    {
        return GroupMember::findOrFail($id);
    }

    public function update(GroupMember $member, array $data): GroupMember
    {
        $member->update($data);
        return $member->fresh();
    }

    public function removeMember(string $groupId, string $userId): bool
    {
        $member = GroupMember::where('group_id', $groupId)
            ->where('user_id', $userId)
            ->firstOrFail();

        // If the user removing is not the member themselves, it's a kick.
        if (auth()->id() !== $userId) {
            \Illuminate\Support\Facades\DB::table('group_kicked_members')->updateOrInsert(
                ['group_id' => $groupId, 'user_id' => $userId],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        return $member->delete();
    }
}
