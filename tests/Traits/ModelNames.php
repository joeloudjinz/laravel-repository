<?php

namespace Inz\Repository\Test\Traits;

trait DifferentModelNames
{
    /**
     * Simple model name without subdirectory
     *
     * @var String
     */
    private $modelName = 'Post';

    /**
     * Model name with subdirectory
     *
     * @var String
     */
    private $modelWithSubDirectory = 'Blog/Post';

    /**
     * Model name with subdirectory in models directory
     *
     * @var String
     */
    private $modelWithSubDirectoryInModels = 'Models/Blog/Post';

    /**
     * Full model name with subdirectory contained in models
     * directory in application folder.
     *
     * @var String
     */
    private $fullModelName = 'App/Models/Blog/Post';

    /**
     * Name of the subdirectory.
     *
     * @var String
     */
    private $subDirectoryName = 'Blog';
}
