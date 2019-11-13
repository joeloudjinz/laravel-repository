<?php

namespace Inz\Exceptions;

use Exception;

class TableHasNoColumnsException extends Exception
{
    public function __construct($table)
    {
        parent::__construct("The table {$table} has no list of columns.");
    }
}
