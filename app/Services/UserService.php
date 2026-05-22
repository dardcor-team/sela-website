<?php

namespace App\Services;

use App\Models\Profile;
use App\Models\ProfileAbility;

class UserService
{
    public function getAll($perPage = null)
    {
        if ($perPage) {
            return Profile::paginate((int) $perPage);
        }
        return Profile::all();
    }

    public function getById(string $id): Profile
    {
        return Profile::findOrFail($id);
    }

    public function update(Profile $profile, array $data): Profile
    {
        $profile->update($data);
        return $profile->fresh();
    }

    public function delete(Profile $profile): bool
    {
        return $profile->delete();
    }

    public function getAbilities(string $userId)
    {
        return ProfileAbility::where('user_id', $userId)->get();
    }

    public function createAbility(string $userId, string $ability): ProfileAbility
    {
        return ProfileAbility::create([
            'user_id' => $userId,
            'ability' => $ability,
        ]);
    }

    public function deleteAbility(string $id): bool
    {
        return ProfileAbility::findOrFail($id)->delete();
    }
}
