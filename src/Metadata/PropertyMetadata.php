<?php

namespace AmaTeam\TreeAccess\Metadata;

use AmaTeam\TreeAccess\API\Metadata\PropertyMetadataInterface;

class PropertyMetadata implements PropertyMetadataInterface
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var bool
     */
    private $virtual;
    /**
     * @var string|null
     */
    private $setter;
    /**
     * @var string|null
     */
    private $getter;

    /**
     * @param string $name
     * @param bool $virtual
     * @param null|string $setter
     * @param null|string $getter
     */
    public function __construct(
        $name,
        $virtual = false,
        $setter = null,
        $getter = null
    ) {
        $this->name = $name;
        $this->virtual = $virtual;
        $this->setter = $setter;
        $this->getter = $getter;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isVirtual()
    {
        return $this->virtual;
    }

    /**
     * @return null|string
     */
    public function getSetter()
    {
        return $this->setter;
    }

    /**
     * @return null|string
     */
    public function getGetter()
    {
        return $this->getter;
    }
}
