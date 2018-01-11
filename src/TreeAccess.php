<?php

namespace AmaTeam\TreeAccess;

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
     * @return Accessor
     */
    public static function createAccessor()
    {
        return static::createAccessorBuilder()->build();
    }
}
