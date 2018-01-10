<?php

namespace AmaTeam\TreeAccess;

use AmaTeam\TreeAccess\API\Metadata\StorageInterface;
use AmaTeam\TreeAccess\Metadata\Manager;
use AmaTeam\TreeAccess\Metadata\RuntimeStorage;

class AccessorBuilder
{
    /**
     * @var StorageInterface
     */
    private $metadataStorage;

    /**
     * @param StorageInterface $metadataStorage
     * @return $this
     */
    public function setMetadataStorage($metadataStorage)
    {
        $this->metadataStorage = $metadataStorage;
        return $this;
    }

    public function build()
    {
        $storage = $this->metadataStorage ?: new RuntimeStorage();
        $manager = new Manager($storage);
        return new Accessor($manager);
    }
}
