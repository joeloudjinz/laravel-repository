<?php

namespace Inz\Repository\Test\Unit\Creators;

use Inz\Repository\Base\ModelCreator;
use Orchestra\Testbench\TestCase;

class ModelCreatorTest extends TestCase
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

    /**
     * @return ModelCreator
     */
    private function createInstance($model = null)
    {
        return new ModelCreator($model ?? $this->modelWithSubDirectory);
    }

    /** @test */
    public function test_model_creator_attributes_initialized()
    {
        $creator = $this->createInstance();

        $this->assertNotNull($creator->getModelName());
        $this->assertNotNull($creator->getModelFullNamespace());

        $this->assertEquals($this->attributesData['modelName'], $creator->getModelName());
        $this->assertEquals($this->attributesData['modelNamespace'], $creator->getModelFullNamespace());
    }
}
