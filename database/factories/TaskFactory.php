<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory {
    protected $model = \App\Models\Task::class;
    
    public function definition(): array {
        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'category' => 'independent',
            'subject' => fake()->word(),
            'start_date' => now(),
            'due_date' => now()->addDays(7),
            'is_group' => false,
            'group_id' => null,
            'created_by' => \App\Models\User::factory(),
            'status' => 'pending',
            'priority' => 'medium',
            'link' => null,
            'file_path' => null,
        ];
    }
}