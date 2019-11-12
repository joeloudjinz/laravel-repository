<?php
namespace Inz\Repository\Test\Feature;

use Inz\Repository\Test\TestCase;
use Inz\Base\Creators\ContractCreator;
use Inz\Base\Creators\RepositoryCreator;

class MakeRepositoryCommandTest extends TestCase
{
    private $command = 'make:repository';
    private $model = 'Post';

    /**
     * testing the case where the model name is empty or not specified.
     *
     * @test
     * @group make_repository_command_test
     */
    public function test_command_with_invalid_model()
    {
        $this->artisan($this->command, ['model' => ''])
            ->expectsOutput('Model argument is missing');
    }

    /**
     * testing the case where the model does not exist & the developer doesn't want to create it.
     *
     * @test
     * @group make_repository_command_test
     */
    public function test_command_with_model_and_negative_answer()
    {
        $this->artisan($this->command, ['model' => $this->model])
            ->expectsQuestion("Model [{$this->model}] does not exist. Would you like to create it?", 'no')
            ->expectsOutput("Model wasn't created, aborting command.")
            ->assertExitCode(0);
    }

    /**
     * testing the case where the model does not exist & the developer want to create it.
     *
     * @test
     * @group make_repository_command_test
     */
    public function test_command_with_model_and_positive_answer()
    {
        $this->artisan($this->command, ['model' => $this->model])
            ->expectsQuestion("Model [{$this->model}] does not exist. Would you like to create it?", 'yes')
            ->expectsOutput("Model {$this->model} created successfully")
            ->assertExitCode(0);
    }

    /**
     * testing the case where the contract file does exist & the developer doesn't want to create it.
     *
     * @test
     * @group make_repository_command_test
     */
    public function test_command_where_contract_file_exist_and_answer_negative()
    {
        (new ContractCreator($this->model))->create();

        $this->artisan($this->command, ['model' => $this->model])
            ->expectsQuestion("Model [{$this->model}] does not exist. Would you like to create it?", 'yes')
            ->expectsOutput("Model {$this->model} created successfully")
            ->expectsQuestion("Contract file already exists. Do you want to overwrite it?", 'no')
            ->expectsOutput("Contract wasn't created")
            ->assertExitCode(0);
    }

    /**
     * testing the case where the contract file does exist & the developer doesn't want to create it.
     *
     * @test
     * @group make_repository_command_test
     */
    public function test_command_where_contract_file_exist_and_answer_positive()
    {
        $creator = new ContractCreator($this->model);
        $creator->create();

        $this->artisan($this->command, ['model' => $this->model])
            ->expectsQuestion("Model [{$this->model}] does not exist. Would you like to create it?", 'yes')
            ->expectsOutput("Model {$this->model} created successfully")
            ->expectsQuestion("Contract file already exists. Do you want to overwrite it?", 'yes')
            ->expectsOutput("{$creator->getClassName()} created successfully")
            ->assertExitCode(0);
    }

    /**
     * testing the case where the implementation file does exist & the developer doesn't want to create it.
     *
     * @test
     * @group make_repository_command_test
     */
    public function test_command_where_implementation_file_exist_and_answer_negative()
    {
        (new RepositoryCreator($this->model))->create();

        $this->artisan($this->command, ['model' => $this->model])
            ->expectsQuestion("Model [{$this->model}] does not exist. Would you like to create it?", 'yes')
            ->expectsOutput("Model {$this->model} created successfully")
            ->expectsQuestion("Implementations file already exists. Do you want to overwrite it?", 'no')
            ->expectsOutput("Implementation class wasn't created")
            ->assertExitCode(0);
    }

    /**
     * testing the case where the implementation file does exist & the developer want to create it.
     *
     * @test
     * @group make_repository_command_test
     */
    public function test_command_where_implementation_file_exist_and_answer_positive()
    {
        $creator = new RepositoryCreator($this->model);
        $creator->create();

        $this->artisan($this->command, ['model' => $this->model])
            ->expectsQuestion("Model [{$this->model}] does not exist. Would you like to create it?", 'yes')
            ->expectsOutput("Model {$this->model} created successfully")
            ->expectsQuestion("Implementations file already exists. Do you want to overwrite it?", 'yes')
            ->expectsOutput("{$creator->getClassName()} created successfully")
            ->assertExitCode(0);
    }
}
