<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DeviceTokenFactory extends Factory {
    protected $model = \App\Models\DeviceToken::class;
    
    public function definition(): array {
        return [
            'user_id' => \App\Models\User::factory(),
            'token' => fake()->sha256(),
            'platform' => fake()->randomElement(['android', 'ios', 'web']),
        ];
    }
}