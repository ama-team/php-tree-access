<?php

namespace AmaTeam\TreeAccess\Test\Suite\Integration\Type;

use AmaTeam\TreeAccess\API\Exception\IllegalTargetException;
use AmaTeam\TreeAccess\API\Exception\MissingNodeException;
use AmaTeam\TreeAccess\API\Exception\RuntimeException;
use AmaTeam\TreeAccess\API\NodeInterface;
use AmaTeam\TreeAccess\Test\Support\TreeAccess\AttributeGetterObject;
use AmaTeam\TreeAccess\Test\Support\TreeAccess\BooleanGetterObject;
use AmaTeam\TreeAccess\Test\Support\TreeAccess\GetterObject;
use AmaTeam\TreeAccess\Test\Support\TreeAccess\PublicPropertiesObject;
use AmaTeam\TreeAccess\Test\Support\TreeAccess\SetterObject;
use AmaTeam\TreeAccess\Type\ObjectAccessor;
use Codeception\Test\Unit;
use PHPUnit\Framework\Assert;
use stdClass;

class ObjectAccessorTest extends Unit
{
    const KEY = 'property';
    const VALUE = 12;

    /**
     * @var object
     */
    private $payload;
    /**
     * @var ObjectAccessor
     */
    private $accessor;

    /**
     * @inheritDoc
     */
    protected function _before()
    {
        $this->payload = new stdClass();
        $this->payload->{self::KEY} = self::VALUE;
        $this->accessor = new ObjectAccessor();
    }

    public function testSuccessfulRead()
    {
        $node = $this->accessor->read($this->payload, self::KEY);
        Assert::assertEquals(self::VALUE, $node->getValue());
        Assert::assertEquals([self::KEY], $node->getPath());
        Assert::assertTrue($node->isReadable());
        Assert::assertTrue($node->isWritable());
    }

    public function testUnsuccessfulRead()
    {
        $this->expectException(MissingNodeException::class);
        $payload = new stdClass();
        $this->accessor->read($payload, self::KEY);
    }

    public function testPublicPropertyRead()
    {
        $payload = new PublicPropertiesObject();
        $property = 'alpha';
        $node = $this->accessor->read($payload, $property);
        Assert::assertEquals($payload->$property, $node->getValue());
        Assert::assertEquals([$property], $node->getPath());
        Assert::assertTrue($node->isReadable());
        Assert::assertTrue($node->isWritable());
    }

    public function testGetterRead()
    {
        $value = self::VALUE;
        $payload = new GetterObject($value);
        $node = $this->accessor->read($payload, GetterObject::PROPERTY);
        Assert::assertEquals($value, $node->getValue());
        Assert::assertEquals([GetterObject::PROPERTY], $node->getPath());
        Assert::assertTrue($node->isReadable());
        Assert::assertFalse($node->isWritable());
    }

    public function testBooleanGetterRead()
    {
        $value = true;
        $payload = new BooleanGetterObject($value);
        $node = $this->accessor->read($payload, BooleanGetterObject::PROPERTY);
        Assert::assertEquals($value, $node->getValue());
        Assert::assertEquals([BooleanGetterObject::PROPERTY], $node->getPath());
        Assert::assertTrue($node->isReadable());
        Assert::assertFalse($node->isWritable());
    }

    public function testAttributeGetterRead()
    {
        $value = true;
        $payload = new AttributeGetterObject($value);
        $node = $this->accessor->read($payload, AttributeGetterObject::PROPERTY);
        Assert::assertEquals($value, $node->getValue());
        Assert::assertEquals([AttributeGetterObject::PROPERTY], $node->getPath());
        Assert::assertTrue($node->isReadable());
        Assert::assertFalse($node->isWritable());
    }

    public function testWrite()
    {
        $payload = new stdClass();
        $node = $this->accessor->write($payload, self::KEY, self::VALUE);
        Assert::assertEquals(self::VALUE, $node->getValue());
        Assert::assertEquals([self::KEY], $node->getPath());
        Assert::assertTrue($node->isReadable());
        Assert::assertTrue($node->isWritable());
    }

    public function testOverwrite()
    {
        $value = 13;
        $node = $this->accessor->write($this->payload, self::KEY, $value);
        Assert::assertEquals($value, $node->getValue());
        Assert::assertEquals([self::KEY], $node->getPath());
        Assert::assertTrue($node->isReadable());
        Assert::assertTrue($node->isWritable());
    }

    public function testSetterWrite()
    {
        $value = self::VALUE;
        $payload = new SetterObject();
        $node = $this->accessor->write($payload, SetterObject::PROPERTY, $value);
        Assert::assertEquals($value, $node->getValue());
        Assert::assertEquals([SetterObject::PROPERTY], $node->getPath());
        Assert::assertTrue($node->isReadable());
        Assert::assertTrue($node->isWritable());
        Assert::assertEquals($value, $payload->retrieveValue());
    }

    public function testSetterRead()
    {
        $this->expectException(IllegalTargetException::class);
        $payload = new SetterObject();
        $this->accessor->read($payload, SetterObject::PROPERTY);
    }

    public function testGetterWrite()
    {
        $this->expectException(IllegalTargetException::class);
        $payload = new GetterObject();
        $this->accessor->write($payload, GetterObject::PROPERTY, self::VALUE);
    }

    public function testStdClassEnumeration()
    {
        $payload = new stdClass();
        $values = ['alpha' => 12, 'beta' => 13];
        foreach ($values as $name => $value) {
            $payload->$name = $value;
        }
        /** @var NodeInterface[] $children */
        $children = $this->accessor->enumerate($payload);
        Assert::assertEquals(2, sizeof($children));
        /**
         * @var string $name
         * @var NodeInterface $node
         */
        foreach ($children as $name => $node) {
            Assert::assertEquals([$name], $node->getPath());
            Assert::assertEquals($values[$name], $node->getValue());
            Assert::assertTrue($node->isReadable());
            Assert::assertTrue($node->isWritable());
        }
    }

    public function testPublicPropertiesEnumeration()
    {
        $payload = new PublicPropertiesObject();
        $values = get_object_vars($payload);
        /**
         * @var string $name
         * @var NodeInterface $node
         */
        foreach ($this->accessor->enumerate($payload) as $name => $node) {
            Assert::assertEquals([$name], $node->getPath());
            Assert::assertEquals($values[$name], $node->getValue());
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
        $payload = [];
        /** @noinspection PhpParamsInspection */
        $this->accessor->write($payload, self::KEY, self::VALUE);
    }
}
