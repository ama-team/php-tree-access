<?php

namespace AmaTeam\TreeAccess\Metadata;

use AmaTeam\TreeAccess\API\Metadata\StorageInterface;

class RuntimeStorage implements StorageInterface
{
    /**
     * @var array
     */
    private $metadata = [];

    /**
     * @inheritDoc
     */
    public function set($className, array $metadata)
    {
        $this->metadata[$className] = $metadata;
    }

    /**
     * @inheritDoc
     */
    public function get($className)
    {
        $exists = isset($this->metadata[$className]);
        return $exists ? $this->metadata[$className] : null;
    }
}
