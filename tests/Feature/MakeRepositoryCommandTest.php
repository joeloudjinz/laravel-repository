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

    /**
     * @test
     * @group make_repository_command_test
     */
    public function test_command_with_invalid_model()
    {
        $this->artisan($this->command, ['model' => ''])
            ->expectsOutput('Model name is missing');
    }
}
