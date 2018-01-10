<?php

namespace AmaTeam\TreeAccess;

/**
 * TODO: support for complex paths that may include dots in segments
 */
class Paths
{
    const DELIMITER = '.';

    /**
     * @param string[] $path
     * @return string
     */
    public static function toString(array $path)
    {
        return implode(self::DELIMITER, $path);
    }

    /**
     * @param string $input
     * @return string[]
     */
    public static function fromString($input)
    {
        return array_filter(explode(self::DELIMITER, $input));
    }

    /**
     * @param string|string[] $path
     * @return string[]
     */
    public static function normalize($path)
    {
        return is_array($path) ? $path : self::fromString($path);
    }
}
