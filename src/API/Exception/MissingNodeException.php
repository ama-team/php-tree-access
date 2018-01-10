<?php

namespace AmaTeam\TreeAccess\API\Exception;

use AmaTeam\TreeAccess\Paths;
use Throwable;

class MissingNodeException extends IllegalTargetException
{
    /**
     * @param string[] $path
     * @param null $message
     * @param Throwable|null $previous
     */
    public function __construct(array $path, $message = null, Throwable $previous = null)
    {
        if (!$message) {
            $template = 'Node at requested path `%s` doesn\'t exist';
            $message = sprintf($template, Paths::toString($path));
        }
        parent::__construct($path, $message, $previous);
    }
}
