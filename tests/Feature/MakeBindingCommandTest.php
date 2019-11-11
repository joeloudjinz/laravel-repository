<?php
namespace Inz\Repository\Test\Feature;

use Inz\Repository\Test\TestCase;

class MakeBindingCommandTest extends TestCase
{
    private $command = 'bind:repository';
    private $model = 'Post';

    /**
     * testing the case where the model name is empty or not specified.
     *
     * @test
     * @group bind_repository_command_test
     */
    public function test_command_with_invalid_model()
    {
        $this->artisan($this->command, ['model' => ''])
            ->expectsOutput('Model name is missing');
    }
}
