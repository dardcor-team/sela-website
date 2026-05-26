<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\GroupMember;
use App\Models\Group;

class GroupJoinRequestController extends Controller
{
    public function accept($id)
    {
        $request = DB::table('group_join_requests')->where('id', $id)->first();

        if (!$request || $request->status !== 'pending') {
            return response()->json(['message' => 'Permintaan tidak ditemukan atau sudah diproses'], 404);
        }

        $group = Group::find($request->group_id);
        if (!$group) {
            return response()->json(['message' => 'Grup tidak ditemukan'], 404);
        }

        DB::beginTransaction();
        try {
            DB::table('group_join_requests')->where('id', $id)->update(['status' => 'accepted', 'updated_at' => now()]);

            GroupMember::updateOrCreate(
                ['group_id' => $request->group_id, 'user_id' => $request->user_id],
                ['role' => 'member', 'joined_at' => now()]
            );

            DB::table('group_kicked_members')
                ->where('group_id', $request->group_id)
                ->where('user_id', $request->user_id)
                ->delete();

            $notificationService = app(\App\Services\NotificationService::class);
            $notificationService->createNotification([
                'user_id' => $request->user_id,
                'title' => 'Permintaan Bergabung Diterima',
                'message' => "Permintaan Anda untuk bergabung kembali ke grup {$group->name} telah diterima.",
                'type' => 'system',
                'related_id' => $group->id,
            ]);

            DB::commit();
            return response()->json(['message' => 'Permintaan diterima, anggota ditambahkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menerima permintaan: ' . $e->getMessage()], 500);
        }
    }

    public function reject($id)
    {
        $request = DB::table('group_join_requests')->where('id', $id)->first();

        if (!$request || $request->status !== 'pending') {
            return response()->json(['message' => 'Permintaan tidak ditemukan atau sudah diproses'], 404);
        }

        DB::table('group_join_requests')->where('id', $id)->update(['status' => 'rejected', 'updated_at' => now()]);

        return response()->json(['message' => 'Permintaan ditolak']);
    }
}

