<?php

namespace Inz\Base\Creators;

use Inz\Base\Abstractions\Creator;

class ContractCreator extends Creator
{
    /**
     * Stub path of the file that will be generated.
     *
     * @var String
     */
    private $stub;

    public function __construct(String $input)
    {
        parent::__construct($input);
        $this->stub = __DIR__ . '/../Stubs/contract.stub';
        $this->classNameSuffix = 'RepositoryInterface';
        $this->createClassName($this->modelName);
        $this->extractStubContent($this->stub);
        $this->generateDirectoryFullPath();
        $this->generateFileFullPath();
    }

    /**
     * Initialize the array of the parts that will be replaced.
     *
     * @return void
     */
    public function initializeReplacementsParts()
    {
        $this->replacements = [
            '%contractsNamespace%' => $this->baseNamespace(),
            '%contractName%' => $this->className,
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
     * @return array
     */
    public function returnedDataWhenSuccess()
    {
        return [
            $this->getClassFullNamespace(),
            $this->className,
        ];
    }

    public function getStub()
    {
        return $this->stub;
    }
}
