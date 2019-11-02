<?php

namespace Inz\Repository\Base;

use Exception;
use Illuminate\Filesystem\Filesystem;

abstract class Creator
{
    /**
     * File manager.
     *
     * @var Illuminate\Filesystem\Filesystem
     */
    protected $fileManager;
    /**
     * Application base namespace.
     *
     * @var string
     */
    protected $appNamespace;
    /**
     * Stub paths.
     *
     * @var array
     */
    protected $stubs = [
        'contract' => __DIR__ . '/Stubs/Contracts/ExampleRepository.stub',
        'repository' => __DIR__ . '/Stubs/Eloquent/EloquentExampleRepository.stub',
    ];
    /**
     * Stub path of the file that will be generated.
     *
     * @var array
     */
    protected $stub;
    /**
     * The content of the stub file that will be manipulated.
     *
     * @var String
     */
    protected $content;
    /**
     * Key value pairs array of parts that will be replaced in the content.
     *
     * @var array
     */
    protected $replacements = [];
    /**
     * name of the class that will be generated.
     *
     * @var String
     */
    protected $className;
    /**
     * The string that will be concatenated with the class name.
     *
     * @var String
     */
    protected $classNameAddition;
    /**
     * Full path to the directory in which the generated file will be stored.
     *
     * @var String
     */
    protected $directory;
    /**
     * Full path to the file that will be generated.
     *
     * @var String
     */
    protected $path;
    /**
     * Permissions for directory, used during creation of the directory.
     *
     * @var int
     */
    protected $permissions = 0755;
    /**
     * The subdirectory specified by the developer.
     *
     * @var String
     */
    protected $subdirectory;
    /**
     * The path value from config file related to the current class
     *
     * @var String
     */
    protected $pathConfig;
    /**
     * The namespace value from config file related to the current class
     *
     * @var String
     */
    protected $namespaceConfig;

    public function __construct()
    {
        $this->fileManager = app()->singleton(Filesystem::class);
        $this->appNamespace = app()->getNamespace();
    }

    abstract public function create();

    /**
     * Extracts the content from the stub file when the $stub attribute is defined, else
     * it will initialize it to null.
     *
     * @return bool
     */
    public function extractContent(): bool
    {
        if (!is_null($this->stub)) {
            $this->content = $this->fileManager->get($this->stubs['contract']);
            return true;
        }
        $this->content = null;
        return false;
    }
    /**
     * Return the value of the content attribute.
     *
     * @return String
     */
    public function getContent(): String
    {
        return $this->content;
    }

    /**
     * Replace all the occurrences of the strings that matches the keys in $replacements
     * array with the their values in the array, the replacement process will be
     * performed on $content attribute.
     *
     * @return bool
     */
    public function replaceContentParts(): bool
    {
        try {
            $this->content = str_replace(
                array_keys($this->replacements),
                array_values($this->replacements),
                $this->content
            );
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Creates a class name based on the passed model name and the addition part.
     *
     * @param String $modelName
     * the name of the model class that is associated with the generated file.
     *
     * @return String
     */
    public function createClassName(String $modelName): String
    {
        return $this->className = $modelName . $this->classNameAddition;
    }

    /**
     * Generates a full path value to the directory  in which the generated file will be stored.
     *
     * @return String
     */
    public function generateDirectoryFullPath(): String
    {
        if (!$this->isNotEmpty($this->pathConfig)) {
            // TODO: throw PathConfigValueIsMissing an exception instead of returning null
            return null;
        }

        $base = app()->basePath() . '/app/' . $this->config($this->pathConfig);

        if ($this->isNotEmpty($this->subdirectory)) {
            return $this->directory = $base . $this->subdirectory . '\\';
        }

        return $base;
    }

    /**
     * Generates a full path value to the file that is generated.
     *
     * @return String
     */
    public function generateFileFullPath(): String
    {
        if (!$this->isNotEmpty($this->directory)) {
            // TODO: throw DirectoryValueIsMissing an exception instead of returning null
            return null;
        }

        return $this->directory . $this->className . '.php';
    }

    /**
     * Checks the existence of the directory for the category of the generated files by the current class.
     *
     * @return bool
     */
    public function directoryExists(): bool
    {
        return $this->fileManager->exists($this->directory);
    }

    /**
     * Create the directory for the generated file by the current class.
     *
     * @return bool
     */
    public function createDirectory(): bool
    {
        return $this->fileManager->makeDirectory($this->directory, $this->permissions, true);
    }

    /**
     * Checks the existence of the file that will be generated by the current class.
     *
     * @return bool
     */
    public function fileExists(): bool
    {
        return $this->fileManager->exists($this->path);
    }

    /**
     * Creates the file.
     *
     * @return int
     */
    public function createFile(): int
    {
        return $this->fileManager->put($this->path, $this->content);
    }

    /**
     * Gets the full namespace of the generated class.
     *
     * @return String
     */
    public function getClassFullNamespace(): String
    {
        if (!$this->isNotEmpty($this->namespaceConfig)) {
            // TODO: throw NamespaceConfigValueIsMissing an exception instead of returning null
            return null;
        }

        if ($this->isNotEmpty($this->subdirectory)) {
            return $this->namespaceConfig . $this->subdirectory . $this->className;
        }
        
        return $this->namespaceConfig . $this->className;
    }

    /**
     * Checks if the given array is not null, is a string & not empty
     *
     * @return bool
     */
    private function isNotEmpty(String $string)
    {
        return !is_null($string) && is_string($string) && $string !== '';
    }
}
