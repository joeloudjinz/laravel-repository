<?php

namespace Inz\Repository\Test\Unit\Creators;

use Illuminate\Filesystem\Filesystem;
use Inz\Base\Creators\RepositoryCreator;
use Inz\Repository\Test\TestCase;
use Inz\Repository\Test\Traits\DifferentModelNames;
use Inz\Repository\Test\Traits\FakeStorageInitiator;

class RepositoryCreatorTest extends TestCase
{
    use FakeStorageInitiator, DifferentModelNames;

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

        $this->assertNotNull($creator->getFileManager());
        $this->assertInstanceOf(Filesystem::class, $creator->getFileManager());
        $this->assertNotNull($creator->getAppNamespace());

        $this->assertNotNull($creator->getStub());
        $this->assertEquals($this->attributesData['classNameSuffix'], $creator->getClassNameSuffix());
        $this->assertEquals($this->attributesData['pathConfig'], $creator->getPathConfig());
        $this->assertEquals($this->attributesData['namespaceConfig'], $creator->getNamespaceConfig());
    }

    /**
     * @test
     * @group repository_creator_test
     */
    public function test_class_attributes_initialized_where_model_in_subdirectory()
    {
        $creator = $this->createInstance($this->modelWithSubDirectory);

        $this->assertNotNull($creator->getFileManager());
        $this->assertInstanceOf(Filesystem::class, $creator->getFileManager());
        $this->assertNotNull($creator->getAppNamespace());

        $this->assertNotNull($creator->getStub());
        $this->assertNotNull($creator->getSubdirectory());
        $this->assertEquals($this->subDirectoryName, $creator->getSubdirectory());
        $this->assertEquals($this->attributesData['classNameSuffix'], $creator->getClassNameSuffix());
        $this->assertEquals($this->attributesData['pathConfig'], $creator->getPathConfig());
        $this->assertEquals($this->attributesData['namespaceConfig'] . '\\', $creator->getNamespaceConfig());
    }

    /**
     * @test
     * @group repository_creator_test
     */
    public function test_class_attributes_initialized_where_model_in_subdirectory_in_models()
    {
        $creator = $this->createInstance($this->modelWithSubDirectoryInModels);

        $this->assertNotNull($creator->getFileManager());
        $this->assertInstanceOf(Filesystem::class, $creator->getFileManager());
        $this->assertNotNull($creator->getAppNamespace());

        $this->assertNotNull($creator->getStub());
        $this->assertNotNull($creator->getSubdirectory());
        $this->assertEquals($this->subDirectoryName, $creator->getSubdirectory());
        $this->assertEquals($this->attributesData['classNameSuffix'], $creator->getClassNameSuffix());
        $this->assertEquals($this->attributesData['pathConfig'], $creator->getPathConfig());
        $this->assertEquals($this->attributesData['namespaceConfig'] . '\\', $creator->getNamespaceConfig());
    }

    /**
     * @test
     * @group repository_creator_test
     */
    public function test_class_attributes_initialized_with_full_model_name()
    {
        $creator = $this->createInstance($this->fullModelName);

        $this->assertNotNull($creator->getFileManager());
        $this->assertInstanceOf(Filesystem::class, $creator->getFileManager());
        $this->assertNotNull($creator->getAppNamespace());

        $this->assertNotNull($creator->getStub());
        $this->assertNotNull($creator->getSubdirectory());
        $this->assertEquals($this->subDirectoryName, $creator->getSubdirectory());
        $this->assertEquals($this->attributesData['classNameSuffix'], $creator->getClassNameSuffix());
        $this->assertEquals($this->attributesData['pathConfig'], $creator->getPathConfig());
        $this->assertEquals($this->attributesData['namespaceConfig'] . '\\', $creator->getNamespaceConfig());
    }

    /**
     * extractStubContent(): bool
     * @test
     * @group repository_creator_test
     * */
    public function test_extract_content_from_stub_file()
    {
        $creator = $this->createInstance($this->modelName);
        $result = $creator->extractStubContent($creator->getStub());

        $this->assertIsBool($result);
        $this->assertTrue($result);
        $this->assertNotNull($creator->getContent());
    }

    /**
     * replaceContentParts(): bool
     * @test
     * @group repository_creator_test
     * */
    public function test_replace_content_parts()
    {
        $creator = $this->createInstance($this->modelName);
        // extracting the content first
        $creator->extractStubContent($creator->getStub());
        // saving the old content
        $oldContent = $creator->getContent();
        // secondly, initializing replacements attribute
        $creator->initializeReplacementsParts('ContractNamespace', 'ContractName', 'Post');
        // finally, performing the replacement process
        $result = $creator->replaceContentParts($creator->getReplacements());

        $this->assertIsBool($result);
        $this->assertTrue($result);
        $this->assertNotNull($creator->getContent());
        $this->assertNotEquals($creator->getContent(), $oldContent);
    }

    /**
     * createClassName(modelName): String
     * @test
     * @group repository_creator_test
     * */
    public function test_create_class_name()
    {
        $creator = $this->createInstance($this->modelName);
        $result = $creator->createClassName($this->modelName);

        $this->assertNotNull($result);
        $this->assertIsString($result);
        $this->assertEquals($this->modelName . $creator->getClassNameSuffix(), $result);
    }

    /**
     * generateDirectoryFullPath(): String
     * @test
     * @group repository_creator_test
     * */
    public function test_generate_directory_full_path()
    {
        $path = $this->prepareFakeStorage();

        $creator = $this->createInstance($this->modelName);
        $result = $creator->generateDirectoryFullPath($path, $creator->getPathConfig());

        $this->assertNotNull($result);
        $this->assertIsString($result);
        $this->assertStringContainsString($path, $result);
        $this->assertStringContainsString($creator->getPathConfig(), $result);
        $this->assertStringContainsString(DIRECTORY_SEPARATOR, $result);
    }

    /**
     * generateFileFullPath(): String
     * @test
     * @group repository_creator_test
     * */
    public function test_generate_file_full_path()
    {
        $path = $this->prepareFakeStorage();
        $creator = $this->createInstance($this->modelName);
        $fileName = $this->modelName . $this->attributesData['classNameSuffix'];

        $result = $creator->generateFileFullPath($path, $fileName);

        $this->assertNotNull($result);
        $this->assertIsString($result);
        $this->assertStringContainsString($path, $result);
        $this->assertStringContainsString($fileName, $result);
        $this->assertStringContainsString(DIRECTORY_SEPARATOR, $result);
        $this->assertStringContainsString('.php', $result);

        $this->assertNotNull($creator->getFileFullPath());
        $this->assertIsString($creator->getFileFullPath());
    }

    /**
     * createDirectory(): bool
     * @test
     * @group repository_creator_test
     * */
    public function test_create_directory()
    {
        $creator = $this->createInstance($this->modelName);
        $fullPath = $this->prepareFakeStorage() . DIRECTORY_SEPARATOR . 'TestRepository';

        $result = $creator->createDirectory($fullPath);

        $this->assertNotNull($result);
        $this->assertIsBool($result);
        $this->assertTrue($result);
        $this->fakeStorage->assertExists('TestRepository');
    }

    /**
     * createFile(): int
     * @test
     * @group repository_creator_test
     * */
    public function test_create_file()
    {
        $path = $this->prepareFakeStorage();
        $creator = $this->createInstance($this->modelName);
        $fullPath = $path . DIRECTORY_SEPARATOR . 'TestRepository.php';

        $result = $creator->createFile($fullPath, 'This is a content');

        $this->assertNotNull($result);
        $this->assertIsInt($result);
        $this->fakeStorage->assertExists('TestRepository.php');
    }

    /**
     * getClassFullNamespace(): int
     * @test
     * @group repository_creator_test
     * */
    public function test_get_class_full_namespace()
    {
        $creator = $this->createInstance($this->modelName);

        $result = $creator->getClassFullNamespace($creator->baseNamespace(), $this->modelName);

        $this->assertNotNull($result);
        $this->assertIsString($result);
        $this->assertStringContainsString($creator->baseNamespace(), $result);
        $this->assertStringContainsString($creator->getNamespaceConfig(), $result);
        $this->assertStringContainsString($this->modelName, $result);
    }

    /**
     * create() where the path is specified
     * @test
     * @group repository_creator_test
     * */
    public function test_create_implementation_file_in_specific_path()
    {
        // preparing
        $this->prepareFakeStorage();
        $creator = $this->createInstance($this->modelName);

        // initializing replacements attribute
        $creator->initializeReplacementsParts('ContractNamespace', 'ContractName', 'Post');

        // execution
        $result = $creator->create();

        // assertions
        $this->assertNotNull($result, 'creation result is NULL');
        $this->assertIsBool($result, 'create() return value is NOT OF TYPE BOOLEAN');
        $this->assertTrue($result, 'create() return value is FALSE');
    }

    /**
     * create() where the path is specified with a subdirectory
     * @test
     * @group repository_creator_test
     * */
    public function test_create_implementation_file_in_specific_path_in_subdirectory()
    {
        // preparing
        $this->prepareFakeStorage();
        $creator = $this->createInstance('Blog/Post');

        // initializing replacements attribute
        $creator->initializeReplacementsParts('ContractNamespace', 'ContractName', 'Blog\Post');

        // execution
        $result = $creator->create();

        // assertions
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
        // preparing
        $this->prepareFakeStorage();
        $creator = $this->createInstance($this->modelName);
        $creator->initializeReplacementsParts('ContractNamespace', 'ContractName', 'Post');
        $result = $creator->create();

        // execution
        $result = $creator->complete();

        // assertions
        $this->assertNotNull($result, 'creation result is NULL');
        $this->assertIsBool($result, 'create() return value is NOT OF TYPE BOOLEAN');
        $this->assertTrue($result, 'create() return value is FALSE');
    }
}
