<?php

namespace AmaTeam\TreeAccess\Type;

use AmaTeam\TreeAccess\API\TypeAccessorInterface;

class Registry
{
    /**
     * @var TypeAccessorInterface[]
     */
    private $accessors = [];

    /**
     * @param string $type
     * @return TypeAccessorInterface|null
     */
    public function getAccessor($type)
    {
        if (isset($this->accessors[$type])) {
            return $this->accessors[$type];
        }
        return null;
    }

    /**
     * @param string $type
     * @param TypeAccessorInterface $accessor
     * @return $this
     */
    public function registerAccessor($type, TypeAccessorInterface $accessor)
    {
        $this->accessors[$type] = $accessor;
        return $this;
    }
}
