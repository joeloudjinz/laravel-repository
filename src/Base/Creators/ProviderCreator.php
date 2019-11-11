<?php

namespace Inz\Base\Creators;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class ProviderAssistor
{
    /**
     * Provider's class name
     *
     * @var String
     */
    protected $name;
    /**
     * Provider's instance
     *
     * @var String
     */
    protected $instance;
    /**
     * Providers base path
     *
     * @var String
     */
    protected $basePath;
    /**
     * Providers base path
     *
     * @var String
     */
    protected $baseNamespace;
    /**
     * The stub of provider class
     *
     * @var String
     */
    protected $stub = __DIR__ . '/../Stub/provider.stub';

    public function __construct(String $name)
    {
        $this->name = $name;
        $this->basePath = app()->basePath() . '/app/Providers/';
        $this->baseNamespace = app()->getNamespace() . 'Providers';
    }

    /**
     * Checks the existence of a provider class.
     *
     * @return bool
     */
    public function providerExist(): bool
    {
        return class_exists($this->getFullClassName());
    }

    /**
     * Initialize an object of the given provider class.
     *
     * @return ServiceProvider
     */
    public function providerInitiator(): ServiceProvider
    {
        return $this->instance = app()->make($this->getFullClassName());
    }

    /**
     * Adds a repository entry to the array of classes in the service provider.
     *
     * @param String $contract
     * @param String $implementation
     * @return bool
     */
    public function addRepositoryEntry($contract, $implementation): bool
    {
        $this->addKeyValue($this->instance->classes, $contract, $implementation);
        return $this->isRepositoryBound($contract);
    }

    /**
     * Replace the content of the generated provider class.
     *
     * @return bool
     */
    public function replaceContent(): bool
    {
        $result = File::put($this->getFullClassPath(), File::get($this->stub));
        return is_int($result);
    }

    /**
     * Return full namespace of the provider class
     *
     * @return String
     */
    public function getFullClassName()
    {
        return $this->baseNamespace . '\\' . $this->name;
    }

    /**
     * Return full namespace of the provider class
     *
     * @return String
     */
    public function getFullClassPath()
    {
        return $this->basePath . $this->name . '.php';
    }
    /**
     *
     */
    public function isRepositoryBound(String $contract): bool
    {
        return Arr::has($this->instance->classes, $contract);
    }

    /**
     * Adds a key value pair directly into the given array.
     *
     * @param array $array passed by reference
     * @param mixed $key
     * @param mixed $value
     *
     * @return array
     */
    private function addKeyValue(array &$array, $key, $value)
    {
        return $array = Arr::add($array, $key, $value);
    }
}
