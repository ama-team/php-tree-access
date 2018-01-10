<?php

namespace AmaTeam\TreeAccess\Test\Suite\Acceptance;

use AmaTeam\TreeAccess\API\Exception\IllegalTargetException;
use AmaTeam\TreeAccess\TreeAccess;
use Codeception\Test\Unit;

class IllegalWriteTest extends Unit
{
    public function dataProvider()
    {
        $variations = [];

        $variations[] = ['value', '1', 12];

        return $variations;
    }

    /**
     * @param mixed $structure
     * @param string|string[] $path
     * @param mixed $value
     *
     * @dataProvider dataProvider
     */
    public function testIllegalWrite($structure, $path, $value)
    {
        $this->expectException(IllegalTargetException::class);
        $accessor = TreeAccess::createAccessorBuilder()->build();
        $accessor->write($structure, $path, $value);
    }
}
