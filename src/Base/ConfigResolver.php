<?php

namespace Inz\Base;

use Inz\Base\Creators\ContractCreator;
use Inz\Base\Creators\RepositoryCreator;
use Inz\Exceptions\InvalidConfigurationValueException;
use Inz\Exceptions\MissingConfigurationValueException;

class ConfigResolver
{
    public static $configName = 'repository';

    /**
     * Retrieve the base path from configuration file.
     *
     * @return string
     */
    public static function basePath()
    {
        $value = config(self::$configName.'.base.path');
        if (self::validateConfigValue($value, 'base path')) {
            return $value;
        }
    }

    /**
     * Retrieve the base namespace from configuration file.
     *
     * @return string
     */
    public static function baseNamespace()
    {
        $value = config(self::$configName.'.base.namespace');
        if (self::validateConfigValue($value, 'base namespace')) {
            return $value;
        }
    }

    /**
     * Retrieve the namespace value for the given class category from configuration file.
     *
     * @param string $class
     *
     * @return string
     */
    public static function namespaceFor(string $class)
    {
        $key = self::getKeyName($class);
        $value = config(self::$configName.'.namespaces.'.$key);
        if (self::validateConfigValue($value, "{$key} namespace")) {
            return $value;
        }
    }

    /**
     * Retrieve the path value for the given class category from configuration file.
     *
     * @param string $class
     *
     * @return string
     */
    public static function pathFor(string $class)
    {
        $key = self::getKeyName($class);
        $value = config(self::$configName.'.paths.'.$key);
        if (self::validateConfigValue($value, "{$key} path")) {
            return $value;
        }
    }

    /**
     * Determines the key name of the configuration array based on the given class.
     *
     * @param string $class
     *
     * @return string
     */
    private static function getKeyName(string $class)
    {
        if ($class === ContractCreator::class) {
            return 'contracts';
        } elseif ($class === RepositoryCreator::class) {
            return 'implementations';
        } else {
            return 'criterions';
        }
    }

    /**
     * Checks if the given config value is valid or not.
     *
     * @param string $value
     * @param string $whatsWrong
     *
     * @throw MissingConfigurationValueException|InvalidConfigurationValueException
     *
     * @return bool
     */
    private static function validateConfigValue($value, $whatsWrong)
    {
        // $value being null means that it is absent from configuration file
        if (is_null($value)) {
            throw new MissingConfigurationValueException($whatsWrong);
        }

        // $value being an empty string means that it not set correctly in configuration file
        if ($value == '') {
            throw new InvalidConfigurationValueException($whatsWrong);
        }

        return true;
    }
}
