<?php

namespace Tests\Feature\Api\Public;

use App\Models\SchoolClass;
use App\Models\Course;
use Tests\TestCase;

class PublicEndpointTest extends TestCase
{
    public function test_get_classes_returns_success(): void
    {
        \App\Models\SchoolClass::factory()->create(['name' => 'UniqueClass-' . \Illuminate\Support\Str::random(5)]);
        \App\Models\SchoolClass::factory()->create(['name' => 'UniqueClass-' . \Illuminate\Support\Str::random(5)]);
        
        $this->getJson('/api/classes')
            ->assertOk();
    }

    public function test_get_courses_returns_success(): void
    {
        Course::factory()->count(3)->create();

        $this->getJson('/api/courses')
            ->assertOk();
    }
}
