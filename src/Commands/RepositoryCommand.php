<?php

namespace Inz\Repository\Commands;

use Illuminate\Console\Command;

class RepositoryCommand extends Command
{
    /**
     * File manager.
     *
     * @var Illuminate\Filesystem\Filesystem
     */
    protected $configName = 'repository';

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

    public function __construct()
    {
        parent::__construct();

        // instantiate a new Illuminate\Filesystem\Filesystem instance throughout the container
        $this->fileManager = app('files');
        // get the application base namespace, ex: App\
        $this->appNamespace = app()->getNamespace();
    }



    /**
     * Gets a configuration value from the config file of the package.
     *
     * @param string $key
     *
     * @return String|null
     */
    protected function config(String $key)
    {
        return config($this->configName . '.' . $key);
    }
}
