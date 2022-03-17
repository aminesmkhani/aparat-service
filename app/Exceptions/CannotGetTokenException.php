<?php

namespace App\Exceptions;

use Exception;

class CannotGetTokenException extends Exception
{
    public $message = 'Can not get token!';
}
