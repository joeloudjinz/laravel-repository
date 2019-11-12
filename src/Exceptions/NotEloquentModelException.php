<?php

namespace Inz\Exceptions;

use Exception;

class NotEloquentModelException extends Exception
{
    public function __construct($model)
    {
        parent::__construct("{$model} is not an instance of Illuminate\Database\Eloquent\Model");
    }
}
