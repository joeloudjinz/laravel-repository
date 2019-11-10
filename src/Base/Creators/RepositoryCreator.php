<?php

namespace Inz\Base\Creators;

use Inz\Base\Abstractions\Creator;

class RepositoryCreator extends Creator
{
    /**
     * Key value pairs array of parts that will be replaced in the content.
     *
     * @var array
     */
    private $replacements = [];

    public function __construct(string $input)
    {
        parent::__construct($input);
        $this->stub = __DIR__.'/../Stubs/implementation.stub';
        $this->classNameSuffix = 'Repository';
    }

    /**
     * Initialize the array of the parts that will be replaced.
     *
     * @param string $contractNamespace
     * @param string $contractName
     * @param string $modelNamespace
     *
     * @return void
     */
    public function initializeReplacementsParts(string $contractNamespace, string $contractName, string $modelNamespace)
    {
        $this->replacements = [
            '%contractNamespace%'      => $contractNamespace,
            '%contractName%'           => $contractName,
            '%modelNamespace%'         => $modelNamespace,
            '%modelName%'              => $this->modelName,
            '%repositoriesNamespaces%' => $this->baseNamespace(),
        ];
    }

    /**
     * Takes care of the creation process of the repository file, if the file does exist
     * it will abort the process and return false, else it will create it and return.
     *
     * @return bool
     */
    public function create()
    {
        // get the content of the stub file of repository
        $this->extractStubContent($this->stub);

        // replacing each string that match a key in $replacements with the value of that key in $content
        $this->replaceContentParts($this->replacements);

        // preparing repository repository (interface) class name
        $this->createClassName($this->modelName);

        // preparing the full path to the directory where the repositories will be stored
        $this->generateDirectoryFullPath($this->directoryBasePath(), $this->pathConfig);

        // checking that the directory of repository repositories doesn't exist
        if (!$this->directoryExists($this->directory)) {
            // if so, create the directory
            $this->createDirectory($this->directory);
        }

        // preparing the full path to the repository repository file
        $this->generateFileFullPath($this->directory, $this->className);

        // checking that the repository repository file does not exist in the directory
        if (!$this->fileExists($this->path)) {
            // creating th file
            $result = $this->createFile($this->path, $this->content);
            // if the file wasn't created
            if (is_bool($result)) {
                return false;
            }
            // else, return response
            return true;
        }

        // if the file does exist, don't create the file & return false
        return false;
    }

    /**
     * Completes the creation process when it was aborted due to the existence of the file.
     *
     * @return bool
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
        return true;
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
