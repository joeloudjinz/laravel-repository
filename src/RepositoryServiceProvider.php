<?php

namespace Inz;

// use Inz\Commands\MakeBindingCommand;
// use Inz\Commands\MakeCriteriaCommand;
use Illuminate\Support\ServiceProvider;
use Inz\Commands\MakeRepositoryCommand;

class RepositoryServiceProvider extends ServiceProvider
{
    private $repoCommands = [
        // MakeBindingCommand::class,
        // MakeCriteriaCommand::class,
        MakeRepositoryCommand::class,
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

        $this->publishes(
            [__DIR__ . '/config/repository.php' => app()->basePath() . '/config/repository.php'],
            'inz-repository'
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
