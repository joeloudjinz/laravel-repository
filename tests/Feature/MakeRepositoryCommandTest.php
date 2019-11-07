<?php
namespace Inz\Repository\Test\Feature;

use Inz\Repository\Test\Traits\FakeStorageInitiator;
use Orchestra\Testbench\TestCase;

class MakeRepositoryCommandTest extends TestCase
{
    use FakeStorageInitiator;

    private $command = 'make:repository';
    private $model = 'Post';
    private $fullModel = 'Blog/Post';
}
