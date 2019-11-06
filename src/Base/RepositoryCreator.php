<?php

namespace Inz\Repository\Base;

use Illuminate\Support\Arr;

class RepositoryCreator extends Creator
{
    /**
     * Contract full namespace.
     *
     * @var String
     */
    private $contractNamespace;
    /**
     * Contract name.
     *
     * @var String
     */
    private $contractName;

    public function __construct(String $input, $appBasePath = null)
    {
        parent::__construct($appBasePath);
        $values = $this->extractInputValues($input);
        $this->modelName = $values['modelName'];
        if (Arr::has($values, 'subdirectory')) {
            $this->subdirectory = $values['subdirectory'];
        }
        $this->stub = __DIR__ . '/Stubs/Eloquent/EloquentExampleRepository.stub';
        $this->classNameSuffix = 'Repository';
        $this->configType = 'repositories';
        $this->setPathFromConfig();
        $this->setNamespaceFromConfig();
    }

    /**
     * Initialize the array of the parts that will be replaced.
     *
     * @param String $modelName
     * @return void
     */
    public function initializeReplacementsParts(String $contractNamespace, String $contractName, String $modelNamespace)
    {
        $this->replacements = [
            '%contract%' => $this->appNamespace . $contractNamespace,
            '%contractName%' => $contractName,
            '%model%' => $modelNamespace,
            '%modelName%' => $this->modelName,
            '%namespaces.repositories%' => $this->appNamespace . $this->namespaceConfig . $this->subdirectory,
        ];
    }

    /**
     * Takes care of the creation process of the repository file, if the file does exist
     * it will abort the process and return false, else it will create it and return
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
}
