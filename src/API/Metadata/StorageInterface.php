<?php

namespace AmaTeam\TreeAccess\API\Metadata;

interface StorageInterface
{
    /**
     * @param string $className
     * @param PropertyMetadataInterface[] $metadata
     * @return void
     */
    public function set($className, array $metadata);

    /**
     * @param string $className
     * @return PropertyMetadataInterface[]|null
     */
    public function get($className);
}
