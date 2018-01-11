<?php

namespace AmaTeam\TreeAccess;

use AmaTeam\TreeAccess\API\AccessorInterface;
use AmaTeam\TreeAccess\API\ActiveNodeInterface;

class ActiveNode extends Node implements ActiveNodeInterface
{
    /**
     * @var AccessorInterface
     */
    private $accessor;
    /**
     * @var ActiveNodeInterface|null
     */
    private $parent;

    /**
     * @param AccessorInterface $accessor
     * @param string[] $path
     * @param mixed $value
     * @param bool $readable
     * @param bool $writable
     * @param ActiveNodeInterface|null $parent
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function __construct(
        AccessorInterface $accessor,
        array $path,
        &$value,
        $readable = true,
        $writable = true,
        ActiveNodeInterface $parent = null
    ) {
        parent::__construct($path, $value, $readable, $writable);
        $this->accessor = $accessor;
        $this->parent = $parent;
    }

    /**
     * @inheritDoc
     */
    public function setValue(&$value)
    {
        if ($this->parent) {
            $target = &$this->parent->getValue();
            $this->accessor->write($target, [$this->getKey()], $value);
            return $this;
        }
        $this->accessor->write($this->getValue(), [], $value);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setChild($key, $value)
    {
        $this->accessor->write($this->getValue(), [$key], $value);
        $node = $this->accessor->getNode($this->getValue(), [$key]);
        return new ActiveNode(
            $this->accessor,
            array_merge($this->getPath(), [$key]),
            $node->getValue(),
            $node->isReadable(),
            $node->isWritable(),
            $this
        );
    }

    /**
     * @inheritDoc
     */
    public function getChild($key)
    {
        $node = $this->accessor->getNode($this->getValue(), [$key]);
        return new ActiveNode(
            $this->accessor,
            array_merge($this->getPath(), [$key]),
            $node->getValue(),
            $node->isReadable(),
            $node->isWritable(),
            $this
        );
    }

    /**
     * @inheritDoc
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @inheritDoc
     */
    public function enumerate()
    {
        $target = [];
        $children = $this->accessor->enumerate($this->getValue(), []);
        foreach ($children as $key => $child) {
            $target[$key] = new ActiveNode(
                $this->accessor,
                array_merge($this->getPath(), [$key]),
                $child->getValue(),
                $child->isReadable(),
                $child->isWritable(),
                $this
            );
        }
        return $target;
    }
}
