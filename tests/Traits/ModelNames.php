<?php

namespace Inz\Repository\Test\Traits;

trait ModelNames
{
    /**
     * Simple model name without subdirectory.
     *
     * @var string
     */
    private $modelName = 'Post';

    /**
     * Model name with subdirectory.
     *
     * @var string
     */
    private $modelWithSubDirectory = 'Blog/Post';

    /**
     * Model name with subdirectory in models directory.
     *
     * @var string
     */
    private $modelWithSubDirectoryInModels = 'Models/Blog/Post';

    /**
     * Full model name with subdirectory contained in models
     * directory in application folder.
     *
     * @var string
     */
    private $fullModelName = 'App/Models/Blog/Post';

    /**
     * Name of the subdirectory.
     *
     * @var string
     */
    private $subDirectoryName = 'Blog';
}
