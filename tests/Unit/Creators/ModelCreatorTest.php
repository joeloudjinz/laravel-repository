<?php

namespace Inz\Repository\Test\Unit\Creators;

use Inz\Repository\Base\ModelCreator;
use Orchestra\Testbench\TestCase;

class ContractCreatorTest extends TestCase
{
    private $modelName = 'Post';
    private $modelWithSubDirectory = 'Models/Post';

    /**
     * Initial values for the attributes of the ModelCreator class.
     *
     * @var array
     */
    private $attributesData = [
        'appNamespace' => 'App\\',
        'antiSlashedInput' => 'Models\Post',
        'modelName' => 'Post',
        'modelNamespace' => 'App\Models\Post',
    ];

    private function createInstance($model = null)
    {
        return new ModelCreator($model ?? $this->modelWithSubDirectory);
    }
}
