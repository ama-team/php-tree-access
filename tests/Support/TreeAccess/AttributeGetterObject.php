<?php

namespace AmaTeam\TreeAccess\Test\Support\TreeAccess;

class AttributeGetterObject
{
    const PROPERTY = 'attribute';

    /**
     * @var bool
     */
    private $attribute;

    /**
     * @param bool $attribute
     */
    public function __construct($attribute)
    {
        $this->attribute = $attribute;
    }

    public function hasAttribute()
    {
        return $this->attribute;
    }
}
