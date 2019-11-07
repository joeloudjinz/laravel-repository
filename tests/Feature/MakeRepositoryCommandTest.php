<?php
namespace Inz\Repository\Test\Feature;

use Inz\Repository\Test\TestCase;
use Inz\Repository\Test\Traits\FakeStorageInitiator;

class MakeRepositoryCommandTest extends TestCase
{
    use FakeStorageInitiator;

    private $command = 'make:repository';
    private $model = 'Post';
    private $fullModel = 'Blog/Post';
}
