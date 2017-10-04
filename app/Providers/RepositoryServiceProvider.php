<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    protected static $repositories = [
        'user' => [
            \App\Contracts\Repositories\UserRepository::class,
            \App\Repositories\UserRepositoryEloquent::class,
        ],
        'project' => [
            \App\Contracts\Repositories\ProjectRepository::class,
            \App\Repositories\ProjectRepositoryEloquent::class,
        ],
        'endpoint' => [
            \App\Contracts\Repositories\EndpointRepository::class,
            \App\Repositories\EndpointRepositoryEloquent::class,
        ],
        'field' => [
            \App\Contracts\Repositories\FieldRepository::class,
            \App\Repositories\FieldRepositoryEloquent::class,
        ],
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        foreach (static::$repositories as $repository) {
            $this->app->singleton(
                $repository[0],
                $repository[1]
            );
        }
    }
}
