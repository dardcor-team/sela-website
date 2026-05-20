<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SubtaskProgressFactory extends Factory {
    protected $model = \App\Models\SubtaskProgress::class;
    
    public function definition(): array {
        return [
            'subtask_id' => \App\Models\Subtask::factory(),
            'user_id' => \App\Models\User::factory(),
            'progress' => fake()->numberBetween(0, 100),
        ];
    }
}