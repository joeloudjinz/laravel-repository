<?php

namespace Inz\Commands;

use Exception;
use Illuminate\Console\Command;
use Inz\Base\Creators\ContractCreator;
use Inz\Base\Creators\ModelAssistor;
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
     * @var ModelAssistor
     */
    private $modelAssistor;
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
        $this->modelAssistor = new ModelAssistor($this->modelArgument);
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
        $this->info("{$this->contractCreator->getClassName()} created successfully");

        if (!$this->createRepository($result[0], $result[1])) {
            return;
        }
        $this->info("{$this->repositoryCreator->getClassName()} created successfully");

        // $this->bindClasses();
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
        if ($this->modelAssistor->modelExist()) {
            return true;
        }

        $response = $this->ask("Model [{$this->modelArgument}] does not exist. Would you like to create it?", 'Yes');
        if (!$this->isResponsePositive($response)) {
            $this->warn("Model wasn't created, aborting command.");
            return false;
        }

        $this->callSilent('make:model', ['name' => $this->modelArgument]);
        $this->info("Model {$this->modelArgument} created successfully");
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

        return $result;
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
            $this->modelAssistor->getModelFullNamespace()
        );

        if ($this->repositoryCreator->create()) {
            return true;
        }

        $response = $this->ask("Implementations file already exists. Do you want to overwrite it?", 'Yes');
        if (!$this->isResponsePositive($response)) {
            $this->warn("Implementation class wasn't created");
            return false;
        }

        if (!$this->repositoryCreator->complete()) {
            throw new Exception("There was an error while creating implementation file");
        }

        return true;
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
