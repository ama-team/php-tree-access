<?php

namespace AmaTeam\TreeAccess\API;

use AmaTeam\TreeAccess\API\Exception\IllegalTargetException;
use AmaTeam\TreeAccess\API\Exception\MissingNodeException;

interface TypeAccessorInterface
{
    /**
     * @param mixed $item
     * @param string $key
     *
     * @return NodeInterface
     *
     * @throws MissingNodeException
     * @throws IllegalTargetException
     */
    public function read(&$item, $key);

    /**
     * @param mixed $item
     * @param string|int $key
     * @param mixed $value
     *
     * @return NodeInterface
     *
     * @throws MissingNodeException
     * @throws IllegalTargetException
     */
    public function write(&$item, $key, $value);

    /**
     * @param $item
     * @param $key
     *
     * @return bool
     *
     * @throws IllegalTargetException
     */
    public function exists($item, $key);

    /**
     * @param mixed $item
     * @return NodeInterface[]
     *
     * @throws IllegalTargetException
     */
    public function enumerate($item);
}
