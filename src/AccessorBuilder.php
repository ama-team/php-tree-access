<?php

namespace AmaTeam\TreeAccess;

use AmaTeam\TreeAccess\API\Metadata\StorageInterface;
use AmaTeam\TreeAccess\Metadata\Manager;
use AmaTeam\TreeAccess\Metadata\RuntimeStorage;
use AmaTeam\TreeAccess\Type\ArrayAccessor;
use AmaTeam\TreeAccess\Type\ObjectAccessor;
use AmaTeam\TreeAccess\Type\Registry;

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
        $registry = (new Registry())
            ->registerAccessor('array', new ArrayAccessor())
            ->registerAccessor('object', new ObjectAccessor($manager));
        return new Accessor($registry);
    }
}
