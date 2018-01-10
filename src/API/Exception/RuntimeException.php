<?php

namespace AmaTeam\TreeAccess\API\Exception;

use AmaTeam\TreeAccess\API\ExceptionInterface;
use RuntimeException as SPLRuntimeException;
use Throwable;

class RuntimeException extends SPLRuntimeException implements
    ExceptionInterface
{
    public function __construct($message = '', Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
