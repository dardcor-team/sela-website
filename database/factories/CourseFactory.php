<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory {
    protected $model = \App\Models\Course::class;
    
    public function definition(): array {
        return [
            'name' => fake()->word(),
            'description' => fake()->sentence(),
        ];
    }
}