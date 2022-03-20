<?php

namespace App\Exceptions;

use Exception;

class CannotGetFormActionException extends Exception
{
    public $message = 'Can not get form action!';
}
