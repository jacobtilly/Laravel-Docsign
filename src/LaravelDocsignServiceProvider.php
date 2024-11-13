<?php

namespace JacobTilly\LaravelDocsign;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use JacobTilly\LaravelDocsign\Console\Commands\InstallDocsign;

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

        // Conditionally load callback routes
        if (config('docsign.callbacks.enabled')) {
            $this->loadRoutesFrom(__DIR__.'/../routes/callbacks.php');
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-docsign.php', 'docsign');

        // Register the service the package provides.
        $this->app->singleton('laravel-docsign', function ($app) {
            return new LaravelDocsign;
        });

        // Register the installation command
        $this->commands([
            \JacobTilly\LaravelDocsign\Console\Commands\InstallDocsign::class,
        ]);
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

        // Publish the routes file if you want users to customize it
        $this->publishes([
            __DIR__.'/../routes/callbacks.php' => base_path('routes/docsign/callbacks.php'),
        ], 'docsign.routes');
    }
}
