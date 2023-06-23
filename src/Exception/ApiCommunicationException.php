<?php

namespace App\Exception;

use Exception;

class ApiCommunicationException extends Exception
{
    public function __construct(string $message = 'An error occurred during API communication.', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
