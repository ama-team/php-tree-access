<?php

namespace AmaTeam\TreeAccess\Test\Suite\Acceptance;

use AmaTeam\TreeAccess\ActiveNode;
use AmaTeam\TreeAccess\API\Exception\IllegalTargetException;
use AmaTeam\TreeAccess\Test\Support\TreeAccess\ValueContainer;
use AmaTeam\TreeAccess\TreeAccess;
use Codeception\Test\Unit;
use PHPUnit\Framework\Assert;
use stdClass;

class ActiveNodeTest extends Unit
{
    public function dataProvider()
    {
        $variations = [];

        $structure = new stdClass();
        $structure->children = [new ValueContainer(12)];
        $expectation = new stdClass();
        $expectation->children = [new ValueContainer(13), 12];
        $expectation->number = 12;
        $mutator = function (ActiveNode $node) {
            $child = $node->getChild('children');
            $twelve = 12;
            $child->setChild('1', $twelve);
            $thirteen = 13;
            $child->getChild('0')->getChild('value')->setValue($thirteen);
            $node->setChild('number', $twelve);
        };
        $variations[] = [$structure, $expectation, $mutator];

        $structure = 12;
        $expectation = 13;
        $mutator = function (ActiveNode $node) use ($expectation) {
            $node->setValue($expectation);
        };
        $variations[] = [$structure, $expectation, $mutator];

        return $variations;
    }

    /**
     * @param mixed $structure
     * @param mixed $expectation
     * @param callable $mutator
     *
     * @dataProvider dataProvider
     */
    public function testLifecycle($structure, $expectation, $mutator)
    {
        $accessor = TreeAccess::createAccessor();
        $node = $accessor->wrap($structure);
        $mutator($node);
        Assert::assertEquals($expectation, $structure);
    }

    public function testEnumeration()
    {
        $structure = new stdClass();
        $structure->alpha = 12;
        $structure->beta = 12;

        $expectation = new stdClass();
        $expectation->alpha = 13;
        $expectation->beta = 13;

        $accessor = TreeAccess::createAccessor();
        $node = $accessor->wrap($structure);
        $thirteen = 13;
        foreach ($node->enumerate() as $child) {
            $child->setValue($thirteen);
        }
        Assert::assertEquals($expectation, $structure);
    }

    public function testUnsuccessfulEnumeration()
    {
        $this->expectException(IllegalTargetException::class);
        $structure = 12;
        $accessor = TreeAccess::createAccessor();
        $node = $accessor->wrap($structure);
        $node->enumerate();
    }
}
