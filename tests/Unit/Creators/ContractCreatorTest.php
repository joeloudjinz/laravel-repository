<?php

namespace Inz\Repository\Test\Unit\Creators;

use Inz\Repository\Test\TestCase;
use Inz\Base\Creators\ContractCreator;
use Inz\Repository\Test\Traits\DifferentModelNames;

class ContractCreatorTest extends TestCase
{
    use DifferentModelNames;

    /**
     * Initial values for the attributes of the ContractCreator class.
     *
     * @var array
     */
    private $attributesData = [
        'classNameSuffix' => 'RepositoryInterface',
        'pathConfig' => 'Repositories/Contracts',
        'namespaceConfig' => 'Repositories\Contracts',
    ];

    private function createInstance($modelName)
    {
        return new ContractCreator($modelName);
    }

    /**
     * @test
     * @group contract_creator_test
     * */
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
     * @group contract_creator_test
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
     * @group contract_creator_test
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
     * @group contract_creator_test
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
     * @group contract_creator_test
     * */
    public function test_replace_content_parts()
    {
        $creator = $this->createInstance($this->modelName);

        $result = $creator->replaceContentParts($creator->getReplacements());

        $this->assertIsBool($result);
        $this->assertTrue($result);
        $this->assertNotNull($creator->getContent());
    }

    /**
     * createDirectory(): bool
     * @test
     * @group contract_creator_test
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
     * @group contract_creator_test1
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
     * create()
     * @test
     * @group contract_creator_test
     * */
    public function test_create_contract_file()
    {
        $creator = $this->createInstance($this->modelName);

        $result = $creator->create();

        $this->assertNotNull($result, 'creation result is NULL');
        $this->assertIsArray($result, 'create() return value is NOT OF TYPE ARRAY');
        $this->assertCount(2, $result);
    }

    /**
     * create() with a subdirectory
     * @test
     * @group contract_creator_test
     * */
    public function test_create_contract_file_in_subdirectory()
    {
        $creator = $this->createInstance($this->modelWithSubDirectory);

        $result = $creator->create();

        $this->assertNotNull($result, 'creation result is NULL');
        $this->assertIsArray($result, 'create() return value is NOT OF TYPE ARRAY');
        $this->assertCount(2, $result);
    }

    /**
     * complete()
     * @test
     * @group contract_creator_test
     * */
    public function test_complete_contract_file_creation()
    {
        $creator = $this->createInstance($this->modelName);

        $result = $creator->create();
        $result = $creator->complete();

        $this->assertNotNull($result, 'creation result is NULL');
        $this->assertIsArray($result, 'create() return value is NOT OF TYPE ARRAY');
        $this->assertCount(2, $result);
    }
}
