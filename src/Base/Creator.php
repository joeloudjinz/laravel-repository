<?php

namespace Inz\Repository\Base;

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

    public function __construct()
    {
        $this->fileManager = app()->singleton(Filesystem::class);
        $this->appNamespace = app()->getNamespace();
    }

    abstract public function create();
    
    // contentGetter
    // contentReplacer
    // createClassName
    // createDirectoryFullPath
    // createFileFullPath
    // doesDirectoryExist
    // createDirectory
    // doesFileExist
    // createFile
    // getFullNamespace
}
