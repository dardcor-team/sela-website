<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassSeeder extends Seeder
{
    public function run(): void
    {
        $json = file_get_contents(database_path('data/classes.json'));
        $classes = json_decode($json, true);

        foreach ($classes as $class) {
            DB::table('classes')->insertOrIgnore([
                'name' => $class['name']
            ]);
        }
    }
}
