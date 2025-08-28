<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
                'password' => 'Admin123',
            ],
            [
                'first_name' => 'Teacher',
                'last_name' => 'User',
                'email' => 'teacher@gmail.com',
                'role' => 'teacher',
                'password' => 'Teacher123',
            ],
            [
                'first_name' => 'Employee',
                'last_name' => 'User',
                'email' => 'employee@gmail.com',
                'role' => 'employee',
                'password' => 'Employee123',
            ],

            [
                'first_name' => 'Student',
                'last_name' => 'User',
                'email' => 'student@gmail.com',
                'role' => 'student',
                'password' => 'Student123',
                'email_verified_at' => now(),
            ],

            [
                'first_name' => 'joy',
                'last_name' => 'User',
                'email' => 'joy.test@gmail.com',
                'role' => 'student',
                'password' => 'JoyStu123',
                'email_verified_at' => now(),
            ],

            [
                'first_name' => 'Mjd',
                'last_name' => 'User',
                'email' => 'mjd.test@gmail.com',
                'role' => 'student',
                'password' => 'MjdStu123',
                'email_verified_at' => now(),
            ],

            [
                'first_name' => 'Meray',
                'last_name' => 'User',
                'email' => 'meray.test@gmail.com',
                'role' => 'student',
                'password' => 'MerayStu123',
                'email_verified_at' => now(),
            ],

        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'email' => $data['email'],
                    // The User model casts the password as 'hashed', so plain text will be hashed automatically
                    'password' => $data['password'],
                    'role' => $data['role'],
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
