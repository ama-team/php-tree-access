<?php

namespace AmaTeam\TreeAccess;

use AmaTeam\TreeAccess\API\AccessorInterface;
use AmaTeam\TreeAccess\API\ActiveNodeFactoryInterface;
use AmaTeam\TreeAccess\API\Exception\IllegalTargetException;
use AmaTeam\TreeAccess\API\NodeInterface;
use AmaTeam\TreeAccess\API\TypeAccessorInterface;
use AmaTeam\TreeAccess\Locator\Context;
use AmaTeam\TreeAccess\Type\Registry;

class Accessor implements AccessorInterface, ActiveNodeFactoryInterface
{
    /**
     * @var Locator
     */
    private $locator;
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
        $this->locator = new Locator($registry);
    }

    /**
     * @inheritDoc
     */
    public function getNode(&$root, $path)
    {
        return $this->traverse($root, $path, new Context(false, false));
    }

    /**
     * @inheritDoc
     */
    public function findNode(&$root, $path)
    {
        return $this->traverse($root, $path, new Context(true, true));
    }

    /**
     * @inheritDoc
     */
    public function exists($root, $path)
    {
        $normalizedPath = Paths::normalize($path);
        return $this->findNode($root, $normalizedPath) !== null;
    }

    /**
     * @inheritDoc
     */
    public function isReadable($root, $path)
    {
        return $this->getNode($root, $path)->isReadable();
    }

    /**
     * @inheritDoc
     */
    public function isWritable($root, $path)
    {
        return $this->getNode($root, $path)->isWritable();
    }

    /**
     * @inheritDoc
     */
    public function isEnumerable($root, $path)
    {
        return $this->getNode($root, $path)->isEnumerable();
    }

    /**
     * @param mixed $root
     * @param string|string[] $path
     * @param Context $context
     * @return NodeInterface|null
     */
    private function traverse(&$root, $path, Context $context)
    {
        $node = new Node([], $root);
        return $this->locator->locate($node, Paths::normalize($path), $context);
    }

    /**
     * @inheritDoc
     */
    public function read(&$root, $path)
    {
        $path = Paths::normalize($path);
        $node = $this->getNode($root, $path);
        return $node->getValue();
    }

    /**
     * @inheritDoc
     */
    public function enumerate(&$root, $path)
    {
        $normalizedPath = Paths::normalize($path);
        $parent = $this->getNode($root, $normalizedPath);
        $accessor = $this->getAccessor($parent->getValue(), $normalizedPath);
        $target = [];
        foreach ($accessor->enumerate($parent->getValue()) as $name => $node) {
            $nodePath = array_merge($normalizedPath, $node->getPath());
            $target[$name] = Node::withPath($node, $nodePath);
        }
        return $target;
    }

    /**
     * @inheritDoc
     */
    public function write(&$root, $path, $value)
    {
        $normalizedPath = Paths::normalize($path);
        if (empty($normalizedPath)) {
            $root = $value;
            return new Node([], $root);
        }
        $key = $normalizedPath[sizeof($normalizedPath) - 1];
        $parentPath = array_slice($normalizedPath, 0, -1);
        $parent = $this->getNode($root, $parentPath);
        $accessor = $this->getAccessor($parent->getValue(), $parentPath);
        $parentValue = &$parent->getValue();
        $node = $accessor->write($parentValue, $key, $value);
        return Node::withPath($node, $normalizedPath);
    }

    /**
     * @param $value
     * @param array $path
     *
     * @return TypeAccessorInterface
     *
     * @throws IllegalTargetException
     */
    private function getAccessor($value, array $path)
    {
        $accessor = $this->registry->getAccessor(gettype($value));
        if (!$accessor) {
            throw new IllegalTargetException($path);
        }
        return $accessor;
    }

    /**
     * @inheritDoc
     */
    public function wrap(&$structure)
    {
        return new ActiveNode($this, [], $structure);
    }
}
