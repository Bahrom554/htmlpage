<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();
        $this->registerPermission();


    }

    public function registerPermission()
    {
        Gate::define('admin', function (User $user) {
            return $user->hasRole(User::ROLE_ADMIN);
        });

        Gate::define('manager', function (User $user) {
            return $user->hasRole(User::ROLE_MANAGER);
        });
        Gate::define('user', function (User $user) {
            return $user->hasRole(User::ROLE_USER);
        });


    }
}
