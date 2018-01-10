<?php

namespace AmaTeam\TreeAccess\API\Exception;

use AmaTeam\TreeAccess\Paths;
use Throwable;

class IllegalTargetException extends RuntimeException
{
    /**
     * @var string[]
     */
    private $path;

    /**
     * @param string[] $path
     * @param null $message
     * @param Throwable|null $previous
     */
    public function __construct(array $path, $message = null, Throwable $previous = null)
    {
        $this->path = $path;
        if (!$message) {
            $template = 'Can not access target at path `%s`';
            $message = sprintf($template, Paths::toString($path));
        }
        parent::__construct($message, $previous);
    }

    /**
     * @return string[]
     */
    public function getPath()
    {
        return $this->path;
    }
}
