<?php

namespace App\Providers;

use Laravel\Passport\Passport;
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
        \App\Eloquent\User::class => \App\Policies\UserPolicy::class,
        \App\Eloquent\Project::class => \App\Policies\ProjectPolicy::class,
        \App\Eloquent\Endpoint::class => \App\Policies\EndpointPolicy::class,
        \App\Eloquent\Field::class => \App\Policies\FieldPolicy::class,
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
    }
}
