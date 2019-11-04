<?php

namespace Inz\Repository\Base;

use Illuminate\Support\Arr;

class ContractCreator extends Creator
{
    /**
     * Model name inserted by the developer.
     *
     * @var String
     */
    private $modelName;
    /**
     * Stub path of the file that will be generated.
     *
     * @var String
     */
    private $stub;
    /**
     * Key value pairs array of parts that will be replaced in the content.
     *
     * @var array
     */
    private $replacements = [];

    public function __construct(String $input)
    {
        parent::__construct();
        $values = $this->extractInputValues($input);
        $this->modelName = $values['modelName'];
        if (Arr::has($values, 'subdirectory')) {
            $this->subdirectory = $values['subdirectory'];
        }
        $this->stub = __DIR__ . '/Stubs/Contracts/ExampleRepository.stub';
        $this->classNameSuffix = 'RepositoryInterface';
        $this->configType = 'contracts';
        $this->setPathFromConfig();
        $this->setNamespaceFromConfig();
        $this->initializeReplacementsParts($this->modelName);
    }

    /**
     * Initialize the array of the parts that will be replaced.
     *
     * @param String $modelName
     * @return void
     */
    public function initializeReplacementsParts(String $modelName)
    {
        $this->replacements = [
            '%namespaces.contracts%' => $this->appNamespace . $this->namespaceConfig . $this->subdirectory,
            '%modelName%' => $modelName,
        ];
    }

    /**
     * Takes care of the creation process of the contract file, if the file does exist
     * it will abort the process and return false, else it will create it and return
     * the full name space to it with it's name in an array.
     *
     * @return mixed bool|array
     */
    public function create()
    {
        // get the content of the stub file of contract
        $this->extractStubContent($this->stub);

        // replacing each string that match a key in $replacements with the value of that key in $content
        $this->replaceContentParts($this->replacements);

        // preparing repository contract (interface) class name
        $this->createClassName($this->modelName);

        // preparing the full path to the directory where the contracts will be stored
        $this->generateDirectoryFullPath($this->directoryBasePath(), $this->pathConfig);

        // checking that the directory of repository contracts doesn't exist
        if (!$this->directoryExists($this->directory)) {
            // if so, create the directory
            $this->createDirectory($this->directory);
        }

        // preparing the full path to the repository contract file
        $this->generateFileFullPath($this->directory, $this->className);

        // checking that the repository contract file does not exist in the directory
        if (!$this->fileExists($this->path)) {
            // creating th file
            $result = $this->createFile($this->path, $this->content);
            // if the file wasn't created
            if (is_bool($result)) {
                return false;
            }
            // else, return response
            return $this->getReturnedData();
        }

        // if the file does exist, don't create the file & return false
        return false;
    }

    /**
     * Completes the creation process when it was aborted due to the existence of the file.
     *
     * @return array|bool
     */
    public function complete()
    {
        // overriding the existing file
        $result = $this->createFile($this->path, $this->content);
        // if the file wasn't created
        if (is_bool($result)) {
            return false;
        }
        // else, return response
        return $this->getReturnedData();
    }

    /**
     * Prepares the array that will be returned by the creation methods of the class.
     *
     * @return array
     */
    private function getReturnedData(): array
    {
        return [
            $this->getClassFullNamespace($this->baseNamespace(), $this->className),
            $this->className,
        ];
    }

    public function getStub()
    {
        return $this->stub;
    }

    public function getReplacements()
    {
        return $this->replacements;
    }
}
