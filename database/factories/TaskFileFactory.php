<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFileFactory extends Factory {
    protected $model = \App\Models\TaskFile::class;
    
    public function definition(): array {
        return [
            'task_id' => \App\Models\Task::factory(),
            'file_name' => fake()->word() . '.pdf',
            'file_path' => fake()->filePath(),
            'file_type' => 'application/pdf',
            'file_size' => fake()->numberBetween(1000, 5000000),
            'uploaded_by' => \App\Models\User::factory(),
        ];
    }
}