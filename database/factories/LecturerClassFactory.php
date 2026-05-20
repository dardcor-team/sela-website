<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LecturerClassFactory extends Factory {
    protected $model = \App\Models\LecturerClass::class;
    
    public function definition(): array {
        return [
            'lecturer_id' => \App\Models\User::factory(),
            'class_name' => 'D4-IT-' . fake()->randomLetter(),
        ];
    }
}