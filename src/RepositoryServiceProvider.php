<?php

namespace Inz\Repository;

use Illuminate\Support\ServiceProvider;
use Inz\Repository\Commands\MakeBindingCommand;
use Inz\Repository\Commands\MakeCriteriaCommand;
use Inz\Repository\Commands\MakeRepositoryCommand;

class RepositoryServiceProvider extends ServiceProvider
{
    private $repoCommands = [
        MakeCriteriaCommand::class,
        MakeRepositoryCommand::class,
        MakeBindingCommand::class,
    ];

    /**
     * Bootstrap services.
     */
    public function boot()
    {
    }

    /**
     * Register services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/repository.php', 'repository');

        $this->publishes([
            __DIR__ . '/config/repository.php' => app()->basePath() . '/config/repository.php',
        ], 'config');

        $this->registerCommands();
    }

    /**
     * Registers repository commands.
     */
    public function registerCommands()
    {
        $this->commands($this->repoCommands);
    }
}
