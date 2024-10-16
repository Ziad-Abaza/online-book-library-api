<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
            // 'image' => 'http://127.0.0.1:8000/assets/images/static/person.png',
            'is_active' => true,
            'token' => Str::random(60),
            'token_expiration' => now()->addDays(7),
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => bcrypt('password123'),
            // 'image' => 'http://127.0.0.1:8000/assets/images/static/person.png',
            'is_active' => true,
            'token' => Str::random(60),
            'token_expiration' => now()->addDays(7),
        ]);

        User::create([
            'name' => 'Michael Johnson',
            'email' => 'michael@example.com',
            'password' => bcrypt('password123'),
            // 'image' => 'http://127.0.0.1:8000/assets/images/static/person.png',
            'is_active' => false,
            'token' => Str::random(60),
            'token_expiration' => now()->addDays(7),
        ]);

    }
}
