<?php

namespace AmaTeam\TreeAccess\API;

interface NodeInterface
{
    /**
     * @return string[]
     */
    public function getPath();

    /**
     * @return string|null
     */
    public function getKey();

    /**
     * Returns null if is not readable
     *
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
}
