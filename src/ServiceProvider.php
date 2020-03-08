<?php

namespace Sungmee\Admini;

use Illuminate\Support\ServiceProvider as SP;

class ServiceProvider extends SP
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadViewsFrom(__DIR__.'/views', 'admini');
        $this->loadTranslationsFrom(__DIR__.'/translations', 'admini');
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        $this->publishes([
            __DIR__.'/assets' => public_path('vendor/admini'),
        ], 'public');

        $this->app['router']->pushMiddlewareToGroup('web', Middleware::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Admini', function () {
            return new Admini;
        });

        $this->mergeConfigFrom(__DIR__.'/config.php', 'admini');
    }
}