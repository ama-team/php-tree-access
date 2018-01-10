<?php

namespace AmaTeam\TreeAccess\Test\Suite\Acceptance;

use AmaTeam\TreeAccess\API\Exception\IllegalTargetException;
use AmaTeam\TreeAccess\TreeAccess;
use Codeception\Test\Unit;

class IllegalReadTest extends Unit
{
    public function dataProvider()
    {
        $variations = [];
        $variations[] = [null, 'value'];
        return $variations;
    }

    /**
     * @param mixed $structure
     * @param string|string[] $path
     *
     * @dataProvider dataProvider
     */
    public function testIllegalRead($structure, $path)
    {
        $this->expectException(IllegalTargetException::class);
        $accessor = TreeAccess::createAccessorBuilder()->build();
        $accessor->read($structure, $path);
    }
}
