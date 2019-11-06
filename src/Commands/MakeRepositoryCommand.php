<?php

namespace Inz\Repository\Commands;

use Exception;
use Inz\Repository\Base\ModelCreator;
use Inz\Repository\Base\ContractCreator;
use Inz\Repository\Base\RepositoryCreator;

class MakeRepositoryCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {model}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository';
    /**
     * The input of the command.
     *
     * @var String
     */
    private $input;
    /**
     * @var ModelCreator
     */
    private $modelCreator;
    /**
     * @var ContractCreator
     */
    private $contractCreator;
    /**
     * @var RepositoryCreator
     */
    private $repositoryCreator;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        if (!$this->hasArgument('model')) {
            // $this->error('No Model is specified');
            throw new Exception("No Model is specified");
            die(1);
        }

        $this->input = $this->argument('model');
        $this->modelCreator = new modelCreator($this->argument('model'));
        $this->contractCreator = new ContractCreator($this->argument('model'));
        $this->repositoryCreator = new RepositoryCreator($this->argument('model'));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!$this->processModelExistence()) {
            return;
        }

        $result = $this->createContract();
        if (is_bool($result)) {
            return;
        }

        if ($this->createRepository($result[0], $result[1])) {
            return;
        }

        $this->bindClasses();
    }

    /**
     * Checks the models existence, it will be created if the developer approved
     *
     * @return boolean.
     */
    protected function processModelExistence(): bool
    {
        if ($this->laravel->runningInConsole() && !$this->modelCreator->modelExist()) {
            $response = $this->ask("Model [{$this->input}] does not exist. Would you like to create it?", 'Yes');

            if ($this->isResponsePositive($response)) {
                $this->call('make:model', ['name' => $this->input]);
                return true;
            }

            $this->warn("Model wasn't created, aborting command.");
            return false;
        }
    }

    /**
     * Handle the process of delivering data to contract creator & processing
     * results of the creation
     *
     * @return array|bool
     */
    protected function createContract()
    {
        $result = $this->contractCreator->create();
        if (is_array($result)) {
            return $result;
        }

        $response = $this->ask("Contract file already exists. Do you want to overwrite it?", 'Yes');
        if (!$this->isResponsePositive($response)) {
            $this->warn("Contract wasn't created");
            return false;
        }

        $result = $this->contractCreator->complete();
        if (!is_array($result)) {
            throw new Exception("There was an error while creating contract file");
        }
    }

    /**
     * Create a new repository.
     *
     * @param String $contract
     * @param String $contractName
     *
     * @return void
     */
    protected function createRepository(String $contractNamespace, String $contractName): bool
    {
        $this->repositoryCreator->initializeReplacementsParts(
            $contractNamespace,
            $contractName,
            $this->modelCreator->getModelFullNamespace()
        );

        $result = $this->repositoryCreator->create();
        if ($result) {
            return true;
        }

        $response = $this->ask("Implementations file already exists. Do you want to overwrite it?", 'Yes');
        if (!$this->isResponsePositive($response)) {
            $this->warn("Implementation class wasn't created");
            return false;
        }

        $result = $this->repositoryCreator->complete();
        if (!is_array($result)) {
            throw new Exception("There was an error while creating implementation file");
        }
    }

    public function bindClasses()
    {
        $this->call('make:binding', ['repository' => $this->input]);
    }

    /**
     * Determine if the user input is positive.
     *
     * @param String $response
     *
     * @return bool
     */
    private function isResponsePositive(String $response): bool
    {
        return in_array(strtolower($response), ['y', 'yes'], true);
    }

    // private function isLumen()
    // {
    //     return str_contains(app()->version(), 'Lumen');
    // }
}
