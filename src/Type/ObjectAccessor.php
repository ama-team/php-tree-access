<?php

namespace AmaTeam\TreeAccess\Type;

use AmaTeam\TreeAccess\API\Exception\IllegalTargetException;
use AmaTeam\TreeAccess\API\Exception\RuntimeException;
use AmaTeam\TreeAccess\API\TypeAccessorInterface;
use AmaTeam\TreeAccess\API\Exception\MissingNodeException;
use AmaTeam\TreeAccess\API\Metadata\ManagerInterface;
use AmaTeam\TreeAccess\API\NodeInterface;
use AmaTeam\TreeAccess\Metadata\Manager;
use AmaTeam\TreeAccess\Node;
use stdClass;

/**
 * TODO: internals are actually a mess
 * TODO: magic methods support
 */
class ObjectAccessor implements TypeAccessorInterface
{
    /**
     * @var ManagerInterface
     */
    private $metadataManger;

    /**
     * @param ManagerInterface $metadataManger
     */
    public function __construct(ManagerInterface $metadataManger = null)
    {
        $this->metadataManger = $metadataManger ?: new Manager();
    }

    /**
     * @param object $object
     * @param string $property
     * @return NodeInterface
     */
    public function read(&$object, $property)
    {
        $this->assertExists($object, $property);
        $metadata = $this->findPropertyMetadata($object, $property);
        $standard = $object instanceof stdClass;
        $native = $metadata && !$metadata->isVirtual();
        if ($standard || $native) {
            $value = $object->$property;
        } else if ($metadata && $metadata->getGetter() !== null) {
            $getter = $metadata->getGetter();
            $value = $getter ? call_user_func([$object, $getter]) : null;
        } else {
            $template = 'Can\'t access property `%s` on target of class `%s`';
            $message = sprintf($template, $property, get_class($object));
            throw new IllegalTargetException([$property], $message);
        }
        $readable = $metadata === null || $native || $metadata->getGetter() !== null;
        $writable = $metadata === null || $native || $metadata->getSetter() !== null;
        return new Node([$property], $value, $readable, $writable);
    }

    /**
     * @param object $object
     * @param string $property
     * @param mixed $value
     * @return NodeInterface
     */
    public function write(&$object, $property, $value)
    {
        $this->assertObject($object);
        $metadata = $this->findPropertyMetadata($object, $property);
        $standard = $object instanceof stdClass;
        $native = $metadata && !$metadata->isVirtual();
        if ($standard || $native) {
            $object->$property = $value;
            return new Node([$property], $value);
        } else if ($metadata && $metadata->getSetter() !== null) {
            call_user_func([$object, $metadata->getSetter()], $value);
            return new Node([$property], $value);
        } else {
            $template = 'Can\'t access property `%s` on target of class `%s`';
            $message = sprintf($template, $property, get_class($object));
            throw new IllegalTargetException([$property], $message);
        }
    }

    /**
     * @inheritDoc
     */
    public function exists($object, $property)
    {
        $this->assertObject($object);
        if ($this->findPropertyMetadata($object, $property) !== null) {
            return true;
        }
        return isset($object->$property);
    }

    /**
     * @inheritDoc
     */
    public function enumerate($item)
    {
        $this->assertObject($item);
        $properties = $this->metadataManger->get(get_class($item));
        $target = [];
        foreach (array_keys($properties) as $name) {
            $target[$name] = $this->read($item, $name);
        }
        return $target;
    }

    private function findPropertyMetadata($object, $property)
    {
        $properties = $this->metadataManger->get(get_class($object));
        return isset($properties[$property]) ? $properties[$property] : null;
    }

    private function assertObject($item)
    {
        if (!is_object($item)) {
            $template = 'Passed `%s` is not an object';
            $message = sprintf($template, gettype($item));
            throw new RuntimeException($message);
        }
    }

    private function assertExists($item, $property)
    {
        if (!$this->exists($item, $property)) {
            $template = 'Passed object doesn\'t have property `%s`';
            $message = sprintf($template, $property);
            throw new MissingNodeException([$property], $message);
        }
    }
}
