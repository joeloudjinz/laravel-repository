<?php

namespace Inz\Repository\Test\Unit\Creators;

use Orchestra\Testbench\TestCase;
use Illuminate\Filesystem\Filesystem;
use Inz\Repository\Base\ContractCreator;

class ContractCreatorTest extends TestCase
{
    // attributes that should be tested when creating the object
    // $classNameSuffix;
    // $stub;
    // $configType;
    // $pathConfig;
    // $namespaceConfig;

    // attributes that are not tested
    // $content;
    // $className;
    // $directory;
    // $replacements = [];
    // $path;
    // $subdirectory;

    // attributes that should not be tested
    // $fileManager;
    // $appNamespace;
    // $permissions = 0755;

    private $modelName = 'Post';

    /**
     * Initial values for the attributes of the ContractCreator class.
     *
     * @var array
     */
    private $attributesData = [
        'classNameSuffix' => 'RepositoryInterface',
        'configType' => 'contracts',
        'pathConfig' => 'Repositories/Contracts/',
        'namespaceConfig' => 'Repositories\Contracts',
    ];

    private function createInstance()
    {
        return new ContractCreator($this->modelName);
    }

    /** @test */
    public function contract_creator_attributes_initialized()
    {
        $creator = $this->createInstance();

        // dd($creator->getNamespaceConfig(), $creator->getPathConfig());

        $this->assertNotNull($creator->getFileManager());
        $this->assertInstanceOf(Filesystem::class, $creator->getFileManager());
        $this->assertNotNull($creator->getAppNamespace());

        // TODO: assert that stub in creator has the correct value
        $this->assertNotNull($creator->getStub());
        $this->assertEquals($this->attributesData['classNameSuffix'], $creator->getClassNameSuffix());
        $this->assertEquals($this->attributesData['configType'], $creator->getConfigType());
        $this->assertEquals($this->attributesData['pathConfig'], $creator->getPathConfig());
        $this->assertEquals($this->attributesData['namespaceConfig'], $creator->getNamespaceConfig());
    }

    // Methods that should be tested

    /**
     * extractStubContent(): bool
     * @test
     * */
    public function test_extract_content_from_stub_file()
    {
        $creator = $this->createInstance();
        $result = $creator->extractStubContent();
        $this->assertIsBool($result);
        $this->assertTrue($result);
        $this->assertNotNull($creator->getContent());
    }

    // getContent(): String

    /**
     * replaceContentParts(): bool
     * @test
     * */
    public function test_replace_content_parts()
    {
        $creator = $this->createInstance();
        // extracting the content first
        $creator->extractStubContent();
        // saving the old content
        $oldContent = $creator->getContent();
        // performing the replacement process second
        $result = $creator->replaceContentParts();
        $this->assertIsBool($result);
        $this->assertTrue($result);
        $this->assertNotNull($creator->getContent());
        // asserting that the parts are replaced
        $this->assertNotEquals($creator->getContent(), $oldContent);
    }

    /**
     * createClassName(modelName): String
     * @test
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
     * */
    public function test_generate_directory_full_path()
    {
        $creator = $this->createInstance();
        $result = $creator->generateDirectoryFullPath();

        $this->assertNotNull($result);
        $this->assertIsString($result);
        $this->assertStringContainsString(app()->basePath(), $result);
        $this->assertStringContainsString($creator->getPathConfig(), $result);
    }

    /**
     * generateFileFullPath(): String
     * @test
     * */
    public function test_generate_file_full_path()
    {
        $creator = $this->createInstance();
        // create directory path & class name first
        $creator->generateDirectoryFullPath();
        $creator->createClassName($this->modelName);

        $result = $creator->generateFileFullPath();

        $this->assertNotNull($result);
        $this->assertIsString($result);
        $this->assertStringContainsString(app()->basePath(), $result);
        $this->assertStringContainsString($creator->getPathConfig(), $result);
        $this->assertStringContainsString($creator->getDirectory(), $result);
        $this->assertStringContainsString($creator->getClassName(), $result);
        $this->assertStringContainsString('.php', $result);
    }

    /**
     * createDirectory(): bool
     * @test
     * */
    // public function test_create_directory()
    // {
    //     $creator = $this->createInstance();
    //     $creator->generateDirectoryFullPath();

    //     $result = $creator->createDirectory();

    //     $this->assertNotNull($result);
    //     $this->assertIsBool($result);
    //     $this->assertTrue($result);
    // }

    // createFile(): int
    /**
     * createFile(): int
     * @test
     * */
    // public function test_create_file()
    // {
    //     $creator = $this->createInstance();
    //     $creator->generateDirectoryFullPath();
    //     $creator->generateFileFullPath();
    //     $creator->createDirectory();
        
    //     $result = $creator->createFile();
    //     dd($result);
    //     $this->assertNotNull($result);
    //     $this->assertIsInt($result);
    //     // $this->assertTrue($result);
    // }
    // getClassFullNamespace(): String

    // fileExists(): bool
    // directoryExists(): bool
}
