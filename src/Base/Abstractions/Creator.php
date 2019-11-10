<?php

namespace Inz\Base\Abstractions;

use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Inz\Base\ConfigurationResolver;

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
        $this->fileManager = app()->make(Filesystem::class);
        $this->baseNamespace = ConfigurationResolver::baseNamespace();
        $this->basePath = ConfigurationResolver::basePath();
        $this->namespaceConfig = ConfigurationResolver::namespaceFor(get_called_class());
        $this->pathConfig = ConfigurationResolver::pathFor(get_called_class());

        $values = $this->extractInputValues($input);
        $this->modelName = $values['modelName'];
        if (Arr::has($values, 'subdirectory')) {
            $this->subdirectory = $values['subdirectory'];
            $this->namespaceConfig .= '\\';
        }
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
        $base = $basePath . $directoryName . DIRECTORY_SEPARATOR;

        if ($this->isNotEmpty($this->subdirectory)) {
            return $this->directory = $base . $this->subdirectory . DIRECTORY_SEPARATOR;
        }

        return $this->directory = $base;
    }

    /**
     * Returns the base path of the directory in which the generated classes are stored.
     *
     * @return String
     */
    public function directoryBasePath()
    {
        if (!$this->isNotEmpty($this->pathConfig)) {
            // TODO: throw PathConfigValueIsMissing an exception instead of returning null
            return null;
        }

        $base = $this->basePath . DIRECTORY_SEPARATOR;
        // if the base path points directly to the application's root directory, append
        // 'app' word to the directory base path so it points to the app folder.
        if (app()->basePath() === $this->basePath) {
            return $base . 'app' . DIRECTORY_SEPARATOR;
        }

        return $base;
    }

    /**
     * Generates a full path value to the file that is generated.
     *
     * @return String
     */
    public function generateFileFullPath(String $directoryPath, String $fileName): String
    {
        if (!$this->isNotEmpty($directoryPath) && !$this->isNotEmpty($fileName)) {
            // TODO: throw BadFilNameException an exception instead of returning null
            return null;
        }

        return $this->path = $directoryPath . $fileName . '.php';
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
    public function createDirectory(String $path): bool
    {
        try {
            return $this->fileManager->makeDirectory($path, $this->permissions, true);
        } catch (Exception $e) {
            return false;
        }
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
    public function getClassFullNamespace(String $base, String $className): String
    {
        return $base . '\\' . $className;
    }

    /**
     * Returns the base namespace of the generated classes, subdirectory name is included.
     *
     * @return String
     */
    public function baseNamespace(): String
    {
        if (!$this->isNotEmpty($this->namespaceConfig)) {
            // TODO: throw NamespaceConfigValueIsMissing an exception instead of returning null
            return null;
        }

        $base = $this->baseNamespace . $this->namespaceConfig;

        if ($this->isNotEmpty($this->subdirectory)) {
            return $base . '\\' . $this->subdirectory;
        }

        return $base;
    }

    /**
     * Extracting the model name & subdirectory values, which is **only** the
     * first element, from exploding input value.
     *
     * @return array
     */
    public function extractInputValues(String $input): array
    {
        $exploded = explode('/', $input);
        if (count($exploded) == 1) {
            return [
                'modelName' => $exploded[0],
            ];
        }
        return [
            'modelName' => array_pop($exploded),
            'subdirectory' => $this->subdirectory = array_pop($exploded),
        ];
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
    public function getDirectory()
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

    public function getFileManager()
    {
        return $this->fileManager;
    }

    public function getAppNamespace()
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
