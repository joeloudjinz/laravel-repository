<?php

namespace Inz\Repository\Base;

class ContractCreator extends Creator
{
    public function __construct()
    {
        parent::__construct();

        $this->stub = _DIR__ . '/Stubs/Contracts/ExampleRepository.stub';
        $this->replacements = [
            '%namespaces.contracts%' => $this->appNamespace . $this->config('namespaces.contracts') . $this->subDir,
            '%modelName%' => $this->modelName,
        ];
        $this->classNameAddition = 'Interface';
        $this->pathConfig = config('repository.paths.contracts');
        $this->namespaceConfig = config('repository.namespaces.contracts');
    }

    /**
     * Takes care of the creation process of the contract file, if the file does exist
     * it will abort the process and return false, else it will create it and return
     * the full name space to it with it's name in an array.
     *
     * @param String $modelName
     *
     * @return mixed bool|array
     */
    public function create(String $modelName)
    {
        // get the content of the stub file of contract
        $this->extractContent();

        // replacing each string that match a key in $replacements with the value of that key in $content
        $this->replaceContentParts();

        // preparing repository contract (interface) class name
        $this->createClassName($modelName);

        // preparing the full path to the directory where the contracts will be stored
        $this->generateDirectoryFullPath();

        // preparing the full path to the repository contract file
        $this->generateFileFullPath();

        // checking that the directory of repository contracts doesn't exist
        if (!$this->directoryExists()) {
            // if so, create the directory
            $this->createDirectory();
        }

        // checking that the repository contract file does not exist in the directory
        if (!$this->fileExists()) {
            // creating th file
            $result = $this->createFile();
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
        $result = $this->createFile();
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
            $this->getClassFullNamespace(),
            $this->className,
        ];
    }
}
