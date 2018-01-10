<?php

namespace AmaTeam\TreeAccess\Test\Suite\Acceptance;

use AmaTeam\TreeAccess\TreeAccess;
use Codeception\Test\Unit;
use PHPUnit\Framework\Assert;
use stdClass;

class SuccessfulExistenceTest extends Unit
{
    public function dataProvider()
    {
        $variations = [];

        $variations[] = ['anything', [], true];

        $variations[] = [['alpha' => 12], ['alpha'], true];

        $variations[] = [['alpha' => 12], ['beta'], false];

        $structure = new stdClass();
        $structure->alpha = 12;
        $variations[] = [$structure, 'alpha', true];

        $structure = new stdClass();
        $variations[] = [$structure, 'alpha', false];

        return $variations;
    }

    /**
     * @param mixed $structure
     * @param string|string[] $path
     * @param bool $expectation
     *
     * @dataProvider dataProvider
     */
    public function testSuccessfulExistence($structure, $path, $expectation)
    {
        $accessor = TreeAccess::createAccessorBuilder()->build();
        Assert::assertEquals($expectation, $accessor->exists($structure, $path));
    }
}
