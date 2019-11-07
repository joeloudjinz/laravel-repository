<?php

namespace Inz\Repository\Test;

use Inz\RepositoryServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            RepositoryServiceProvider::class,
        ];
    }
}
