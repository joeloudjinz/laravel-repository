<?php

namespace Inz\Repository\Test;

use Inz\RepositoryServiceProvider;
use Inz\Base\ConfigurationResolver;
use Inz\Repository\Test\Traits\FakeStorageInitiator;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    use FakeStorageInitiator;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->prepareFakeStorage();

        config()->set(
            ConfigurationResolver::$configName . '.base.path',
            storage_path('framework/testing/disks/app')
        );
        config()->set(
            ConfigurationResolver::$configName . '.base.providers.path',
            storage_path('framework/testing/disks/app')
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            RepositoryServiceProvider::class,
        ];
    }
}
