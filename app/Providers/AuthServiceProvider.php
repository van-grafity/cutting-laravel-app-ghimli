<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        
        Gate::before(function (User $user, $ability) {
           if ($user->hasRole('super_admin')) {
               return true;
           }
        });

        Gate::define('developer-menu', function (User $user) {
            if ($user->hasRole('super_admin')) {
                return true;
            }
        });

        Gate::define('department-menu', function (User $user) {
            $permitted_permissions = [
                'department.access',
                'department.manage',
            ];
            if ($user->canany($permitted_permissions)) { return true; }
        });

        Gate::define('cutting-group-menu', function (User $user) {
            $permitted_permissions = [
                'cutting-group.access',
                'cutting-group.manage',
            ];
            if ($user->canany($permitted_permissions)) { return true; }
        });
        
        Gate::define('report-menu-section', function (User $user) {
            $permitted_permissions = [
                'daily-cutting-report.access',
            ];
            if ($user->canany($permitted_permissions)) { return true; }
        });

        // todo : kodingan untuk nanti
        // Gate::define('user-management-menu', function (User $user) {
        //     $permitted_permissions = [
        //         'user-management.access',
        //         'user-management.manage',
        //     ];
        //     if ($user->canany($permitted_permissions)) { return true; }
        // });


        // ## ========== batas


        // Implicitly grant "Super-Admin" role all permission checks using can()
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('super_admin')) {
                return true;
            }
        });

        Gate::define('admin-only', function ($user) {
            return $user->hasRole(['super_admin']);
        });

        Gate::define('warehouse', function ($user) {
            return $user->hasRole(['super_admin','warehouse']);
        });

        

        Gate::define('ppc', function ($user) {
            return $user->hasRole(['super_admin','ppc']);
        });

        Gate::define('pmr', function ($user) {
            return $user->hasRole(['super_admin','pmr']);
        });

        Gate::define('packing', function ($user) {
            return $user->hasRole(['super_admin','packing']);
        });
        
        Gate::define('clerk', function ($user) {
            return $user->hasRole(['super_admin','planner','cutter', 'layer','ticketer','pmr','ppc', 'merchandiser', 'bundle','warehouse']);
        });

        Gate::define('editor', function ($user) {
            return $user->hasRole(['super_admin','cutter', 'bundle']);
        });

        Gate::define('viewer', function ($user) {
            return $user->hasRole(['super_admin','cutter', 'pmr', 'ppc', 'warehouse', 'packing', 'merchandiser', 'bundle']);
        });
        
        Gate::define('clerk-cutting', function ($user) {
            return $user->hasRole(['super_admin','cutter','layer', 'clerk', 'bundle']);
        });

        // form
        Gate::define('form', function ($user) {
            return $user->hasRole(['super_admin', 'cutter', 'warehouse']);
        });

        Gate::define('cutting-record', function ($user) {
            return $user->hasRole(['super_admin','cutter','layer', 'warehouse','pmr','ppc','packing', 'labtest',  'merchandiser']);
        });

        Gate::define('status-cutting-record', function ($user) {
            return $user->hasRole(['super_admin','cutter','layer', 'warehouse']);
        });

        Gate::define('group-cutting-record', function ($user) {
            return $user->hasRole(['super_admin','cutter','layer', 'warehouse']);
        });
    }
}
