<?php

namespace Infra\Exceptions;

use Exception;
use Throwable;

class NotFoundException extends Exception
{
    public function __construct(string $message = 'Route Not Found', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
