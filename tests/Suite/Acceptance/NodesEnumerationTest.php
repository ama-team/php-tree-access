<?php

namespace AmaTeam\TreeAccess\Test\Suite\Acceptance;

use AmaTeam\TreeAccess\API\NodeInterface;
use AmaTeam\TreeAccess\Node;
use AmaTeam\TreeAccess\Test\Support\TreeAccess\PublicPropertiesObject;
use AmaTeam\TreeAccess\TreeAccess;
use Codeception\Test\Unit;
use PHPUnit\Framework\Assert;
use stdClass;

class NodesEnumerationTest extends Unit
{
    public function dataProvider()
    {
        $variations = [];

        $structure = new stdClass();
        $structure->child = new PublicPropertiesObject();
        $expectation = [];
        foreach (PublicPropertiesObject::VALUES as $name => &$value) {
            $expectation[$name] = new Node(['child', $name], $value);
        }
        $variations[] = [$structure, 'child', $expectation];

        return $variations;
    }

    /**
     * @param mixed $structure
     * @param string|string[] $path
     * @param NodeInterface[] $expectation
     *
     * @dataProvider dataProvider
     */
    public function testEnumeration($structure, $path, $expectation)
    {
        $accessor = TreeAccess::createAccessorBuilder()->build();
        $nodes = $accessor->enumerateNodes($structure, $path);
        $methods = ['getValue', 'getPath', 'isWritable', 'isReadable'];
        /**
         * @var string $name
         * @var NodeInterface $node
         */
        foreach ($nodes as $name => $node) {
            foreach ($methods as $method) {
                $expected = call_user_func([$expectation[$name], $method]);
                $value = call_user_func([$node, $method]);
                Assert::assertEquals($expected, $value);
            }
        }
    }
}
