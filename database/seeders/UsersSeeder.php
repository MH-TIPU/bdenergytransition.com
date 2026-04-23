<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('SEED_ADMIN_EMAIL', 'admin@admin.com');
        $password = env('SEED_ADMIN_PASSWORD', 'password');

        User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Super Admin',
                'password' => Hash::make($password),
                'is_admin' => true,
            ]
        );

        $commonPassword = Hash::make('password');

        // Sample non-admin users (deterministic + idempotent)
        for ($i = 1; $i <= 9; $i += 1) {
            User::query()->updateOrCreate(
                ['email' => "user{$i}@example.com"],
                [
                    'name' => "Sample User {$i}",
                    'password' => $commonPassword,
                    'is_admin' => false,
                ]
            );
        }
    }
}
