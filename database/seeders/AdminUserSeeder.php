<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'admin@example.com',
            ],
            [
                'name'        => 'Super Admin',
                'email'       => 'admin@example.com',
                'password'    => Hash::make('Admin@123'),
                'is_admin'    => 1,
                'is_verified' => 1,
            ]
        );
    }
}
