<?php

namespace AmaTeam\TreeAccess\Locator;

class Context
{
    /**
     * @var bool
     */
    private $ignoreIllegal = false;
    /**
     * @var bool
     */
    private $ignoreMissing = false;

    /**
     * @param bool $ignoreIllegal
     * @param bool $ignoreMissing
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function __construct($ignoreIllegal = false, $ignoreMissing = false)
    {
        $this->ignoreIllegal = $ignoreIllegal;
        $this->ignoreMissing = $ignoreMissing;
    }

    /**
     * @return bool
     */
    public function shouldIgnoreIllegal()
    {
        return $this->ignoreIllegal;
    }

    /**
     * @param bool $ignoreIllegal
     * @return $this
     */
    public function setIgnoreIllegal($ignoreIllegal)
    {
        $this->ignoreIllegal = $ignoreIllegal;
        return $this;
    }

    /**
     * @return bool
     */
    public function shouldIgnoreMissing()
    {
        return $this->ignoreMissing;
    }

    /**
     * @param bool $ignoreMissing
     * @return $this
     */
    public function setIgnoreMissing($ignoreMissing)
    {
        $this->ignoreMissing = $ignoreMissing;
        return $this;
    }
}
