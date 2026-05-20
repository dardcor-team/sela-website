<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GroupMemberFactory extends Factory {
    protected $model = \App\Models\GroupMember::class;
    
    public function definition(): array {
        return [
            'group_id' => \App\Models\Group::factory(),
            'user_id' => \App\Models\User::factory(),
            'role' => 'member',
            'joined_at' => now(),
        ];
    }
}