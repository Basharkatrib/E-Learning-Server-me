<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class UpdateTeacherPassword extends Command
{
    protected $signature = 'teacher:update-password {email} {password}';
    protected $description = 'Update teacher password with proper Bcrypt hashing';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found!");
            return 1;
        }

        if ($user->role !== 'teacher') {
            $this->error("User with email {$email} is not a teacher!");
            return 1;
        }

        $user->password = Hash::make($password);
        $user->save();

        $this->info("Password updated successfully for teacher {$email}");
        return 0;
    }
} 