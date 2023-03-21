<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserRolePermissionsSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'menu master']);
        Permission::create(['name' => 'menu planning']);
        Permission::create(['name' => 'menu cutting']);
        Permission::create(['name' => 'menu ticket']);

        // gets all permissions via Gate::before rule; see AuthServiceProvider
        $role_super_admin = Role::create(['name' => 'super_admin']);

        // create roles and assign existing permissions
        $role_planning = Role::create(['name' => 'planner']);
        $role_planning->givePermissionTo('menu master');
        $role_planning->givePermissionTo('menu planning');

        $role_cutting = Role::create(['name' => 'cutter']);
        $role_cutting->givePermissionTo('menu master');
        $role_cutting->givePermissionTo('menu cutting');

        $role_ticket = Role::create(['name' => 'ticketer']);
        $role_ticket->givePermissionTo('menu master');
        $role_ticket->givePermissionTo('menu ticket');

        
        // create demo users
        $user = \App\Models\User::factory()->create([
            'name' => 'User Admin',
            'email' => 'admin@gmail.com',
        ]);
        $user->assignRole($role_super_admin);
        
        $user = \App\Models\User::factory()->create([
            'name' => 'User Planning',
            'email' => 'planning@gmail.com',
        ]);
        $user->assignRole($role_planning);

        $user = \App\Models\User::factory()->create([
            'name' => 'User Cutting',
            'email' => 'cutting@gmail.com',
        ]);
        $user->assignRole($role_cutting);

        $user = \App\Models\User::factory()->create([
            'name' => 'User Ticket',
            'email' => 'ticket@gmail.com',
        ]);
        $user->assignRole($role_ticket);

        
    }
}