<?php

namespace Inggo\Boilerplate\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'Inggo\Boilerplate\Model' => 'Inggo\Boilerplate\Policies\ModelPolicy',
        'Inggo\Boilerplate\User' => 'Inggo\Boilerplate\Policies\UserPolicy',
        'Inggo\Boilerplate\Activity' => 'Inggo\Boilerplate\Policies\ActivityPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::resource('users', 'UserPolicy');
        Gate::resource('activities', 'ActivityPolicy');
    }
}
