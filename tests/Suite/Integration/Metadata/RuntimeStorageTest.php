<?php

namespace AmaTeam\TreeAccess\Test\Suite\Integration\Metadata;

use AmaTeam\TreeAccess\Metadata\PropertyMetadata;
use AmaTeam\TreeAccess\Metadata\RuntimeStorage;
use Codeception\Test\Unit;
use PHPUnit\Framework\Assert;

class RuntimeStorageTest extends Unit
{
    /**
     * @var RuntimeStorage
     */
    private $storage;

    /**
     * @inheritDoc
     */
    protected function _before()
    {
        parent::_before();
        $this->storage = new RuntimeStorage();
    }

    public function testMissingGet()
    {
        Assert::assertNull($this->storage->get('Missing'));
    }

    public function testSetAndGet()
    {
        $payload = [new PropertyMetadata('name')];
        $className = 'Missing';
        $this->storage->set($className, $payload);
        Assert::assertEquals($payload, $this->storage->get($className));
    }
}
