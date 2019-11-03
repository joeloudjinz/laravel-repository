<?php

namespace Inz\Repository\Test\Unit\Creators;

use Illuminate\Filesystem\Filesystem;
use Inz\Repository\Base\ContractCreator;
use Orchestra\Testbench\TestCase;

class ContractCreatorTest extends TestCase
{
    // attributes that should be tested when creating the object
    // $classNameAddition;
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
        'classNameAddition' => 'Interface',
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
        $this->assertEquals($this->attributesData['classNameAddition'], $creator->getClassNameAddition());
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
    // replaceContentParts(): bool
    // createClassName(modelName): String
    // generateDirectoryFullPath(): String
    // generateFileFullPath(): String
    // directoryExists(): bool
    // createDirectory(): bool
    // fileExists(): bool
    // createFile(): int
    // getClassFullNamespace(): String
}
