<?php

namespace Inz\Repository\Base;

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
     * @var array
     */
    private $stub;
    /**
     * Key value pairs array of parts that will be replaced in the content.
     *
     * @var array
     */
    private $replacements = [];
    /**
     * name of the class that will be generated.
     *
     * @var String
     */
    private $className;
    /**
     * The string that will be concatenated with the class name.
     * Ex: in case of generating an interface, the value should be 'Interface'
     *
     * @var String
     */
    private $classNameSuffix;
    /**
     * Full path to the directory in which the generated file will be stored.
     *
     * @var String
     */
    private $directory;
    /**
     * Full path to the file that will be generated.
     *
     * @var String
     */
    private $path;
    /**
     * The subdirectory specified by the developer.
     *
     * @var String
     */
    private $subdirectory;
    /**
     * Config array key of the current class
     *
     * @var String
     */
    private $configType;
    /**
     * The path value from config file related to the current class
     *
     * @var String
     */
    private $pathConfig;
    /**
     * The namespace value from config file related to the current class
     *
     * @var String
     */
    private $namespaceConfig;
    public function __construct(String $modelName)
    {
        parent::__construct();
        $this->modelName = $modelName;
        $this->stub = __DIR__ . '/Stubs/Contracts/ExampleRepository.stub';
        $this->classNameSuffix = 'RepositoryInterface';
        $this->configType = 'contracts';
        $this->setPathFromConfig();
        $this->setNamespaceFromConfig();
        $this->initializeReplacementsParts($modelName);
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
        // // get the content of the stub file of contract
        // $this->extractStubContent();

        // // replacing each string that match a key in $replacements with the value of that key in $content
        // $this->replaceContentParts();

        // // preparing repository contract (interface) class name
        // $this->createClassName($this->modelName);

        // // preparing the full path to the directory where the contracts will be stored
        // $this->generateDirectoryFullPath();

        // // preparing the full path to the repository contract file
        // $this->generateFileFullPath();

        // // checking that the directory of repository contracts doesn't exist
        // if (!$this->directoryExists()) {
        //     // if so, create the directory
        //     $this->createDirectory();
        // }

        // // checking that the repository contract file does not exist in the directory
        // if (!$this->fileExists()) {
        //     // creating th file
        //     $result = $this->createFile();
        //     // if the file wasn't created
        //     if (is_bool($result)) {
        //         return false;
        //     }
        //     // else, return response
        //     return $this->getReturnedData();
        // }

        // // if the file does exist, don't create the file & return false
        // return false;
    }

    /**
     * Completes the creation process when it was aborted due to the existence of the file.
     *
     * @return array|bool
     */
    public function complete()
    {
        // // overriding the existing file
        // $result = $this->createFile();
        // // if the file wasn't created
        // if (is_bool($result)) {
        //     return false;
        // }
        // // else, return response
        // return $this->getReturnedData();
    }

    /**
     * Prepares the array that will be returned by the creation methods of the class.
     *
     * @return array
     */
    private function getReturnedData(): array
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

    public function getReplacements()
    {
        return $this->replacements;
    }

    public function getClassName()
    {
        return $this->className;
    }

    public function getClassNameSuffix()
    {
        return $this->classNameSuffix;
    }

    public function getDirectory()
    {
        return $this->directory;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getSubdirectory()
    {
        return $this->subdirectory;
    }

    public function getConfigType()
    {
        return $this->configType;
    }

    public function getPathConfig()
    {
        return $this->pathConfig;
    }

    public function getNamespaceConfig()
    {
        return $this->namespaceConfig;
    }
}
