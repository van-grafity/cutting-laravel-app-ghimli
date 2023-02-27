<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::define('accessSuperAdmin', function ($user) {
            return $user->access == 1;
        });
        Gate::define('accessCutting', function ($user) {
            return $user->access == 2;
        });
    }
}
