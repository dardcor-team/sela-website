<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory {
    protected $model = \App\Models\Group::class;
    
    public function definition(): array {
        return [
            'name' => fake()->word() . ' Group',
            'course_name' => fake()->word(),
            'class_name' => 'D4-IT-A',
            'group_number' => fake()->numberBetween(1, 10),
            'member_limit' => 5,
            'invitation_code' => fake()->unique()->lexify('??????'),
            'lecture_code' => fake()->lexify('???'),
            'created_by' => \App\Models\User::factory(),
        ];
    }
}