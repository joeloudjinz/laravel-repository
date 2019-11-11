<?php

namespace Inz\Repository\Test\Unit\Creators;

use Inz\Repository\Test\TestCase;
use Inz\Base\Creators\ProviderAssistor;

class ProviderAssistorTest extends TestCase
{
    private $providerName = 'RepositoryServiceProvider';

    private function createInstance()
    {
        return new ProviderAssistor($this->providerName);
    }

    /**
     * @group provider_assistor_test
     */
    public function test_class_attributes_initialized()
    {
        $assistor = $this->createInstance();

        $this->assertEquals($this->fakeStoragePath . 'Providers' . DIRECTORY_SEPARATOR, $assistor->getBasePath());
        $this->assertEquals(app()->getNamespace() . 'Providers', $assistor->getBaseNamespace());
    }

    /**
     * @group provider_assistor_test
     */
    public function test_get_full_class_name()
    {
        $assistor = $this->createInstance();

        $this->assertEquals(
            app()->getNamespace() . 'Providers\\' . $this->providerName,
            $assistor->getFullClassName()
        );
    }

    /**
     * @group provider_assistor_test
     */
    public function test_get_full_class_path()
    {
        $assistor = $this->createInstance();

        $this->assertEquals(
            $this->fakeStoragePath . 'Providers' . DIRECTORY_SEPARATOR . $this->providerName . '.php',
            $assistor->getFullClassPath()
        );
    }

    /**
     * @group provider_assistor_test
     */
    public function test_provider_exist()
    {
        $this->fakeStorage->put('Providers' . DIRECTORY_SEPARATOR . $this->providerName . '.php', 'content');

        $result = $this->createInstance()->providerExist();

        $this->assertNotNull($result);
        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    /**
     * @group provider_assistor_test1
     */
    public function test_add_repository_entry()
    {
        $this->fakeStorage->put(
            'Providers' . DIRECTORY_SEPARATOR . $this->providerName . '.php',
            'content'
        );
        $assistor = $this->createInstance();
        $assistor->replaceContent();
        $result = $assistor->addRepositoryEntry('Contract\PostContract', 'Repository\PostRepository');

        $this->assertNotNull($result);
        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    // public function addRepositoryEntry($contract, $implementation): bool
    // public function replaceContent(): bool
    // public function isRepositoryBound(String $contract): bool
}
