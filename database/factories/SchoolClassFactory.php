<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SchoolClassFactory extends Factory {
    protected $model = \App\Models\SchoolClass::class;
    
    public function definition(): array {
        return [
            'name' => 'D4-IT-' . fake()->randomLetter(),
        ];
    }
}