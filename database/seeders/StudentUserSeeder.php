<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class StudentUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'student@edupulse.com'],
            [
                'name' => 'Demo Student',
                'password' => Hash::make('Student12345'),
                'role' => 'student',
                'email_verified_at' => Carbon::now(),
            ]
        );
    }
}