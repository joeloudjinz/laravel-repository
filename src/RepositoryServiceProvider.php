<?php

namespace Inz;

use Illuminate\Support\ServiceProvider;
use Inz\Commands\MakeBindingCommand;
use Inz\Commands\MakeCriteriaCommand;
use Inz\Commands\MakeRepositoryCommand;

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
        ],
        // TODO: rename this tag
            'config'
        );

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
