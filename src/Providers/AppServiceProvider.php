<?php

namespace Vxsoft\LaravelRepository\Providers;

use Vxsoft\LaravelRepository\Interfaces\EntityManagerInterface;
use Vxsoft\LaravelRepository\Repository;
use Vxsoft\LaravelRepository\Command\CreateRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // binding a entityManagerInterface with repository
        $this->app->bind(EntityManagerInterface::class, Repository::class);
    }

    public function boot(): void
    {
        // Register the command when in console mode
        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateRepository::class,
            ]);
        }

        // Publish config file
        $this->publishes([
            __DIR__.'/../config/repository.php' => config_path('repository.php'),
        ], 'repository-config');

        // Merge the config file for package defaults
        $this->mergeConfigFrom(
            __DIR__.'/../config/repository.php', 'repository'
        );
    }

}