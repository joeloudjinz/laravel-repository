<?php

namespace Inz\Commands;

use Exception;
use Inz\Base\Creators\ModelCreator;
use Inz\Base\Creators\ContractCreator;
use Illuminate\Console\Command;
use Inz\Base\Creators\RepositoryCreator;

class MakeRepositoryCommand extends Command
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
    protected $modelArgument;
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
        parent::__construct();
    }

    /**
     * Takes care of initializing class attributes after the constructor
     * has completed constructing the object.
     *
     * @return void
     */
    private function initializeAttributes()
    {
        $this->modelArgument = $this->argument('model');
        $this->modelCreator = new ModelCreator($this->modelArgument);
        $this->contractCreator = new ContractCreator($this->modelArgument);
        $this->repositoryCreator = new RepositoryCreator($this->modelArgument);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        if (!$this->isValidArgument('model')) {
            $this->error('Model name is missing');
            return;
        }

        $this->initializeAttributes();

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
     * Checks the presence & validity of the argument with the given name.
     *
     * @param String $name
     *
     * @return bool
     */
    private function isValidArgument(String $name): bool
    {
        return $this->hasArgument($name) &&
        $this->argument($name) !== '';
    }

    public function bindClasses()
    {
        $this->call('make:binding', ['repository' => $this->modelArgument]);
    }

    /**
     * Checks the models existence, it will be created if the developer approved
     *
     * @return boolean.
     */
    protected function processModelExistence(): bool
    {
        if ($this->laravel->runningInConsole() && !$this->modelCreator->modelExist()) {
            $response = $this->ask("Model [{$this->modelArgument}] does not exist. Would you like to create it?", 'Yes');

            if ($this->isResponsePositive($response)) {
                $this->callSilent('make:model', ['name' => $this->modelArgument]);
                $this->line("Model [{$this->modelArgument}] created successfully");
                return true;
            }

            $this->warn("Model wasn't created, aborting command.");
            return false;
        }
        return true;
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
}
