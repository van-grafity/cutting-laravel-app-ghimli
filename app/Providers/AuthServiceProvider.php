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

        // set menu show using "can"
        Gate::define('admin-only', function ($user) {
            return $user->hasRole(['super_admin']);
        });

        Gate::define('clerk-cutting', function ($user) {
            return $user->hasRole(['super_admin','cutter']);
        });

        Gate::define('clerk-cutting', function ($user) {
            return $user->hasRole(['super_admin','layer']);
        });

        Gate::define('ppc', function ($user) {
            return $user->hasRole(['super_admin','ppc']);
        });

        Gate::define('clerk', function ($user) {
            return $user->hasRole(['super_admin','planner','cutter', 'layer','ticketer','ppc']);
        });
    }
}
