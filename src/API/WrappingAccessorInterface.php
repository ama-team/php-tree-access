<?php

namespace AmaTeam\TreeAccess\API;

interface WrappingAccessorInterface extends AccessorInterface
{
    /**
     * @param mixed $root
     * @return ActiveNodeInterface
     */
    public function wrap($root);
}
