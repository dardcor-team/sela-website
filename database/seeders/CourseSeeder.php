<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $json = file_get_contents(database_path('data/courses.json'));
        $courses = json_decode($json, true);

        foreach ($courses as $course) {
            DB::table('courses')->insertOrIgnore([
                'name' => $course['name']
            ]);
        }
    }
}
