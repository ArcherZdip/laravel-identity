<?php

namespace ArcherZdip\Identity;

use ArcherZdip\Identity\Console\IdentityGetCommand;
use ArcherZdip\Identity\Console\IdentityVerityCommand;
use Illuminate\Support\ServiceProvider;

class IdentityServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                IdentityGetCommand::class,
                IdentityVerityCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('identity_faker', function ($app) {
            return new IdentityService();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['identity_faker'];
    }
}
