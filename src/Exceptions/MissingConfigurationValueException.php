<?php

namespace Inz\Exceptions;

use Exception;

class MissingConfigurationValueException extends Exception
{
    public function __construct($whatIsMissing)
    {
        parent::__construct("{$whatIsMissing} is missing in configuration file.");
    }
}
