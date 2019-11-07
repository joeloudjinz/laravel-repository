<?php

namespace Inz\Base;

class ConfigurationResolver
{
    protected static $configName = 'repository';

    /**
     * Retrieve the base path from configuration file
     *
     * @return String
     */
    public static function basePath()
    {
        return config(self::$configName . ".base.path");
    }

    /**
     * Retrieve the base namespace from configuration file
     *
     * @return String
     */
    public static function baseNamespace()
    {
        return config(self::$configName . ".base.namespace");
    }

    /**
     * Retrieve the namespace value for the given class category from configuration file
     *
     * @param String $class
     * @return String
     */
    public static function namespaceFor(String $class)
    {
        if ($class === ContractCreator::class) {
            return config(self::$configName . ".namespaces.contracts");
        } elseif ($class === RepositoryCreator::class) {
            return config(self::$configName . ".namespaces.implementations");
        } else {
            return config(self::$configName . ".namespaces.criteria");
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
        if ($class === ContractCreator::class) {
            return config(self::$configName . ".paths.contracts");
        } elseif ($class === RepositoryCreator::class) {
            return config(self::$configName . ".paths.implementations");
        } else {
            return config(self::$configName . ".paths.criteria");
        }
    }
}
