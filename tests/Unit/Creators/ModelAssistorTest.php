<?php

namespace Inz\Repository\Test\Unit\Creators;

use Inz\Repository\Test\TestCase;
use Inz\Base\Creators\ModelAssistor;
use Inz\Repository\Test\Traits\DifferentModelNames;

class ModelAssistorTest extends TestCase
{
    use DifferentModelNames;

    /**
     * Initial values for the attributes of the ModelAssistor class.
     *
     * @var array
     */
    private $attributesData = [
        'modelName' => 'Post',
        'namespaceOfModelName' => 'App\Post',
        'namespaceOfFullModelName' => 'App\Models\Blog\Post',
        'namespaceOfModelNameWithSubdirectory' => 'App\Blog\Post',
    ];

    /**
     * @return ModelAssistor
     */
    private function createInstance($modelName)
    {
        return new ModelAssistor($modelName);
    }

    /**
     * @test
     * @group model_creator_test
     **/
    public function test_class_attributes_initialized_using_model_in_base_app_directory()
    {
        $creator = $this->createInstance($this->modelName);

        $this->assertNotNull($creator->getModelName());
        $this->assertNotNull($creator->getModelFullNamespace());

        $this->assertEquals($this->attributesData['modelName'], $creator->getModelName());
        $this->assertEquals(
            $this->attributesData['namespaceOfModelName'],
            $creator->getModelFullNamespace()
        );
    }

    /**
     * @test
     * @group model_creator_test
     **/
    public function test_class_attributes_initialized_using_model_in_subdirectory()
    {
        $creator = $this->createInstance($this->modelWithSubDirectory);

        $this->assertNotNull($creator->getModelName());
        $this->assertNotNull($creator->getModelFullNamespace());

        $this->assertEquals($this->attributesData['modelName'], $creator->getModelName());
        $this->assertEquals(
            $this->attributesData['namespaceOfModelNameWithSubdirectory'],
            $creator->getModelFullNamespace()
        );
    }

    /**
     * @test
     * @group model_creator_test
     **/
    public function test_class_attributes_initialized_using_model_in_models_directory()
    {
        $creator = $this->createInstance($this->modelWithSubDirectoryInModels);

        $this->assertNotNull($creator->getModelName());
        $this->assertNotNull($creator->getModelFullNamespace());

        $this->assertEquals($this->attributesData['modelName'], $creator->getModelName());
        $this->assertEquals(
            $this->attributesData['namespaceOfFullModelName'],
            $creator->getModelFullNamespace()
        );
    }
}
