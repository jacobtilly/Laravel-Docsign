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

        if (config('docsign.callbacks.enabled')) {
            Route::prefix('docsign/callbacks')->group(function () {
                Route::get('/document-complete', [Http\Controllers\CallbackController::class, 'documentComplete']);
                Route::get('/party-sign', [Http\Controllers\CallbackController::class, 'partySign']);
            });
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

        // Publishing example jobs.
        $this->publishes([
            __DIR__.'/Jobs/DocumentCompleteJob.php' => app_path('Jobs/DocumentCompleteJob.php'),
            __DIR__.'/Jobs/PartySignJob.php' => app_path('Jobs/PartySignJob.php'),
        ], 'docsign.jobs');
    }
}
