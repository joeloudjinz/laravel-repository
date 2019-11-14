<?php

namespace Inz\Repository\Test\Unit\Creators;

use Inz\Repository\Test\TestCase;
use Inz\Base\Creators\RepositoryCreator;
use Inz\Repository\Test\Traits\DifferentModelNames;

class RepositoryCreatorTest extends TestCase
{
    use DifferentModelNames;

    /**
     * Initial values for the attributes of the ContractCreator class.
     *
     * @var array
     */
    private $attributesData = [
        'classNameSuffix' => 'Repository',
        'pathConfig' => 'Repositories/Implementations',
        'namespaceConfig' => 'Repositories\Implementations',
    ];

    private function createInstance($modelName)
    {
        return new RepositoryCreator($modelName);
    }

    /**
     * @test
     * @group repository_creator_test
     */
    public function test_class_attributes_initialized()
    {
        $creator = $this->createInstance($this->modelName);

        $this->assertEquals($this->attributesData['classNameSuffix'], $creator->getClassNameSuffix());
        $this->assertEquals($this->attributesData['pathConfig'], $creator->getPathConfig());
        $this->assertEquals($this->attributesData['namespaceConfig'], $creator->getNamespaceConfig());
        $this->assertNotNull($creator->getStub());
        $this->assertNotNull($creator->getContent());
        $this->assertNotNull($creator->getClassName());
        $this->assertNotNull($creator->getFileFullPath());
        $this->assertNotNull($creator->getBaseNamespace());
        $this->assertNotNull($creator->getDirectoryFullPath());
    }

    /**
     * @test
     * @group repository_creator_test
     */
    public function test_class_attributes_initialized_where_model_in_subdirectory()
    {
        $creator = $this->createInstance($this->modelWithSubDirectory);

        $this->assertNotNull($creator->getSubdirectory());
        $this->assertEquals($this->subDirectoryName, $creator->getSubdirectory());
        $this->assertEquals($this->attributesData['pathConfig'], $creator->getPathConfig());
        $this->assertEquals($this->attributesData['classNameSuffix'], $creator->getClassNameSuffix());
        $this->assertEquals($this->attributesData['namespaceConfig'], $creator->getNamespaceConfig());
        $this->assertNotNull($creator->getStub());
        $this->assertNotNull($creator->getContent());
        $this->assertNotNull($creator->getClassName());
        $this->assertNotNull($creator->getFileFullPath());
        $this->assertNotNull($creator->getBaseNamespace());
        $this->assertNotNull($creator->getDirectoryFullPath());
    }

    /**
     * @test
     * @group repository_creator_test
     */
    public function test_class_attributes_initialized_where_model_in_subdirectory_in_models()
    {
        $creator = $this->createInstance($this->modelWithSubDirectoryInModels);

        $this->assertNotNull($creator->getSubdirectory());
        $this->assertEquals($this->subDirectoryName, $creator->getSubdirectory());
        $this->assertEquals($this->attributesData['pathConfig'], $creator->getPathConfig());
        $this->assertEquals($this->attributesData['classNameSuffix'], $creator->getClassNameSuffix());
        $this->assertEquals($this->attributesData['namespaceConfig'], $creator->getNamespaceConfig());
        $this->assertNotNull($creator->getStub());
        $this->assertNotNull($creator->getContent());
        $this->assertNotNull($creator->getClassName());
        $this->assertNotNull($creator->getFileFullPath());
        $this->assertNotNull($creator->getBaseNamespace());
        $this->assertNotNull($creator->getDirectoryFullPath());
    }

    /**
     * @test
     * @group repository_creator_test
     */
    public function test_class_attributes_initialized_with_full_model_name()
    {
        $creator = $this->createInstance($this->fullModelName);

        $this->assertNotNull($creator->getSubdirectory());
        $this->assertEquals($this->subDirectoryName, $creator->getSubdirectory());
        $this->assertEquals($this->attributesData['pathConfig'], $creator->getPathConfig());
        $this->assertEquals($this->attributesData['classNameSuffix'], $creator->getClassNameSuffix());
        $this->assertEquals($this->attributesData['namespaceConfig'], $creator->getNamespaceConfig());
        $this->assertNotNull($creator->getStub());
        $this->assertNotNull($creator->getContent());
        $this->assertNotNull($creator->getClassName());
        $this->assertNotNull($creator->getFileFullPath());
        $this->assertNotNull($creator->getBaseNamespace());
        $this->assertNotNull($creator->getDirectoryFullPath());
    }

    /**
     * replaceContentParts(): bool
     * @test
     * @group repository_creator_test
     * */
    public function test_replace_content_parts()
    {
        $creator = $this->createInstance($this->modelName);
        $creator->initializeReplacementsParts('ContractNamespace', 'ContractName', 'Post');

        $result = $creator->replaceContentParts($creator->getReplacements());

        $this->assertIsBool($result);
        $this->assertTrue($result);
        $this->assertNotNull($creator->getContent());
    }

    /**
     * createDirectory(): bool
     * @test
     * @group repository_creator_test
     * */
    public function test_create_directory()
    {
        $creator = $this->createInstance($this->modelName);

        $result = $creator->createDirectory();

        $this->assertNotNull($result);
        $this->assertIsBool($result);
        $this->assertTrue($result);
        $this->fakeStorage->assertExists($creator->getPathConfig());
    }

    /**
     * createFile(): int
     * @test
     * @group repository_creator_test
     * */
    public function test_create_file()
    {
        $creator = $this->createInstance($this->modelName);
        $creator->createDirectory();

        $result = $creator->createFile();

        $this->assertNotNull($result);
        $this->assertIsInt($result);
        $fileName = $creator->getPathConfig() . DIRECTORY_SEPARATOR . $creator->getClassName() . '.php';
        $this->fakeStorage->assertExists($fileName);
    }

    /**
     * create() where the path is specified
     * @test
     * @group repository_creator_test
     * */
    public function test_create_implementation_file()
    {
        $creator = $this->createInstance($this->modelName);

        $result = $creator->create();

        $this->assertNotNull($result, 'creation result is NULL');
        $this->assertIsBool($result, 'create() return value is NOT OF TYPE BOOLEAN');
        $this->assertTrue($result, 'create() return value is FALSE');
    }

    /**
     * create() where the path is specified with a subdirectory
     * @test
     * @group repository_creator_test
     * */
    public function test_create_implementation_file_in_subdirectory()
    {
        $creator = $this->createInstance('Blog/Post');

        $result = $creator->create();

        $this->assertNotNull($result, 'creation result is NULL');
        $this->assertIsBool($result, 'create() return value is NOT OF TYPE BOOLEAN');
        $this->assertTrue($result, 'create() return value is FALSE');
    }

    /**
     * complete()
     * @test
     * @group repository_creator_test
     * */
    public function test_complete_implementation_file_creation()
    {
        $creator = $this->createInstance($this->modelName);
        $result = $creator->create();

        $result = $creator->complete();

        $this->assertNotNull($result, 'creation result is NULL');
        $this->assertIsBool($result, 'create() return value is NOT OF TYPE BOOLEAN');
        $this->assertTrue($result, 'create() return value is FALSE');
    }
}
