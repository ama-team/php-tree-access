<?php

namespace AmaTeam\TreeAccess\Test\Suite\Acceptance;

use AmaTeam\TreeAccess\Test\Support\TreeAccess\ValueContainer;
use AmaTeam\TreeAccess\TreeAccess;
use Codeception\Test\Unit;
use PHPUnit\Framework\Assert;
use stdClass;

class SuccessfulReadTest extends Unit
{
    public function dataProvider()
    {
        $variations = [];

        $variations[] = [12, '', 12];

        $variations[] = [['value' => 12], 'value', 12];

        $structure = ['alpha' => ['beta' => [1, 2]]];
        $variations[] = [$structure, 'alpha.beta.1', 2];

        $structure = new stdClass();
        $structure->property = new ValueContainer(12);
        $variations[] = [$structure, 'property.value', 12];

        $subStructure = ['beta' => 12];
        $structure = ['alpha' => $subStructure];
        $variations[] = [$structure, 'alpha', $subStructure];
        return $variations;
    }

    /**
     * @param $structure
     * @param $path
     * @param $value
     *
     * @dataProvider dataProvider
     */
    public function testSuccessfulVariations($structure, $path, $value)
    {
        $accessor = TreeAccess::createAccessorBuilder()->build();
        $extracted = $accessor->read($structure, $path);
        Assert::assertEquals($value, $extracted);
    }
}
