<?php

namespace JacobTilly\LaravelDocsign;

use Illuminate\Support\ServiceProvider;

class LaravelDocsignServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-docsign.php', 'laravel-docsign');

        // Register the service the package provides.
        $this->app->singleton('laravel-docsign', function ($app) {
            return new LaravelDocsign;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['laravel-docsign'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/laravel-docsign.php' => config_path('docsign.php'),
        ], 'docsign.config');

    }
}
