<?php

namespace Inz\Repository\Test\Unit\Creators;

use Inz\Repository\Base\ContractCreator;
use Orchestra\Testbench\TestCase;

class ContractCreatorTest extends TestCase
{
    private $modelName = 'Post';
    /**
     * Initial values for the attributes of the ContractCreator class.
     *
     * @var array
     */
    private $attributesData = [
        'stub' => _DIR__ . '/Stubs/Contracts/ExampleRepository.stub',
        'classNameAddition' => 'Interface',
        'configType' => 'contracts',
        'pathConfig' => 'Repositories/Contracts/',
        'namespaceConfig' => 'Repositories\Contracts',
    ];
    // private $stub = _DIR__ . '/Stubs/Contracts/ExampleRepository.stub';
    // private $classNameAddition = 'Interface';
    // private $configType = 'contracts';
    // private $pathConfig = 'Repositories/Contracts/';
    // private $namespaceConfig = 'Repositories\Contracts';

    /** @test */
    public function creator_attributes_initialized()
    {
        // preparation
        $creator = new ContractCreator($this->modelName);
        // assertions
        // $this->assertEquals($this->attributesData[], $creator->);
    }
}
