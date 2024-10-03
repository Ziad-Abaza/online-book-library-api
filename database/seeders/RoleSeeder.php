<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء الأدوار
        $superAdmin = Role::create(['name' => 'Super Admin']);
        $admin = Role::create(['name' => 'Admin']);
        $bookManager = Role::create(['name' => 'Book Manager']);
        $userManager = Role::create(['name' => 'User Manager']);
        
        // منح الصلاحيات لدور Super Admin (يملك كل الصلاحيات)
        $superAdmin->givePermissionTo([
            'create-role',
            'edit-role',
            'delete-role',
            'view-role',
            'create-user',
            'edit-user',
            'delete-user',
            'ban-user',
            'unban-user',
            'create-book',
            'edit-book',
            'delete-book',
            'view-book',
            'download-book',
            'approve-book',
            'create-category',
            'edit-category',
            'delete-category',
            'view-category',
            'create-comment',
            'edit-comment',
            'delete-comment',
            'approve-comment',
        ]);

        // منح الصلاحيات لدور Admin
        $admin->givePermissionTo([
            'create-user',
            'edit-user',
            'delete-user',
            'ban-user',
            'unban-user',
            'create-book',
            'edit-book',
            'delete-book',
            'view-book',
            'approve-book',
            'create-category',
            'edit-category',
            'delete-category',
            'view-category',
        ]);

        // منح الصلاحيات لدور Book Manager
        $bookManager->givePermissionTo([
            'create-book',
            'edit-book',
            'delete-book',
            'view-book',
            'download-book',
            'approve-book',
        ]);

        // منح الصلاحيات لدور User Manager
        $userManager->givePermissionTo([
            'create-user',
            'edit-user',
            'delete-user',
            'ban-user',
            'unban-user',
        ]);
    }
}
