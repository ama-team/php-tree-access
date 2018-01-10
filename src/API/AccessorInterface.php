<?php

namespace AmaTeam\TreeAccess\API;

use AmaTeam\TreeAccess\API\Exception\IllegalTargetException;

interface AccessorInterface
{
    /**
     * Reads value at specified path.
     *
     * @param mixed $root
     * @param string|string[] $path
     * @return mixed
     *
     * @throws IllegalTargetException
     */
    public function read(&$root, $path);

    /**
     * Returns specified node children as [key => value] array.
     *
     * @param mixed $root
     * @param string|string[] $path
     * @return array
     *
     * @throws IllegalTargetException
     */
    public function enumerate(&$root, $path);

    /**
     * Writes value at specified target.
     *
     * @param mixed $root
     * @param string|string[] $path
     * @param mixed $value
     * @return void
     *
     * @throws IllegalTargetException
     */
    public function write(&$root, $path, $value);

    /**
     * Tells if specified node exists.
     *
     * @param mixed $root
     * @param string|string[] $path
     * @return bool
     */
    public function exists($root, $path);
}
