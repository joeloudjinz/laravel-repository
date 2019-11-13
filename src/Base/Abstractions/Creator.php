<?php

namespace Inz\Base\Abstractions;

use Exception;
use Inz\Base\ConfigurationResolver;
use Illuminate\Support\Facades\File;

/**
 * Abstracts the creation process of the files, this way we can reuse the logic to
 * generate any type of file for any pattern based on the configuration set up.
 */
abstract class Creator
{
    /**
     * Application's path.
     *
     * @var string
     */
    protected $basePath;
    /**
     * Application's namespace.
     *
     * @var string
     */
    protected $baseNamespace;
    /**
     * Model name inserted by the developer.
     *
     * @var String
     */
    protected $modelName;
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
     * Permissions for directory, used during creation of the directory.
     *
     * @var int
     */
    protected $permissions = 0755;
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
    /**
     * Full path to the directory in which the generated file will be stored.
     *
     * @var String
     */
    protected $directory;
    /**
     * The subdirectory specified by the developer.
     *
     * @var String
     */
    protected $subdirectory;
    /**
     * Full path to the file that will be generated.
     *
     * @var String
     */
    protected $path;

    public function __construct(String $input)
    {
        $this->baseNamespace = ConfigurationResolver::baseNamespace();
        $this->basePath = ConfigurationResolver::basePath();
        $this->namespaceConfig = ConfigurationResolver::namespaceFor(get_called_class());
        $this->pathConfig = ConfigurationResolver::pathFor(get_called_class());
        $this->extractValuesFromInput($input);
    }

    abstract public function returnedDataWhenSuccess();
    abstract public function returnedDataWhenFailure();

    /**
     * Takes care of the creation process of the contract file, if the file does exist
     * it will abort the process.
     *
     * @return mixed bool|array
     */
    public function create()
    {
        $this->replaceContentParts($this->replacements);

        if (!$this->directoryExists()) {
            $this->createDirectory();
        }

        if ($this->fileExists()) {
            return $this->returnedDataWhenFailure();
        }

        if (is_bool($this->createFile())) {
            return $this->returnedDataWhenFailure();
        }

        return $this->returnedDataWhenSuccess();
    }

    /**
     * Completes the creation process when it was aborted due to the existence of the file.
     *
     * @return array|bool
     */
    public function complete()
    {
        if (is_bool($this->createFile())) {
            return $this->returnedDataWhenFailure();
        }

        return $this->returnedDataWhenSuccess();
    }

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
            $this->content = File::get($stubPath);
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
    public function generateDirectoryFullPath(): String
    {
        $base = $this->basePath . DIRECTORY_SEPARATOR . $this->pathConfig . DIRECTORY_SEPARATOR;

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
    public function generateFileFullPath(): String
    {
        return $this->path = $this->directory . $this->className . '.php';
    }

    /**
     * Checks the existence of the directory for the category of the generated files by the current class.
     *
     * @return bool
     */
    public function directoryExists(): bool
    {
        return File::exists($this->directory);
    }

    /**
     * Create the directory for the generated file by the current class.
     *
     * @return bool
     */
    public function createDirectory(): bool
    {
        try {
            return File::makeDirectory($this->directory, $this->permissions, true);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Checks the existence of the file that will be generated by the current class.
     *
     * @return bool
     */
    public function fileExists(): bool
    {
        return File::exists($this->path);
    }

    /**
     * Creates the file.
     *
     * @return int
     */
    public function createFile(): int
    {
        return File::put($this->path, $this->content);
    }

    /**
     * Gets the full namespace of the generated class.
     *
     * @return String
     */
    public function getClassFullNamespace(): String
    {
        return $this->baseNamespace() . '\\' . $this->className;
    }

    /**
     * Returns the base namespace of the generated classes, subdirectory name is included.
     *
     * @return String
     */
    public function baseNamespace(): String
    {
        $base = $this->baseNamespace . $this->namespaceConfig;

        if ($this->isNotEmpty($this->subdirectory)) {
            return $base . '\\' . $this->subdirectory;
        }

        return $base;
    }

    /**
     * Extracting the model name & subdirectory values.
     *
     * @param String $input
     */
    public function extractValuesFromInput(String $input)
    {
        $exploded = explode('/', $input);

        if (count($exploded) == 1) {
            $this->modelName = $exploded[0];
        }

        $this->modelName = array_pop($exploded);
        $temp = array_pop($exploded);
        $this->subdirectory = $this->isValidSubdirectory($temp) ? $temp : null;
    }

    /**
     * Checks the validity of the subdirectory value, to avoid using 'Models'
     * as a subdirectory or similar words.
     *
     * @param String|null $subdirectory
     * @return bool
     */
    public function isValidSubdirectory(?String $subdirectory)
    {
        $unwanted = ['Models', 'models', 'model', 'App', 'app'];
        foreach ($unwanted as $value) {
            if ($subdirectory === $value) {
                return false;
            }
        }
        return true;
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
     * Return the generated full path to the file that will be created.
     *
     * @return String
     */
    public function getFileFullPath()
    {
        return $this->path;
    }

    /**
     * Return the generated full path to the directory
     * in which the file will be created.
     *
     * @return String
     */
    public function getDirectoryFullPath()
    {
        return $this->directory;
    }

    /**
     * Return the value of the subdirectory name.
     *
     * @return String
     */
    public function getSubdirectory()
    {
        return $this->subdirectory;
    }

    /**
     * Gets the value of path extracted from the config file.
     *
     * @return String
     */
    public function getPathConfig()
    {
        return $this->pathConfig;
    }

    /**
     * Gets the value of namespace extracted from the config file.
     *
     * @return String
     */
    public function getNamespaceConfig()
    {
        return $this->namespaceConfig;
    }

    public function getBaseNamespace()
    {
        return $this->baseNamespace;
    }

    public function getClassNameSuffix()
    {
        return $this->classNameSuffix;
    }

    public function getClassName()
    {
        return $this->className;
    }

    public function getReplacements()
    {
        return $this->replacements;
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
