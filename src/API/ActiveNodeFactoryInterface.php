<?php

namespace AmaTeam\TreeAccess\API;

interface ActiveNodeFactoryInterface
{
    /**
     * @param mixed $structure
     * @return ActiveNodeInterface
     */
    public function wrap(&$structure);
}
