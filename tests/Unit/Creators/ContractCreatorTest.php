<?php

namespace Inz\Repository\Test\Unit\Creators;

use Illuminate\Filesystem\Filesystem;
use Inz\Repository\Base\ContractCreator;
use Orchestra\Testbench\TestCase;

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
        $this->assertEquals($this->attributesData['classNameSuffix'], $creator->getclassNameSuffix());
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
    // createClassName(modelName): String
    /**
     * extractStubContent(): bool
     * @test
     * */
    public function test_create_class_name()
    {
        $creator = $this->createInstance();
        $result = $creator->createClassName($this->modelName);
        $this->assertNotNull($result);
        $this->assertIsString($result);
        $this->assertEquals($this->modelName . $creator->getclassNameSuffix(), $result);
    }
    // generateDirectoryFullPath(): String
    // generateFileFullPath(): String
    // directoryExists(): bool
    // createDirectory(): bool
    // fileExists(): bool
    // createFile(): int
    // getClassFullNamespace(): String
}
