<?php

namespace App\Exceptions;

use Exception;

class LoginException extends Exception
{
    protected $message = "";

    public function __construct($message)
    {
        $exception = $this->translateException($message);

        $this->message = $exception;
    }

    protected function translateException(String $message): String
    {        
        $key = strtolower($message);

        return  __('api_error.' . $key, []);
    }
}
