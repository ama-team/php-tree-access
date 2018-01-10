<?php

namespace AmaTeam\TreeAccess;

use AmaTeam\TreeAccess\API\AccessorInterface;
use AmaTeam\TreeAccess\API\Exception\IllegalTargetException;
use AmaTeam\TreeAccess\API\Exception\MissingNodeException;
use AmaTeam\TreeAccess\API\Metadata\ManagerInterface;
use AmaTeam\TreeAccess\API\NodeInterface;
use AmaTeam\TreeAccess\API\TypeAccessorInterface;
use AmaTeam\TreeAccess\Type\ArrayAccessor;
use AmaTeam\TreeAccess\Type\ObjectAccessor;

class Accessor implements AccessorInterface
{
    /**
     * @var ObjectAccessor
     */
    private $objectAccessor;
    /**
     * @var ArrayAccessor
     */
    private $arrayAccessor;

    /**
     * @param ManagerInterface $metadataManager
     */
    public function __construct(ManagerInterface $metadataManager)
    {
        $this->objectAccessor = new ObjectAccessor($metadataManager);
        $this->arrayAccessor = new ArrayAccessor();
    }

    public function read(&$root, $path)
    {
        $path = Paths::normalize($path);
        $node = $this->getNode($root, $path);
        return $node->getValue();
    }

    public function enumerateNodes(&$root, $path)
    {
        $normalizedPath = Paths::normalize($path);
        $parent = $this->getNode($root, $normalizedPath);
        $accessor = $this->getAccessor($parent->getValue(), $normalizedPath);
        $target = [];
        foreach ($accessor->enumerate($parent->getValue()) as $name => $node) {
            $target[$name] = new Node(
                array_merge($normalizedPath, $node->getPath()),
                $node->getValue(),
                $node->isReadable(),
                $node->isWritable()
            );
        }
        return $target;
    }

    public function enumerate(&$root, $path)
    {
        $target = [];
        foreach ($this->enumerateNodes($root, $path) as $name => $node) {
            $target[$name] = $node->getValue();
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
            return;
        }
        $key = $normalizedPath[sizeof($normalizedPath) - 1];
        $parentPath = array_slice($normalizedPath, 0, -1);
        $parent = $this->getNode($root, $parentPath);
        $accessor = $this->getAccessor($parent->getValue(), $parentPath);
        $parentValue = &$parent->getValue();
        $accessor->write($parentValue, $key, $value);
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
     * @param mixed $root
     * @param string|string[] $path
     * @return NodeInterface
     */
    public function getNode(&$root, $path)
    {
        return $this->traverse(new Node([], $root), $path);
    }

    /**
     * @param NodeInterface $root
     * @param string[] $path
     * @return NodeInterface|null
     */
    public function findNode(&$root, array $path)
    {
        return $this->find(new Node([], $root), $path);
    }

    private function traverse(NodeInterface $root, array $path)
    {
        $node = $this->find($root, $path);
        if ($node === null) {
            throw new MissingNodeException($path);
        }
        return $node;
    }

    private function find(NodeInterface $root, array $path)
    {
        $cursor = $root;
        $walked = [];
        foreach ($path as $segment) {
            $walked[] = $segment;
            $value = &$cursor->getValue();
            $accessor = $this->findAccessor($value);
            if (!$accessor || !$accessor->exists($value, $segment)) {
                return null;
            }
            $cursor = $accessor->read($value, $segment);
        }
        return new Node(
            array_merge($root->getPath(), $path),
            $cursor->getValue(),
            $cursor->isReadable(),
            $cursor->isWritable()
        );
    }

    /**
     * @param mixed $value
     * @param string[] $path
     *
     * @return TypeAccessorInterface
     */
    private function getAccessor($value, $path)
    {
        $accessor = $this->findAccessor($value);
        if ($accessor) {
            return $accessor;
        }
        throw new IllegalTargetException($path);
    }

    /**
     * @param mixed $value
     * @return TypeAccessorInterface|null
     */
    private function findAccessor($value)
    {
        if (is_object($value)) {
            return $this->objectAccessor;
        }
        return is_array($value) ? $this->arrayAccessor : null;
    }
}
