<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            'tardio@gmail.com',
            'carman@gmail.com',
            'villamor@gmail.com',
            'tamayuza@gmail.com',
            'embanecido@gmail.com',
        ];

        foreach ($admins as $email) {
            User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => 'Admin',
                    'password' => Hash::make('12345678'),
                    'role' => 'admin',
                    'approval_status' => 'approved',
                ]
            );
        }
    }
}
