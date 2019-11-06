<?php

namespace Inz\Repository\Commands;

use Exception;
use Inz\Repository\Base\ModelCreator;
use Illuminate\Support\Facades\Artisan;
use Inz\Repository\Base\ContractCreator;

class MakeRepositoryCommand extends RepositoryCommand
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
     * Stub paths.
     *
     * @var array
     */
    protected $stubs = [
        'contract' => __DIR__ . '/../stubs/Contracts/ExampleRepository.stub',
        'repository' => __DIR__ . '/../stubs/Eloquent/EloquentExampleRepository.stub',
    ];

    /**
     * Full namespace of the model.
     *
     * @var string
     */
    protected $model;

    /**
     * Model class name.
     *
     * @var string
     */
    protected $modelName;

    protected $subDir;
    /**
     * @var ContractCreator
     */
    private $contractCreator;
    /**
     * @var ModelCreator
     */
    private $modelCreator;
    /**
     * The input of the command.
     *
     * @var String
     */
    private $input;
    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();

        if (!$this->hasArgument('model')) {
            // $this->error('No Model is specified');
            throw new Exception("No Model is specified");
        }

        $this->input = $this->argument('model');
        $this->modelCreator = new modelCreator($this->argument('model'));
        $this->contractCreator = new ContractCreator($this->argument('model'));
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

        // createContract() will return an array of two values, contract namespace in the first position
        // which will be assigned to $contract variable, and the name of the contract name in the
        // second position which will be assigned to $contractName, that's how list work.
        list($contractNamespace, $contractName) = $this->createContract();

        // using previously extracted variables to construct a repository implementation class
        $this->createRepository($contractNamespace, $contractName);

        // bind the created contract interface to repository implementation class
        $this->bindingRepository();
    }

    public function bindingRepository()
    {
        Artisan::call('make:binding', [
            'repository' => $this->argument('model'),
        ]);
    }

    /**
     * Handle the process of delivering data to contract creator & processing
     * results of the creation
     *
     * @return array|null
     */
    protected function createContract()
    {
        $result = $this->contractCreator->create();
        if (is_array($result)) {
            return $result;
        }

        $response = $this->ask("Contract file already exists. Do you want to overwrite it?", 'Yes');
        if (!$this->isResponsePositive($response)) {
            // TODO: handle negative response return statement
            $this->warn("Contract wasn't created");
        }

        $result = $this->contractCreator->complete();
        if (!is_array($result)) {
            // TODO: handle when complete() doesn't create the file return statement
            $this->error('There was an error while creating contract file');
        }
    }

    /**
     * Create a new repository.
     *
     * @param mixed $contract
     * @param mixed $contractName
     *
     * @return void
     */
    protected function createRepository($contract, $contractName)
    {
        // get the content of the stub file of repository
        $content = $this->fileManager->get($this->stubs['repository']);

        // preparing strings that will be replaced in the content of the repository class
        $replacements = [
            '%contract%' => $this->appNamespace . $contract,
            '%contractName%' => $contractName,
            '%model%' => $this->model,
            '%modelName%' => $this->modelName,
            '%namespaces.repositories%' => $this->appNamespace . $this->config('namespaces.repositories') . $this->subDir,
        ];

        // replacing each string that match a key in $replacements with the value of that key in $content
        $content = str_replace(array_keys($replacements), array_values($replacements), $content);

        // preparing the name of repository implementation class
        $fileName = 'Eloquent' . $this->modelName . 'Repository';
        // preparing the full directory name in which repositories implementations will be stored
        $fileDirectory = app()->basePath() . '/app/' . $this->config('paths.repositories') . $this->subDir . '\\';
        // preparing the full path to the repository implementation file
        $filePath = $fileDirectory . $fileName . '.php';

        // Checking if the directory doesn't exists
        if (!$this->fileManager->exists($fileDirectory)) {
            // if so, create it
            $this->fileManager->makeDirectory($fileDirectory, 0755, true);
        }

        // checking that the application is running in the console and
        // the repository implementation file exist in the directory
        if ($this->laravel->runningInConsole() && $this->fileManager->exists($filePath)) {
            // if the file exists, ask developer if he desires to override it
            $response = $this->ask("The repository [{$fileName}] already exists. Do you want to overwrite it?", 'Yes');

            // if the answer was other then 'yes' or 'y'
            if (!$this->isResponsePositive($response)) {
                // will not override the existing file & will not create the new file
                $this->line("The repository [{$fileName}] will not be overwritten.");
                // TODO: handle return statement in the case where the file exists already
                return;
            }
        }

        // if the file doesn't exist, inform the developer that the repository implementation class was created
        $this->line("The repository [{$fileName}] has been created.");

        // create the repository implementation class
        $this->fileManager->put($filePath, $content);
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
                // $this->line("Model [{$this->input}] has been successfully created.");
                return true;
            }

            $this->warn("Model wasn't created, aborting command.");
            return false;
        }
    }

    protected function isLumen()
    {
        return str_contains(app()->version(), 'Lumen');
    }
}
