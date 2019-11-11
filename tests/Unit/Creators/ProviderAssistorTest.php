<?php

namespace Inz\Repository\Test\Unit\Creators;

use Inz\Repository\Test\TestCase;
use Inz\Base\Creators\ProviderAssistor;
use Inz\Repository\Test\Traits\FakeStorageInitiator;

class ProviderAssistorTest extends TestCase
{
    use FakeStorageInitiator;

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
        $this->prepareFakeStorage();

        $assistor = $this->createInstance();

        $this->assertEquals($this->fakeStoragePath . 'Providers' . DIRECTORY_SEPARATOR, $assistor->getBasePath());
        $this->assertEquals(app()->getNamespace() . 'Providers', $assistor->getBaseNamespace());
    }

    // public function providerExist(): bool
    // public function providerInitiator(): ServiceProvider
    // public function addRepositoryEntry($contract, $implementation): bool
    // public function replaceContent(): bool
    // public function getFullClassName()
    // public function getFullClassPath()
    // public function isRepositoryBound(String $contract): bool
}
