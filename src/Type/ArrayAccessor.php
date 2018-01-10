<?php

namespace AmaTeam\TreeAccess\Type;

use AmaTeam\TreeAccess\API\Exception\MissingNodeException;
use AmaTeam\TreeAccess\API\Exception\RuntimeException;
use AmaTeam\TreeAccess\API\TypeAccessorInterface;
use AmaTeam\TreeAccess\Node;

class ArrayAccessor implements TypeAccessorInterface
{
    /**
     * @inheritDoc
     */
    public function read(&$item, $key)
    {
        $this->assertExists($item, $key);
        return new Node([$key], $item[$key]);
    }

    /**
     * @inheritDoc
     */
    public function write(&$item, $key, $value)
    {
        $this->assertArray($item);
        $item[$key] = $value;
        return new Node([$key], $value);
    }

    /**
     * @inheritDoc
     */
    public function exists($item, $key)
    {
        $this->assertArray($item);
        return array_key_exists($key, $item);
    }

    /**
     * @inheritDoc
     */
    public function enumerate($item)
    {
        $this->assertArray($item);
        $target = [];
        foreach ($item as $key => &$value) {
            $target[$key] = new Node([$key], $value);
        }
        return $target;
    }

    /**
     * @param array $item
     * @param string $key
     */
    private function assertExists($item, $key)
    {
        if (!$this->exists($item, $key)) {
            $template = 'Key `%s` doesn\'t exist in passed array';
            $message = sprintf($template, $key);
            throw new MissingNodeException([$key], $message);
        }
    }

    /**
     * @param array $item
     */
    private function assertArray($item)
    {
        if (!is_array($item)) {
            throw new RuntimeException('Non-array item passed');
        }
    }
}
