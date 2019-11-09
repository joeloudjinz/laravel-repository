<?php
namespace Inz\Repository\Test\Feature;

use Inz\Repository\Test\TestCase;
use Inz\Base\ConfigurationResolver;
use Inz\Exceptions\MissingConfigurationValueException;

class MissingConfigurationExceptionTest extends TestCase
{
    private $command = 'make:repository';
    private $model = 'Post';

    /**
     * testing exception is thrown when base path value is missing form config file.
     *
     * @test
     * @group exceptions_thrown_test
     */
    public function test_missing_base_path_value_in_config()
    {
        config()->set(ConfigurationResolver::$configName . '.base.path', null);

        $this->expectException(MissingConfigurationValueException::class);
        $this->expectExceptionMessage("base path is missing in configuration file.");

        $this->artisan($this->command, ['model' => $this->model])->execute();
    }

    /**
     * testing exception is thrown when base namespace value is missing form config file.
     *
     * @test
     * @group exceptions_thrown_test
     */
    public function test_missing_base_namespace_value_in_config()
    {
        config()->set(ConfigurationResolver::$configName . '.base.namespace', null);

        $this->expectException(MissingConfigurationValueException::class);
        $this->expectExceptionMessage("base namespace is missing in configuration file.");

        $this->artisan($this->command, ['model' => $this->model])->execute();
    }

    /**
     * testing exception is thrown when contracts namespace value is missing form config file.
     *
     * @test
     * @group exceptions_thrown_test
     */
    public function test_missing_contracts_value_in_namespaces_in_config()
    {
        config()->set(ConfigurationResolver::$configName . '.namespaces.contracts', null);

        $this->expectException(MissingConfigurationValueException::class);
        $this->expectExceptionMessage("contracts namespace is missing in configuration file.");

        $this->artisan($this->command, ['model' => $this->model])->execute();
    }

    /**
     * testing exception is thrown when implementations namespace value is missing form config file.
     *
     * @test
     * @group exceptions_thrown_test
     */
    public function test_missing_implementations_value_in_namespaces_in_config()
    {
        config()->set(ConfigurationResolver::$configName . '.namespaces.implementations', null);

        $this->expectException(MissingConfigurationValueException::class);
        $this->expectExceptionMessage("implementations namespace is missing in configuration file.");

        $this->artisan($this->command, ['model' => $this->model])->execute();
    }

    /**
     * testing exception is thrown when contracts path value is missing form config file.
     *
     * @test
     * @group exceptions_thrown_test
     */
    public function test_missing_contracts_value_in_paths_in_config()
    {
        config()->set(ConfigurationResolver::$configName . '.paths.contracts', null);

        $this->expectException(MissingConfigurationValueException::class);
        $this->expectExceptionMessage("contracts path is missing in configuration file.");

        $this->artisan($this->command, ['model' => $this->model])->execute();
    }

    /**
     * testing exception is thrown when implementations path value is missing form config file.
     *
     * @test
     * @group exceptions_thrown_test
     */
    public function test_missing_implementations_value_in_paths_in_config()
    {
        config()->set(ConfigurationResolver::$configName . '.paths.implementations', null);

        $this->expectException(MissingConfigurationValueException::class);
        $this->expectExceptionMessage("implementations path is missing in configuration file.");

        $this->artisan($this->command, ['model' => $this->model])->execute();
    }
}
