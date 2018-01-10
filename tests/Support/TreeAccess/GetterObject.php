<?php

namespace AmaTeam\TreeAccess\Test\Support\TreeAccess;

class GetterObject
{
    const PROPERTY = 'value';

    private $value;

    /**
     * @param $value
     */
    public function __construct($value = null)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
