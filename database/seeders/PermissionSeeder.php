<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // صلاحيات الأدوار
            'create-role',
            'edit-role',
            'delete-role',
            'view-role',
            
            // صلاحيات المستخدمين
            'create-user',
            'edit-user',
            'delete-user',
            'ban-user',
            'unban-user',

            // صلاحيات الكتب
            'create-book',
            'edit-book',
            'delete-book',
            'view-book',
            'download-book',
            'approve-book',

            // صلاحيات الفئات
            'create-category',
            'edit-category',
            'delete-category',
            'view-category',

            // صلاحيات المراجعات
            'create-comment',
            'edit-comment',
            'delete-comment',
            'approve-comment',
        ];

        // Looping and Inserting Array's Permissions into Permission Table
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
