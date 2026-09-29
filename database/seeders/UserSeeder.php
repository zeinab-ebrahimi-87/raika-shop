<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ادمین
        User::create([
            'name' => 'مدیر',
            'email' => 'admin@raika.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);
        
        // کاربر عادی
        User::create([
            'name' => 'کاربر تست',
            'email' => 'user@raika.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);
    }
}