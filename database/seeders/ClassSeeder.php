<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClassSeeder extends Seeder
{
    public function run(): void
    {
        $json = file_get_contents(database_path('data/classes.json'));
        $classes = json_decode($json, true);

        foreach ($classes as $class) {
            DB::table('classes')->insertOrIgnore([
                'id' => Str::uuid()->toString(),
                'name' => $class['name']
            ]);
        }
    }
}
