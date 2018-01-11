<?php

namespace AmaTeam\TreeAccess\API;

use AmaTeam\TreeAccess\API\Exception\IllegalTargetException;
use AmaTeam\TreeAccess\API\Exception\MissingNodeException;

interface ActiveNodeInterface extends NodeInterface
{
    /**
     * @param mixed $value
     * @return ActiveNodeInterface
     */
    public function setValue(&$value);

    /**
     * @param string $key
     * @param mixed $value
     * @return ActiveNodeInterface
     *
     * @throws IllegalTargetException
     */
    public function setChild($key, &$value);

    /**
     * @param string $key
     * @return ActiveNodeInterface
     *
     * @throws MissingNodeException
     * @throws IllegalTargetException
     */
    public function getChild($key);

    /**
     * @return ActiveNodeInterface[]
     *
     * @throws IllegalTargetException
     */
    public function enumerate();
}
