<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PasswordHistoryFactory extends Factory {
    protected $model = \App\Models\PasswordHistory::class;
    
    public function definition(): array {
        return [
            'user_id' => \App\Models\User::factory(),
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ];
    }
}