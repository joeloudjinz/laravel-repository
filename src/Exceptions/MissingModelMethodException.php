<?php

namespace Inz\Exceptions;

use Exception;

class MissingModelMethodException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            "Method model() is missing from the class, make sure implementation class defines this method"
        );
    }
}
