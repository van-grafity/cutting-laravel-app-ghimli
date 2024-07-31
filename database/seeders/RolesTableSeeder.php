<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'developer',
                'title' => 'Developer',
                'description' => 'Developer',
            ],
            [
                'name' => 'planner',
                'title' => 'Planner',
                'description' => 'Planner',
            ],
            [
                'name' => 'cutter',
                'title' => 'Cutter',
                'description' => 'Cutter',
            ],
            [
                'name' => 'ticketer',
                'title' => 'Ticketer',
                'description' => 'Ticketer',
            ],
            [
                'name' => 'ppc',
                'title' => 'PPC',
                'description' => 'PPC',
            ],
            [
                'name' => 'layer',
                'title' => 'Layer',
                'description' => 'Layer',
            ],
            [
                'name' => 'warehouse',
                'title' => 'Warehouse',
                'description' => 'Warehouse',
            ],
            [
                'name' => 'pmr',
                'title' => 'PMR',
                'description' => 'PMR',
            ],
            [
                'name' => 'clerk-cutting',
                'title' => 'Clerk Cutting',
                'description' => 'Clerk Cutting',
            ],
            [
                'name' => 'clerk-manager',
                'title' => 'Clerk Manager',
                'description' => 'Clerk Manager',
            ],
            [
                'name' => 'packing',
                'title' => 'Packing',
                'description' => 'Packing',
            ],
            [
                'name' => 'labtest',
                'title' => 'Labtest',
                'description' => 'Labtest',
            ],
            [
                'name' => 'merchandiser',
                'title' => 'Merchandiser',
                'description' => 'Merchandiser',
            ],
            [
                'name' => 'bundle',
                'title' => 'Bundle',
                'description' => 'Bundle',
            ],
        ];

        foreach ($data as $role) {
            Role::create($role);
        }
    }
}
