<?php

namespace Inz\Repository\Test\Unit\Creators;

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

    /** @test */
    public function contract_creator_attributes_initialized()
    {
        // preparation
        $creator = new ContractCreator($this->modelName);

        // dd($creator->getNamespaceConfig(), $creator->getPathConfig());

        // assertions
        $this->assertNotNull($creator->getStub());
        $this->assertEquals($this->attributesData['classNameAddition'], $creator->getClassNameAddition());
        $this->assertEquals($this->attributesData['configType'], $creator->getConfigType());
        $this->assertEquals($this->attributesData['pathConfig'], $creator->getPathConfig());
        $this->assertEquals($this->attributesData['namespaceConfig'], $creator->getNamespaceConfig());
    }
}
