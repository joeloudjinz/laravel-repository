<?php

namespace Inz\Repository\Base;

use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

/**
 * Abstracts the creation process of the files, this way we can reuse the logic to
 * generate any type of file for any pattern based on the configuration set up
 * in the config file of the package.
 */
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
     * The content of the stub file that will be manipulated.
     *
     * @var String
     */
    protected $content;
    /**
     * Permissions for directory, used during creation of the directory.
     *
     * @var int
     */
    protected $permissions = 0755;
    /**
     * Config array key of the current class
     *
     * @var String
     */
    protected $configType;
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
    /**
     * The string that will be concatenated with the class name.
     * Ex: in case of generating an interface, the value should be 'Interface'
     *
     * @var String
     */
    protected $classNameSuffix;
    /**
     * name of the class that will be generated.
     *
     * @var String
     */
    protected $className;

    public function __construct()
    {
        $this->fileManager = app()->make(Filesystem::class);
        $this->appNamespace = app()->getNamespace();
    }

    abstract public function create();
    abstract public function complete();

    /**
     * Extracts the content from the stub file when the $stub attribute is defined, else
     * it will initialize it to null.
     *
     * @param String $stubPath
     *
     * @return bool
     */
    public function extractStubContent(String $stubPath): bool
    {
        if (!is_null($stubPath)) {
            $this->content = $this->fileManager->get($stubPath);
            return true;
        }
        $this->content = null;
        return false;
    }

    /**
     * Based on the key value pairs in $replacements array, it will replace all
     * string occurrences that matches those keys with their values, the
     * replacement process will be performed on $content attribute.
     *
     * @param array $replacements
     *
     * @return bool
     */
    public function replaceContentParts(array $replacements): bool
    {
        try {
            $this->content = str_replace(
                array_keys($replacements),
                array_values($replacements),
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
        return $this->className = $modelName . $this->classNameSuffix;
    }

    /**
     * Generates a full path value to the directory in which the generated file will be stored.
     *
     * @return String
     */
    public function generateDirectoryFullPath(String $basePath, String $directoryName): String
    {
        // if (!$this->isNotEmpty($this->pathConfig)) {
        // TODO: throw PathConfigValueIsMissing an exception instead of returning null
        //     return null;
        // }

        // $base = app()->basePath() . '/app/' . $this->pathConfig;
        $base = $basePath . DIRECTORY_SEPARATOR . $directoryName . DIRECTORY_SEPARATOR;

        if ($this->isNotEmpty($this->subdirectory)) {
            return $this->directory = $base . $this->subdirectory . DIRECTORY_SEPARATOR;
        }

        return $this->directory = $base;
    }

    /**
     * Generates a full path value to the file that is generated.
     *
     * @return String
     */
    public function generateFileFullPath(String $directoryPath): String
    {
        if (!$this->isNotEmpty($this->directory)) {
            // TODO: throw DirectoryValueIsMissing an exception instead of returning null
            return null;
        }

        return $directoryPath . DIRECTORY_SEPARATOR . $this->className . '.php';
    }

    /**
     * Checks the existence of the directory for the category of the generated files by the current class.
     *
     * @return bool
     */
    public function directoryExists(String $path): bool
    {
        return $this->fileManager->exists($path);
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
    public function fileExists(String $path): bool
    {
        return $this->fileManager->exists($path);
    }

    /**
     * Creates the file.
     *
     * @return int
     */
    public function createFile(String $path, String $content): int
    {
        return $this->fileManager->put($path, $content);
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
     * Return the path value for the current class from the config file,
     * if the value from the config file is null it will construct path
     * value based on the pattern Repositories/ConfigTypeValue/
     *
     * @return void
     */
    public function setPathFromConfig()
    {
        $this->pathConfig = config('repository.paths.' . $this->configType) ??
        'Repositories' . DIRECTORY_SEPARATOR . Str::title($this->configType) . DIRECTORY_SEPARATOR;
    }

    /**
     * Return the namespace value for the current class from the config file,
     * if the value from the config file is null it will construct path
     * value based on the pattern Repositories\ConfigTypeValue
     *
     * @return Void
     */
    public function setNamespaceFromConfig()
    {
        $this->namespaceConfig =
        config('repository.namespaces.' . $this->configType) ??
        'Repositories\\' . Str::title($this->configType);
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

    public function getFileManager()
    {
        return $this->fileManager;
    }

    public function getAppNamespace()
    {
        return $this->appNamespace;
    }

    public function getClassNameSuffix()
    {
        return $this->classNameSuffix;
    }

    public function getClassName()
    {
        return $this->className;
    }

    /**
     * Checks if the given array is not null, is a string & not empty
     *
     * @return bool
     */
    private function isNotEmpty(?String $string)
    {
        return !is_null($string) && is_string($string) && $string !== '';
    }
}
