<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StorageBucketSeeder extends Seeder
{
    public function run(): void
    {
        $json = file_get_contents(database_path('data/storage_buckets.json'));
        $buckets = json_decode($json, true);

        foreach ($buckets as $bucket) {
            DB::statement(
                "INSERT INTO storage.buckets (id, name, public) VALUES (?, ?, ?) ON CONFLICT (id) DO NOTHING",
                [$bucket['id'], $bucket['name'], $bucket['public'] ? 'true' : 'false']
            );
        }
    }
}
