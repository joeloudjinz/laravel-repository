<?php

namespace Inz\Commands;

use Illuminate\Console\Command;
use Inz\Base\Creators\ContractCreator;
use Inz\Base\Creators\ProviderAssistor;
use Inz\Base\Creators\RepositoryCreator;

class MakeBindingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:binding {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add repository binding of the given model to service provider.';
    
    /**
     * The name of the service provider class.
     *
     * @var String
     **/
    protected $providerName = 'RepositoryServiceProvider';
    /**
     * @var ProviderAssistor
     */
    private $assistor;
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
        $this->assistor = new ProviderAssistor($this->providerName);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->modelArgument = $this->argument('model');
        $this->contractCreator = new ContractCreator($this->modelArgument);
        $this->repositoryCreator = new RepositoryCreator($this->modelArgument);

        // test if doesn't exists
        if (!$this->assistor->providerExist()) {
            $this->call('make:provider', ['name' => $this->providerName]);
            $this->assistor->replaceContent();
        } elseif ($this->assistor->isRepositoryBound($this->contractCreator->getClassName())) {
            $this->warn("Model's repository already bound in the service provider");
            return;
        }

        if ($this->bind()) {
            $this->info('Repository bound successfully');
            return;
        }

        $this->warn('Error while binding repository!');
    }

    /**
     * Perform the binding process
     *
     * @return bool
     **/
    private function bind()
    {
        $contract = $this->contractCreator->getClassFullNamespace(
            $this->contractCreator->baseNamespace(),
            $this->contractCreator->getClassName()
        );
        $implementation = $this->repositoryCreator->getClassFullNamespace(
            $this->repositoryCreator->baseNamespace(),
            $this->repositoryCreator->getClassName()
        );
        return $this->assistor->addRepositoryEntry($contract, $implementation);
    }
}
