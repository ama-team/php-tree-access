<?php

namespace AmaTeam\TreeAccess\API;

interface NodeInterface
{
    /**
     * Node path relative to root. Equals to empty array for root.
     *
     * @return string[]
     */
    public function getPath();

    /**
     * Node name as a child of parent node.
     *
     * @return string|int|null
     */
    public function getKey();

    /**
     * @return mixed
     */
    public function &getValue();

    /**
     * @return bool
     */
    public function isReadable();

    /**
     * @return bool
     */
    public function isWritable();

    /**
     * @return bool
     */
    public function isEnumerable();
}
