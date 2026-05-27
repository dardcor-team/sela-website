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
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::table('profiles')->insertOrIgnore([
                'id' => $userId,
                'username' => 'dosen',
                'full_name' => 'Dosen',
                'role' => 'lecturer',
                'updated_at' => now()
            ]);
        }

        // Seed Super Admin
        $adminEmail = 'admin@pens.ac.id';
        $adminExists = DB::table('users')->where('email', $adminEmail)->exists();

        if (!$adminExists) {
            $adminId = Str::uuid()->toString();

            DB::table('users')->insert([
                'id' => $adminId,
                'username' => 'superadmin',
                'email' => $adminEmail,
                'password' => Hash::make('admin123'),
                'role' => 'super_admin',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('profiles')->insertOrIgnore([
                'id' => $adminId,
                'username' => 'superadmin',
                'full_name' => 'Super Admin Sela',
                'role' => 'super_admin',
                'updated_at' => now()
            ]);
        }
    }
}
