<?php

namespace App\Exceptions\Cart;

use Exception;

class QuantityException extends Exception
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
