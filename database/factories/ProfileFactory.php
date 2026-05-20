<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory {
    protected $model = \App\Models\Profile::class;
    
    public function definition(): array {
        return [
            'id' => \App\Models\User::factory(),
            'username' => fake()->unique()->userName(),
            'full_name' => fake()->name(),
            'avatar_url' => null,
            'class_name' => 'D4-IT-A',
        ];
    }
}