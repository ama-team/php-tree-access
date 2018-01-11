<?php

namespace AmaTeam\TreeAccess;

use AmaTeam\TreeAccess\API\Exception\IllegalTargetException;
use AmaTeam\TreeAccess\API\Exception\MissingNodeException;
use AmaTeam\TreeAccess\API\NodeInterface;
use AmaTeam\TreeAccess\Locator\Context;
use AmaTeam\TreeAccess\Type\Registry;

class Locator
{
    /**
     * @var Registry
     */
    private $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param NodeInterface $root
     * @param array $path
     * @param Context $context
     *
     * @return NodeInterface|null
     *
     * @throws MissingNodeException
     * @throws IllegalTargetException
     */
    public function locate(NodeInterface $root, array $path, Context $context)
    {
        $currentPath = [];
        $cursor = $root;
        foreach ($path as $segment) {
            $currentPath[] = $segment;
            $value = &$cursor->getValue();
            $accessor = $this->registry->getAccessor(gettype($value));
            if (!$accessor) {
                if ($context->shouldIgnoreIllegal()) {
                    return null;
                }
                throw new IllegalTargetException($currentPath);
            }
            if (!$accessor || !$accessor->exists($value, $segment)) {
                if ($context->shouldIgnoreMissing()) {
                    return null;
                }
                throw new MissingNodeException($currentPath);
            }
            $cursor = $accessor->read($value, $segment);
        }
        return Node::withPath($cursor, $path);
    }
}
