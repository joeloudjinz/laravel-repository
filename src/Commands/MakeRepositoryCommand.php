<?php

namespace Inz\Commands;

use Exception;
use Inz\Base\Abstractions\Command;

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
    protected $description = "Create a new repository with it's interface";

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!$this->isValidArgument('model')) {
            $this->missingArgumentError('model');
            return;
        }

        $this->prepareForMakeRepository();

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

        $this->call('bind:repository', ['model' => $this->modelArgument]);
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
}
