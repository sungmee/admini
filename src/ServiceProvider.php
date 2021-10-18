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
        $this->app['router']->pushMiddlewareToGroup('web', Middleware::class);

        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadTranslationsFrom(__DIR__.'/translations', 'admini');
        $this->loadViewsFrom(__DIR__.'/views', 'admini');

        $this->publishes([
            __DIR__.'/assets' => public_path('vendor/admini'),
        ], 'public');

        $this->publishes([
            __DIR__.'/views' => resource_path('views/vendor/admini'),
        ], 'views');

        $this->publishes([
            __DIR__.'/config.php' => config_path('admini.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/translations' => resource_path('lang/vendor/admini'),
        ], 'translations');
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