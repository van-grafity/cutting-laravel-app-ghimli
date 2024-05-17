<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permission_list = [
            [
                'name' => 'developer.access',
                'description' => 'Access all permissions',
                'permission_category_id' => '1',
            ],
            [
                'name' => 'admin.access',
                'description' => 'Can access admin permissions.',
                'permission_category_id' => '1',
            ],
            [
                'name' => 'user.access',
                'description' => 'Can access default permissions.',
                'permission_category_id' => '1',
            ],
            [
                'name' => 'guest.access',
                'description' => 'Can access guest permissions.',
                'permission_category_id' => '1',
            ],
            [
                'name' => 'cutting.access',
                'description' => 'Can access fg warehouse permissions.',
                'permission_category_id' => '1',
            ],
            [
                'name' => 'department.access',
                'description' => 'Can access department features.',
                'permission_category_id' => '2',
            ],
            [
                'name' => 'department.manage',
                'description' => 'Can manage department features.',
                'permission_category_id' => '3',
            ],
            [
                'name' => 'cutting-group.access',
                'description' => 'Can access cutting group features.',
                'permission_category_id' => '2',
            ],
            [
                'name' => 'cutting-group.manage',
                'description' => 'Can manage cutting group features.',
                'permission_category_id' => '3',
            ],
        ];
        foreach ($permission_list as $key => $permission) {
            Permission::create($permission);
        }
    }
}
