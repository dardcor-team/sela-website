<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SubtaskFactory extends Factory {
    protected $model = \App\Models\Subtask::class;
    
    public function definition(): array {
        return [
            'task_id' => \App\Models\Task::factory(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
        ];
    }
}