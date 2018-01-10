<?php

namespace AmaTeam\TreeAccess\Test\Suite\Acceptance;

use AmaTeam\TreeAccess\Test\Support\TreeAccess\PublicPropertiesObject;
use AmaTeam\TreeAccess\Test\Support\TreeAccess\SetterObject;
use AmaTeam\TreeAccess\TreeAccess;
use Codeception\Test\Unit;
use PHPUnit\Framework\Assert;
use stdClass;

class SuccessfulWriteTest extends Unit
{
    public function dataProvider()
    {
        $variations = [];
        $structure = ['alpha' => ['beta' => []]];
        $expectation = ['alpha' => ['beta' => [1 => 12]]];
        $variations[] = [$structure, 'alpha.beta.1', 12, $expectation];
        $structure = new PublicPropertiesObject();
        $structure->alpha = new stdClass();
        $structure->alpha->child = new SetterObject();
        $expectation = new PublicPropertiesObject();
        $expectation->alpha = new stdClass();
        $expectation->alpha->child = (new SetterObject())->setValue(13);
        $variations[] = [$structure, 'alpha.child.value', 13, $expectation];
        $expectation = ['beta' => 2];
        $variations[] = [['alpha' => 1], '', $expectation, $expectation];
        return $variations;
    }

    /**
     * @param $structure
     * @param $path
     * @param $value
     * @param $expectation
     *
     * @dataProvider dataProvider
     */
    public function testSuccessfulWrite($structure, $path, $value, $expectation)
    {
        $accessor = TreeAccess::createAccessorBuilder()->build();
        $accessor->write($structure, $path, $value);
        Assert::assertEquals($structure, $expectation);
    }
}
