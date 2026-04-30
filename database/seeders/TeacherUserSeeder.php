<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class TeacherUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'teacher@edupulse.com'],
            [
                'name' => 'Demo Teacher',
                'password' => Hash::make('Teacher12345'),
                'role' => 'teacher',
                'email_verified_at' => Carbon::now(),
            ]
        );
    }
}