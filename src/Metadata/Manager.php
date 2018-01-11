<?php

namespace AmaTeam\TreeAccess\Metadata;

use AmaTeam\TreeAccess\API\Metadata\ManagerInterface;
use AmaTeam\TreeAccess\API\Metadata\StorageInterface;
use AmaTeam\TreeAccess\Misc\Strings;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

class Manager implements ManagerInterface
{
    const GETTER_PREFIXES = ['get', 'is', 'has'];
    const SETTER_PREFIXES = ['set'];

    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage = null)
    {
        $this->storage = $storage ?: new RuntimeStorage();
    }

    /**
     * @inheritDoc
     */
    public function get($className)
    {
        $properties = $this->storage->get($className);
        if ($properties === null) {
            $properties = $this->analyze($className);
            $this->storage->set($className, $properties);
        }
        return $properties;
    }

    /**
     * @param string $className
     * @return PropertyMetadata[]
     */
    private function analyze($className)
    {
        $reflection = new ReflectionClass($className);
        return array_merge(
            $this->extractVirtualProperties($reflection),
            $this->extractProperties($reflection)
        );
    }

    /**
     * @param ReflectionClass $reflection
     * @return PropertyMetadata[]
     */
    private function extractProperties(ReflectionClass $reflection)
    {
        $properties = [];
        $filter = ReflectionProperty::IS_PUBLIC;
        foreach ($reflection->getProperties($filter) as $property) {
            $metadata = new PropertyMetadata($property->getName());
            $properties[$property->getName()] = $metadata;
        }
        return $properties;
    }

    /**
     * @param ReflectionClass $reflection
     * @return PropertyMetadata[]
     */
    private function extractVirtualProperties(ReflectionClass $reflection)
    {
        $properties = [];
        $filter = ReflectionMethod::IS_PUBLIC;
        $defaults = ['getter' => null, 'setter' => null];
        $candidates = [
            'setter' => self::SETTER_PREFIXES,
            'getter' => self::GETTER_PREFIXES
        ];
        foreach ($reflection->getMethods($filter) as $method) {
            $name = $method->getName();
            foreach ($candidates as $key => $prefixes) {
                foreach ($prefixes as $prefix) {
                    $property = Strings::stripCamelCasePrefix($name, $prefix);
                    if (!$property) {
                        continue;
                    }
                    $metadata = isset($properties[$property]) ? $properties[$property] : $defaults;
                    $metadata[$key] = $metadata[$key] ?: $name;
                    $properties[$property] = $metadata;
                }
            }
        }
        $target = [];
        foreach ($properties as $property => $metadata) {
            $target[$property] = new PropertyMetadata(
                $property,
                true,
                $metadata['setter'],
                $metadata['getter']
            );
        }
        return $target;
    }
}
