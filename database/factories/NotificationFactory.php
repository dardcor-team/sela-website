<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory {
    protected $model = \App\Models\Notification::class;
    
    public function definition(): array {
        return [
            'user_id' => \App\Models\User::factory(),
            'title' => fake()->sentence(),
            'message' => fake()->paragraph(),
            'type' => 'info',
            'related_id' => null,
            'is_read' => false,
        ];
    }
}