<?php

namespace AmaTeam\TreeAccess\API\Metadata;

interface ManagerInterface
{
    /**
     * @param string $className
     * @return PropertyMetadataInterface[]
     */
    public function get($className);
}
