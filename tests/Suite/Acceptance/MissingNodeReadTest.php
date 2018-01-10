<?php

namespace AmaTeam\TreeAccess\Test\Suite\Acceptance;

use AmaTeam\TreeAccess\API\Exception\MissingNodeException;
use AmaTeam\TreeAccess\TreeAccess;
use Codeception\Test\Unit;

class MissingNodeReadTest extends Unit
{
    public function dataProvider()
    {
        $variations = [];
        $variations[] = [[], 'alpha'];
        return $variations;
    }

    /**
     * @param mixed $structure
     * @param string|string[] $path
     *
     * @dataProvider dataProvider
     */
    public function testUnsuccessfulRead($structure, $path)
    {
        $this->expectException(MissingNodeException::class);
        $accessor = TreeAccess::createAccessorBuilder()->build();
        $accessor->read($structure, $path);
    }
}
