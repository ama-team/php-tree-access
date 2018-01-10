<?php

namespace AmaTeam\TreeAccess\Test\Support\TreeAccess;

class BooleanGetterObject
{
    const PROPERTY = 'flagged';

    /**
     * @var bool
     */
    private $flagged;

    /**
     * @param bool $flagged
     */
    public function __construct($flagged)
    {
        $this->flagged = $flagged;
    }

    /**
     * @return bool
     */
    public function isFlagged()
    {
        return $this->flagged;
    }
}
