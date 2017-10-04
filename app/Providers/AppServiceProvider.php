<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (env('APP_ENV') === 'local' || env('APP_ENV') === 'dev') {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }

        $this->app->singleton('glide', function () {
            return ServerFactory::create([
                'source' => \Storage::disk('image')->getDriver(),
                'cache' => \Storage::disk('image')->getDriver(),
                'cache_path_prefix' => 'cache',
                'base_url' => null,
                'max_image_size' => 2000 * 2000,
                'presets' => config('settings.image_size'),
                'response' => new LaravelResponseFactory(),
            ]);
        });

        $this->app->singleton('glide.builder', function () {
            return UrlBuilderFactory::create(null, env('GLIDE_SIGNATURE_KEY'));
        });

        $this->app->bind(
            \App\Contracts\Services\PassportInterface::class,
            \App\Services\Passport::class
        );

        $this->app->bind(
            \App\Contracts\Services\GlideInterface::class,
            \App\Services\GlideService::class
        );
    }
}
