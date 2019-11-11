<?php

namespace Inz\Repository\Test;

use Inz\Base\ConfigurationResolver;
use Inz\RepositoryServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
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
