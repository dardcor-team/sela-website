<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileAbilityFactory extends Factory {
    protected $model = \App\Models\ProfileAbility::class;
    
    public function definition(): array {
        return [
            'user_id' => \App\Models\User::factory(),
            'ability' => fake()->word(),
        ];
    }
}