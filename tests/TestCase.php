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
        // setting up the fake storage
        $this->prepareFakeStorage();

        // setting up 'base path' in configuration file of the package so
        // it can generates files in that directory and not using 'app'
        // folder
        config()->set(
            ConfigurationResolver::$configName . '.base.path',
            storage_path('framework/testing/disks/app')
        );
        // setting up the 'providers base path' in configuration file of the
        // package so provider assistor class (in the package) can work
        // on that directory
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
