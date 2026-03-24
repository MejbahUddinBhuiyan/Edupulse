<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@edupulse.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('Admin12345'),
                'role' => 'admin',
                'email_verified_at' => Carbon::now(),
            ]
        );
    }
}