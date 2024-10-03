<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء مستخدم Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin', 
            'email' => 'superadmin@bookmanagement.com',
            'password' => Hash::make('superadmin1234')
        ]);
        $superAdmin->assignRole('Super Admin');

        // إنشاء مستخدم Admin
        $admin = User::create([
            'name' => 'Admin User', 
            'email' => 'admin@bookmanagement.com',
            'password' => Hash::make('admin1234')
        ]);
        $admin->assignRole('Admin');

        // إنشاء مستخدم Book Manager
        $bookManager = User::create([
            'name' => 'Book Manager', 
            'email' => 'bookmanager@bookmanagement.com',
            'password' => Hash::make('bookmanager1234')
        ]);
        $bookManager->assignRole('Book Manager');

        // إنشاء مستخدم User Manager
        $userManager = User::create([
            'name' => 'User Manager', 
            'email' => 'usermanager@bookmanagement.com',
            'password' => Hash::make('usermanager1234')
        ]);
        $userManager->assignRole('User Manager');
    }
}
