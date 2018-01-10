<?php

namespace AmaTeam\TreeAccess\Test\Support\TreeAccess;

class SetterObject
{
    const PROPERTY = 'value';
    private $value;

    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function retrieveValue()
    {
        return $this->value;
    }
}
