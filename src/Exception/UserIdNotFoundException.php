<?php

namespace App\Exception;

use Exception;

class UserIdNotFoundException extends Exception
{
    public $message = 'User was not found';
}