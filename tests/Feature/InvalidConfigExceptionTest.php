<?php

namespace Inz\Repository\Test\Feature;

use Inz\Base\ConfigurationResolver;
use Inz\Exceptions\InvalidConfigurationValueException;
use Inz\Repository\Test\TestCase;

class InvalidConfigExceptionTest extends TestCase
{
    private $command = 'make:repository';
    private $model = 'Post';

    /**
     * testing exception is thrown when base path value is invalid form config file.
     *
     * @test
     * @group exceptions_thrown_test
     */
    public function test_invalid_base_path_value_in_config()
    {
        config()->set(ConfigurationResolver::$configName.'.base.path', '');

        $this->expectException(InvalidConfigurationValueException::class);
        $this->expectExceptionMessage('base path is not valid in configuration file.');

        $this->artisan($this->command, ['model' => $this->model])->execute();
    }

    /**
     * testing exception is thrown when base namespace value is invalid form config file.
     *
     * @test
     * @group exceptions_thrown_test
     */
    public function test_invalid_base_namespace_value_in_config()
    {
        config()->set(ConfigurationResolver::$configName.'.base.namespace', '');

        $this->expectException(InvalidConfigurationValueException::class);
        $this->expectExceptionMessage('base namespace is not valid in configuration file.');

        $this->artisan($this->command, ['model' => $this->model])->execute();
    }

    /**
     * testing exception is thrown when contracts namespace value is invalid form config file.
     *
     * @test
     * @group exceptions_thrown_test
     */
    public function test_invalid_contracts_value_in_namespaces_in_config()
    {
        config()->set(ConfigurationResolver::$configName.'.namespaces.contracts', '');

        $this->expectException(InvalidConfigurationValueException::class);
        $this->expectExceptionMessage('contracts namespace is not valid in configuration file.');

        $this->artisan($this->command, ['model' => $this->model])->execute();
    }

    /**
     * testing exception is thrown when implementations namespace value is invalid form config file.
     *
     * @test
     * @group exceptions_thrown_test
     */
    public function test_invalid_implementations_value_in_namespaces_in_config()
    {
        config()->set(ConfigurationResolver::$configName.'.namespaces.implementations', '');

        $this->expectException(InvalidConfigurationValueException::class);
        $this->expectExceptionMessage('implementations namespace is not valid in configuration file.');

        $this->artisan($this->command, ['model' => $this->model])->execute();
    }

    /**
     * testing exception is thrown when contracts path value is invalid form config file.
     *
     * @test
     * @group exceptions_thrown_test
     */
    public function test_invalid_contracts_value_in_paths_in_config()
    {
        config()->set(ConfigurationResolver::$configName.'.paths.contracts', '');

        $this->expectException(InvalidConfigurationValueException::class);
        $this->expectExceptionMessage('contracts path is not valid in configuration file.');

        $this->artisan($this->command, ['model' => $this->model])->execute();
    }

    /**
     * testing exception is thrown when implementations path value is invalid form config file.
     *
     * @test
     * @group exceptions_thrown_test
     */
    public function test_invalid_implementations_value_in_paths_in_config()
    {
        config()->set(ConfigurationResolver::$configName.'.paths.implementations', '');

        $this->expectException(InvalidConfigurationValueException::class);
        $this->expectExceptionMessage('implementations path is not valid in configuration file.');

        $this->artisan($this->command, ['model' => $this->model])->execute();
    }
}
