<?php

namespace Jlab\ElogRepository;

use Illuminate\Support\ServiceProvider;

class ElogRepositoryServiceProvider extends ServiceProvider
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
        $this->mergeConfigFrom(__DIR__.'/../config/elog-repository.php', 'elog-repository');

        // Register the service the package provides.
        $this->app->singleton('elog-repository', function ($app) {
            return new ElogRepository;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['elog-repository'];
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
            __DIR__.'/../config/elog-repository.php' => config_path('elog-repository.php'),
        ], 'elog.config');

        // Registering package commands.
        // $this->commands([]);
    }
}
