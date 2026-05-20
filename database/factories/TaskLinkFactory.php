<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TaskLinkFactory extends Factory {
    protected $model = \App\Models\TaskLink::class;
    
    public function definition(): array {
        return [
            'task_id' => \App\Models\Task::factory(),
            'url' => fake()->url(),
            'label' => fake()->word(),
        ];
    }
}