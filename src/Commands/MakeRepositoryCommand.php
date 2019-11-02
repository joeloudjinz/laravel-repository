<?php

namespace Inz\Repository\Commands;

use Illuminate\Support\Facades\Artisan;

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
        $this->checkModel();

        // createContract() will return an array of two values, contract namespace in the first position
        // which will be assigned to $contract variable, and the name of the contract name in the
        // second position which will be assigned to $contractName, that's how list work.
        list($contract, $contractName) = $this->createContract();

        // using previously extracted variables to construct a repository implementation class
        $this->createRepository($contract, $contractName);

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
     * Create a new contract.
     */
    protected function createContract()
    {
        // get the content of the stub file of contract
        $content = $this->fileManager->get($this->stubs['contract']);

        // prepare the parts that are going to be replaced in the contract file
        $replacements = [
            // constructing the namespace of the contract
            '%namespaces.contracts%' => $this->appNamespace . $this->config('namespaces.contracts') . $this->subDir,
            '%modelName%' => $this->modelName,
        ];

        // replacing each string that match a key in $replacements with the value of that key in $content
        $content = str_replace(array_keys($replacements), array_values($replacements), $content);

        // preparing repository contract (interface) class name
        $fileName = $this->modelName . 'Repository';

        // preparing the full path to the directory where the contracts will be stored
        $fileDirectory = app()->basePath() . '/app/' . $this->config('paths.contracts') . $this->subDir . '\\';

        // preparing the full path to the repository contract file
        $filePath = $fileDirectory . $fileName . '.php';

        // checking that the directory of repository contracts doesn't exist
        if (!$this->fileManager->exists($fileDirectory)) {
            // if so, create the directory, 755 means read and execute access for
            // everyone and also write access for the owner of the file.
            $this->fileManager->makeDirectory($fileDirectory, 0755, true);
        }

        // checking that the application is running in the console and
        // the repository contract file exist in the directory
        if ($this->laravel->runningInConsole() && $this->fileManager->exists($filePath)) {
            // asking the developer if he desires to override the existing repository contract file
            $response = $this->ask("The contract [{$fileName}] already exists. Do you want to overwrite it?", 'Yes');

            // if the $response is other then 'yes' or 'y'
            if (!$this->isResponsePositive($response)) {
                // will not override the existing file & will not create the new file
                $this->line("The contract [{$fileName}] will not be overwritten.");
                // TODO: handle return statement in the case where the file exists already
                return;
            }
            // if the answer was positive, will create the new file which will override the existing one
            $this->fileManager->put($filePath, $content);
        } else {
            // if the file does not exist or the application isn't running in the console
            // create the file in the directory
            $this->fileManager->put($filePath, $content);
        }
        // inform the developer
        $this->line("The contract [{$fileName}] has been created.");

        // return the namespace of the created repository contract & it's interface name
        return [
            $this->config('namespaces.contracts') . $this->subDir . '\\' . $fileName,
            $fileName,
        ];
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
     * Checks the models existence, it will be create if the developer approved
     *
     * @return void.
     */
    protected function checkModel()
    {
        $model = str_replace('/', '\\', $this->argument('model'));
        $model_arr = explode('\\', $model);

        $this->modelName = array_pop($model_arr);
        $this->model = $this->appNamespace . $this->modelName;

        if (!$this->isLumen() && $this->laravel->runningInConsole()) {
            if (!class_exists($this->model)) {
                $response = $this->ask("Model [{$this->model}] does not exist. Would you like to create it?", 'Yes');

                if ($this->isResponsePositive($response)) {
                    Artisan::call('make:model', [
                        'name' => $this->model,
                    ]);

                    $this->line("Model [{$this->model}] has been successfully created.");
                } else {
                    $this->line("Model [{$this->model}] is not being created.");
                }
            }
        }

        if (count($model_arr) > 0) {
            $this->subDir = '\\' . implode('\\', $model_arr);
        } else {
            $this->subDir = '';
        }
    }

    protected function isLumen()
    {
        return str_contains(app()->version(), 'Lumen');
    }
}
