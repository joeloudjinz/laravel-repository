<?php

namespace Inz\Repository\Test\Unit\Creators;

use Inz\Base\RepositoryCreator;
use Orchestra\Testbench\TestCase;
use Illuminate\Filesystem\Filesystem;
use Inz\Repository\Test\Traits\FakeStorageInitiator;

class RepositoryCreatorTest extends TestCase
{
    use FakeStorageInitiator;

    private $modelName = 'Post';

    /**
     * Initial values for the attributes of the ContractCreator class.
     *
     * @var array
     */
    private $attributesData = [
        'classNameSuffix' => 'Repository',
        'configType' => 'implementations',
        'pathConfig' => 'Repositories/Implementations',
        'namespaceConfig' => 'Repositories\Implementations',
    ];

    private function createInstance($modelName = null, $appBasePath = null)
    {
        return new RepositoryCreator($modelName ?? $this->modelName, $appBasePath);
    }

    /**
     * @test
     * @group repository_creator_test
     */
    public function test_repository_creator_attributes_initialized()
    {
        $creator = $this->createInstance();

        $this->assertNotNull($creator->getFileManager());
        $this->assertInstanceOf(Filesystem::class, $creator->getFileManager());
        $this->assertNotNull($creator->getAppNamespace());

        $this->assertNotNull($creator->getStub());
        $this->assertEquals($this->attributesData['classNameSuffix'], $creator->getClassNameSuffix());
        $this->assertEquals($this->attributesData['configType'], $creator->getConfigType());
        $this->assertEquals($this->attributesData['pathConfig'], $creator->getPathConfig());
        $this->assertEquals($this->attributesData['namespaceConfig'], $creator->getNamespaceConfig());
    }

    /**
     * @test
     * @group repository_creator_test
     */
    public function test_repository_creator_attributes_initialized_where_model_in_subdirectory()
    {
        $creator = $this->createInstance('Models/Post');

        $this->assertNotNull($creator->getFileManager());
        $this->assertInstanceOf(Filesystem::class, $creator->getFileManager());
        $this->assertNotNull($creator->getAppNamespace());

        $this->assertNotNull($creator->getStub());
        $this->assertNotNull($creator->getSubdirectory());
        $this->assertEquals('Models', $creator->getSubdirectory());
        $this->assertEquals($this->attributesData['classNameSuffix'], $creator->getClassNameSuffix());
        $this->assertEquals($this->attributesData['configType'], $creator->getConfigType());
        $this->assertEquals($this->attributesData['pathConfig'], $creator->getPathConfig());
        $this->assertEquals($this->attributesData['namespaceConfig'], $creator->getNamespaceConfig());
    }

    /**
     * extractStubContent(): bool
     * @test
     * @group repository_creator_test
     * */
    public function test_extract_content_from_stub_file()
    {
        $creator = $this->createInstance();
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
        $creator = $this->createInstance();
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
        $creator = $this->createInstance();
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

        $creator = $this->createInstance();
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
        $creator = $this->createInstance();
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
        $creator = $this->createInstance();
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
        $creator = $this->createInstance();
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
        $creator = $this->createInstance();

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
        $path = $this->prepareFakeStorage();
        $creator = $this->createInstance($this->modelName, $path);

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
        $path = $this->prepareFakeStorage();
        $creator = $this->createInstance('Blog/Post', $path);

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
        $path = $this->prepareFakeStorage();
        $creator = $this->createInstance($this->modelName, $path);
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
