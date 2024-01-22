<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\Hash;

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
        Permission::create(['name' => 'menu layer']);
        Permission::create(['name' => 'menu ticket']);
        Permission::create(['name' => 'menu ppc']);
        Permission::create(['name' => 'menu_pmr']);

        // gets all permissions via Gate::before rule; see AuthServiceProvider
        $role_super_admin = Role::create(['name' => 'super_admin']);

        // create roles and assign existing permissions
        $role_planning = Role::create(['name' => 'planner']);
        $role_planning->givePermissionTo('menu master');
        $role_planning->givePermissionTo('menu planning');

        $role_cutting = Role::create(['name' => 'cutter']);
        $role_cutting->givePermissionTo('menu master');
        $role_cutting->givePermissionTo('menu cutting');

        $role_layer = Role::create(['name' => 'layer']);
        $role_layer->givePermissionTo('menu master');
        $role_layer->givePermissionTo('menu layer');

        $role_ticket = Role::create(['name' => 'ticketer']);
        $role_ticket->givePermissionTo('menu master');
        $role_ticket->givePermissionTo('menu ticket');

        $role_ppc = Role::create(['name' => 'ppc']);
        $role_ppc->givePermissionTo('menu master');
        $role_ppc->givePermissionTo('menu ppc');

        $role_pmr = Role::create(['name' => 'pmr']);
        $role_pmr->givePermissionTo('menu master');
        $role_pmr->givePermissionTo('menu pmr');

        // create demo users
        $user = \App\Models\User::factory()->create([
            'name' => 'User Admin',
            'email' => 'admin@ghimli.com',
            'password' => Hash::make('ghimli@2024'),
        ]);
        $user->assignRole($role_super_admin);

        $user = \App\Models\User::factory()->create([
            'name' => 'User Planning',
            'email' => 'planning@ghimli.com',
            'password' => Hash::make('123456789'),
        ]);
        $user->assignRole($role_planning);

        $user = \App\Models\User::factory()->create([
            'name' => 'User Cutting',
            'email' => 'cutting@ghimli.com',
            'password' => Hash::make('123456789'),
        ]);
        $user->assignRole($role_cutting);

        $user = \App\Models\User::factory()->create([
            'name' => 'User Ticket',
            'email' => 'ticket@ghimli.com',
            'password' => Hash::make('123456789'),
        ]);
        $user->assignRole($role_ticket);

        $user = \App\Models\User::factory()->create([
            'name' => 'Yenti Cutting',
            'email' => 'yenti@ghimli.com',
            'password' => Hash::make('123456789'),
        ]);
        $user->assignRole($role_cutting);

        $user = \App\Models\User::factory()->create([
            'name' => 'Bela Cutting',
            'email' => 'bela@ghimli.com',
            'password' => Hash::make('123456789'),
        ]);
        $user->assignRole($role_cutting);

        $user = \App\Models\User::factory()->create([
            'name' => 'Laras Cutting',
            'email' => 'laras@ghimli.com',
            'password' => Hash::make('123456789'),
        ]);
        $user->assignRole($role_cutting);

        $user = \App\Models\User::factory()->create([
            'name' => 'Masri Cutting',
            'email' => 'masri@ghimli.com',
            'password' => Hash::make('123456789'),
        ]);
        $user->assignRole($role_cutting);

        $user = \App\Models\User::factory()->create([
            'name' => 'User Layer',
            'email' => 'layer@ghimli,com',
            'password' => Hash::make('123456789'),
        ]);
        $user->assignRole($role_layer);

        $user = \App\Models\User::factory()->create([
            'name' => 'User PPC',
            'email' => 'ppc@ghimli.com',
            'password' => Hash::make('123456789'),
        ]);
        $user->assignRole($role_ppc);

        $user = \App\Models\User::factory()->create([
            'name' => 'User PMR',
            'email' => 'pmr@ghimli.com',
            'password' => Hash::make('123456789'),
        ]);
        $user->assignRole($role_pmr);
    }
}
