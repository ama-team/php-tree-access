<?php

namespace AmaTeam\TreeAccess\Test\Suite\Unit\Type;

use AmaTeam\TreeAccess\API\Exception\MissingNodeException;
use AmaTeam\TreeAccess\API\Exception\RuntimeException;
use AmaTeam\TreeAccess\API\NodeInterface;
use AmaTeam\TreeAccess\Type\ArrayAccessor;
use Codeception\Test\Unit;
use PHPUnit\Framework\Assert;
use stdClass;

class ArrayAccessorTest extends Unit
{
    const KEY = 'value';
    const VALUE = 12;
    const PAYLOAD = [self::KEY => self::VALUE];

    /**
     * @var ArrayAccessor
     */
    private $accessor;

    /**
     * @inheritDoc
     */
    protected function _before()
    {
        parent::_before();
        $this->accessor = new ArrayAccessor();
    }

    public function testSuccessfulRead()
    {
        $payload = self::PAYLOAD;
        $value = $this->accessor->read($payload, self::KEY);
        Assert::assertEquals(self::VALUE, $value->getValue());
        Assert::assertEquals([self::KEY], $value->getPath());
    }

    public function testUnsuccessfulRead()
    {
        $this->expectException(MissingNodeException::class);
        $payload = [];
        $this->accessor->read($payload, self::KEY);
    }

    public function testWrite()
    {
        $payload = [];
        $node = $this->accessor->write($payload, self::KEY, self::VALUE);
        Assert::assertEquals(self::PAYLOAD, $payload);
        Assert::assertEquals(self::VALUE, $node->getValue());
    }

    public function testOverwrite()
    {
        $payload = self::PAYLOAD;
        $value = 13;
        $expectation = [self::KEY => $value];
        $node = $this->accessor->write($payload, self::KEY, $value);
        Assert::assertEquals($expectation, $payload);
        Assert::assertEquals($value, $node->getValue());
    }

    public function testSuccessfulExists()
    {
        Assert::assertTrue($this->accessor->exists(self::PAYLOAD, self::KEY));
    }

    public function testUnsuccessfulExists()
    {
        Assert::assertFalse($this->accessor->exists([], self::KEY));
    }

    public function testEmptyEnumeration()
    {
        Assert::assertEquals([], $this->accessor->enumerate([]));
    }

    public function testEnumeration()
    {
        $source = [12, 13];
        /**
         * @var int $index
         * @var NodeInterface $node
         */
        foreach ($this->accessor->enumerate($source) as $index => $node) {
            Assert::assertEquals([$index], $node->getPath());
            Assert::assertEquals($source[$index], $node->getValue());
            Assert::assertTrue($node->isReadable());
            Assert::assertTrue($node->isWritable());
        }
    }

    /**
     * @test
     */
    public function throwsOnInvalidInput()
    {
        $this->expectException(RuntimeException::class);
        $this->accessor->exists(new stdClass(), self::KEY);
    }
}
