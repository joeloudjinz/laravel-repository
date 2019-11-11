<?php

namespace Inz\Base\Creators;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Inz\Base\ConfigurationResolver;

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
    protected $stub = __DIR__ . '/../Stubs/provider.stub';

    public function __construct(String $name)
    {
        $this->name = $name;
        $this->basePath = $this->getBasePath();
        $this->baseNamespace = $this->getBaseNamespace();
    }

    /**
     * Checks the existence of a provider class.
     *
     * @return bool
     */
    public function providerExist(): bool
    {
        return File::exists($this->getFullClassPath());
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
        $oldContent = File::get($this->getFullClassPath());
        // figuring out the position where we want to insert the key value pairs,
        // we are adding 2 to pass these two chars '[' & '\n'
        $position = strpos($oldContent, "[\n") + 2;
        // extracting the first slice of the content, this slice holds the name
        // of the array classes
        $firstSlice = substr($oldContent, 0, $position);
        // extracting the second slice of the content, this slice holds the
        // content of classes array which we will operate on
        $lastSlice = substr($oldContent, $position);
        // adding the key values pairs to classes array
        $lastSlice = "        '{$contract}' => '{$implementation}',\n" . $lastSlice;
        // constructing the new content of the provider file
        $newContent = $firstSlice . $lastSlice;
        return is_int(File::put($this->getFullClassPath(), $newContent));
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
        $content = File::get($this->getFullClassPath());
        return is_int(strpos($content, $contract));
    }

    public function getBasePath(): String
    {
        return ConfigurationResolver::basePathOfProviders() . DIRECTORY_SEPARATOR . 'Providers' . DIRECTORY_SEPARATOR;
    }

    public function getBaseNamespace(): String
    {
        return ConfigurationResolver::baseNamespaceOfProviders() . 'Providers';
    }

    public function getInstance()
    {
        return $this->instance;
    }
}
