<?php

namespace AmaTeam\TreeAccess\API;

interface NodeInterface
{
    /**
     * @return string[]
     */
    public function getPath();

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
