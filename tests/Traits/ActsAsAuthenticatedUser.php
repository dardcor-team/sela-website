<?php
namespace Tests\Traits;

use App\Models\User;
use App\Models\Profile;

trait ActsAsAuthenticatedUser
{
    protected function createAuthenticatedUser(array $userOverrides = [], array $profileOverrides = []): User
    {
        $user = User::factory()->create($userOverrides);
        Profile::factory()->create(array_merge(['id' => $user->id], $profileOverrides));
        return $user;
    }

    protected function actingAsUser(array $userOverrides = [], array $profileOverrides = []): User
    {
        $user = $this->createAuthenticatedUser($userOverrides, $profileOverrides);
        $this->actingAs($user, 'sanctum');
        return $user;
    }
}
