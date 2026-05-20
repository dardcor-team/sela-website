<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'dosen@pens.ac.id';

        $userExists = DB::table('users')->where('email', $email)->exists();

        if (!$userExists) {
            $userId = Str::uuid()->toString();

            DB::table('users')->insert([
                'id' => $userId,
                'username' => 'dosen',
                'email' => $email,
                'password' => Hash::make('dosen123'),
                'role' => 'lecturer',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('profiles')->insertOrIgnore([
                'id' => $userId,
                'username' => 'dosen',
                'full_name' => 'Dosen',
                'role' => 'lecturer',
                'updated_at' => now()
            ]);
        }
    }
}
