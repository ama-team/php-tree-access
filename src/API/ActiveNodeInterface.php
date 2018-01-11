<?php

namespace AmaTeam\TreeAccess\API;

use AmaTeam\TreeAccess\API\Exception\IllegalTargetException;
use AmaTeam\TreeAccess\API\Exception\MissingNodeException;

/**
 * Active node represents single node in processed tree and allows
 * in-place editing rather than by specifying absolute paths.
 */
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
    public function setChild($key, $value);

    /**
     * @param string $key
     * @return ActiveNodeInterface
     *
     * @throws MissingNodeException
     * @throws IllegalTargetException
     */
    public function getChild($key);

    /**
     * @return ActiveNodeInterface|null
     */
    public function getParent();

    /**
     * @return ActiveNodeInterface[]
     *
     * @throws IllegalTargetException
     */
    public function enumerate();
}
