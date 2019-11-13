<?php

namespace Inz\Base\Creators;

use Inz\Base\Abstractions\Creator;

class RepositoryCreator extends Creator
{
    public function __construct(String $input)
    {
        parent::__construct($input);
        $this->stub = __DIR__ . '/../Stubs/implementation.stub';
        $this->classNameSuffix = 'Repository';
        $this->createClassName($this->modelName);
        $this->extractStubContent($this->stub);
        $this->generateDirectoryFullPath();
        $this->generateFileFullPath();
    }

    /**
     * Initialize the array of the parts that will be replaced.
     *
     * @param String $contractNamespace
     * @param String $contractName
     * @param String $modelNamespace
     * @return void
     */
    public function initializeReplacementsParts(String $contractNamespace, String $contractName, String $modelNamespace)
    {
        $this->replacements = [
            '%contractNamespace%' => $contractNamespace,
            '%contractName%' => $contractName,
            '%modelNamespace%' => $modelNamespace,
            '%modelName%' => $this->modelName,
            '%repositoriesNamespaces%' => $this->baseNamespace(),
        ];
    }

    /**
     * Return the appropriate data when the process of creation fails.
     *
     * @return bool
     */
    public function returnedDataWhenFailure()
    {
        return false;
    }

    /**
     * Return the appropriate data when the process of creation executed successfully.
     *
     * @return bool
     */
    public function returnedDataWhenSuccess()
    {
        return true;
    }

    public function getStub()
    {
        return $this->stub;
    }
}
