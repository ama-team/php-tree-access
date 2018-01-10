<?php

namespace AmaTeam\TreeAccess\API;

interface ActiveNodeInterface extends NodeInterface
{
    /**
     * @param mixed $value
     * @return ActiveNodeInterface
     */
    public function setValue($value);

    /**
     * @return ActiveNodeInterface[]
     */
    public function enumerate();
}
