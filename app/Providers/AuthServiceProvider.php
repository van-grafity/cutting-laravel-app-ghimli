<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

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

        //
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


        Gate::define('labtest', function ($user) {
            return $user->hasRole(['super_admin','labtest']);
        });
        
        Gate::define('clerk', function ($user) {
            return $user->hasRole(['super_admin','planner','cutter', 'layer','ticketer','ppc']);
        });
        
        Gate::define('clerk-cutting', function ($user) {
            return $user->hasRole(['super_admin','cutter','layer', 'clerk']);
        });

        // form
        Gate::define('form', function ($user) {
            return $user->hasRole(['super_admin', 'cutter', 'warehouse']);
        });

        Gate::define('cutting-record', function ($user) {
            return $user->hasRole(['super_admin','cutter','layer', 'warehouse','pmr','ppc','packing', 'labtest']);
        });

        Gate::define('status-cutting-record', function ($user) {
            return $user->hasRole(['super_admin','cutter','layer', 'warehouse']);
        });

        Gate::define('group-cutting-record', function ($user) {
            return $user->hasRole(['super_admin','cutter','layer', 'warehouse']);
        });

        Gate::define('completion-cutting-record', function ($user) {
            return $user->hasRole(['super_admin','cutter','layer', 'warehouse']);
        });
    }
}
