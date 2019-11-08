<?php

namespace Inz\Exceptions;

use Exception;

class InvalidConfigurationValueException extends Exception
{
    public function __construct($whatIsInvalid)
    {
        parent::__construct("{$whatIsInvalid} is not valid in configuration file.");
    }
}
