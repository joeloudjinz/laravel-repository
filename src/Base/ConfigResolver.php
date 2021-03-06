<?php

namespace Inz\Base;

use Inz\Base\Creators\ContractCreator;
use Inz\Base\Creators\RepositoryCreator;
use Inz\Exceptions\InvalidConfigurationValueException;
use Inz\Exceptions\MissingConfigurationValueException;

class ConfigurationResolver
{
    public static $configName = 'repository';

    /**
     * Retrieve the base path from configuration file
     *
     * @return String
     */
    public static function basePath()
    {
        $value = config(self::$configName . ".base.path");
        if (self::validateConfigValue($value, "base path")) {
            return $value;
        }
    }

    /**
     * Retrieve the base path of the providers classes from configuration file
     *
     * @return String
     */
    public static function basePathOfProviders()
    {
        $value = config(self::$configName . ".base.providers.path");
        if (is_null($value) || $value === '') {
            return app_path();
        }
        return $value;
    }

    /**
     * Retrieve the base namespace from configuration file
     *
     * @return String
     */
    public static function baseNamespace()
    {
        $value = config(self::$configName . ".base.namespace");
        if (self::validateConfigValue($value, "base namespace")) {
            return $value;
        }
    }

    /**
     * Retrieve the base namespace fo providers classes from configuration file
     *
     * @return String
     */
    public static function baseNamespaceOfProviders()
    {
        $value = config(self::$configName . ".base.providers.namespace");
        if (is_null($value) || $value === '') {
            return app()->getNamespace();
        }
        return $value;
    }

    /**
     * Retrieve the namespace value for the given class category from configuration file
     *
     * @param String $class
     * @return String
     */
    public static function namespaceFor(String $class)
    {
        $key = self::getKeyName($class);
        $value = config(self::$configName . ".namespaces." . $key);
        if (self::validateConfigValue($value, "{$key} namespace")) {
            return $value;
        }
    }

    /**
     * Retrieve the path value for the given class category from configuration file
     *
     * @param String $class
     * @return String
     */
    public static function pathFor(String $class)
    {
        $key = self::getKeyName($class);
        $value = config(self::$configName . ".paths." . $key);
        if (self::validateConfigValue($value, "{$key} path")) {
            return $value;
        }
    }

    /**
     * Determines the key name of the configuration array based on the given class.
     *
     * @param String $class
     * @return String
     */
    private static function getKeyName(String $class)
    {
        if ($class === ContractCreator::class) {
            return "contracts";
        } elseif ($class === RepositoryCreator::class) {
            return "implementations";
        } else {
            return "criterions";
        }
    }

    /**
     * Checks if the given config value is valid or not.
     *
     * @param String $value
     * @param String $whatsWrong
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
