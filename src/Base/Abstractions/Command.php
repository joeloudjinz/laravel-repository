<?php

namespace Inz\Base\Abstractions;

use Inz\Base\Assistors\ModelAssistor;
use Inz\Base\Creators\ContractCreator;
use Inz\Base\Assistors\ProviderAssistor;
use Inz\Base\Creators\RepositoryCreator;
use Illuminate\Console\Command as BaseCommand;

abstract class Command extends BaseCommand
{
    /**
     * @var ProviderAssistor
     */
    protected $providerAssistor;
    /**
     * @var ModelAssistor
     */
    protected $modelAssistor;
    /**
     * @var ContractCreator
     */
    protected $contractCreator;
    /**
     * @var RepositoryCreator
     */
    protected $repositoryCreator;
    /**
     * The name of the service provider class.
     *
     * @var String
     **/
    protected $providerName = 'RepositoryServiceProvider';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Takes care of initializing MakeRepositoryCommand attributes.
     *
     * @return void
     */
    protected function prepareForMakeRepository()
    {
        $this->modelArgument = $this->argument('model');
        $this->modelAssistor = new ModelAssistor($this->modelArgument);
        $this->contractCreator = new ContractCreator($this->modelArgument);
        $this->repositoryCreator = new RepositoryCreator($this->modelArgument);
    }

    /**
     * Takes care of initializing MakeBindCommand attributes.
     *
     * @return void
     */
    protected function prepareForBindRepository()
    {
        $this->modelArgument = $this->argument('model');
        $this->contractCreator = new ContractCreator($this->modelArgument);
        $this->repositoryCreator = new RepositoryCreator($this->modelArgument);
        $this->providerAssistor = new ProviderAssistor($this->providerName);
    }

    /**
     * Checks the presence & validity of the argument with the given name.
     *
     * @param String $name
     *
     * @return bool
     */
    protected function isValidArgument(String $name): bool
    {
        return $this->hasArgument($name) &&
        $this->argument($name) !== '';
    }

    /**
     * Determine if the user input is positive.
     *
     * @param String $response
     *
     * @return bool
     */
    protected function isResponsePositive(String $response): bool
    {
        return in_array(strtolower($response), ['y', 'yes'], true);
    }

    /**
     * Prints an error of a missing argument.
     *
     * @param String $name
     *
     * @return void
     */
    protected function missingArgumentError(String $name)
    {
        $name = ucfirst($name);
        $this->error("{$name} argument is missing");
    }
}
