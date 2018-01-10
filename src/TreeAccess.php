<?php

namespace AmaTeam\TreeAccess;

use AmaTeam\TreeAccess\API\AccessorInterface;

class TreeAccess
{
    /**
     * @return AccessorBuilder
     */
    public static function createAccessorBuilder()
    {
        return new AccessorBuilder();
    }

    /**
     * @return AccessorInterface
     */
    public static function createAccessor()
    {
        return static::createAccessorBuilder()->build();
    }
}
