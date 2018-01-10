<?php

namespace AmaTeam\TreeAccess\Test\Suite\Unit\Misc;

use AmaTeam\TreeAccess\Misc\Strings;
use Codeception\Test\Unit;
use PHPUnit\Framework\Assert;

class StringsTest extends Unit
{
    public function stripCamelCasePrefixDataProvider()
    {
        return [
            [
                'noPrefixMatch',
                'prefix',
                false
            ],
            [
                'prefixedPhraseOfSeveralWords',
                'prefixed',
                'phraseOfSeveralWords'
            ],
            [
                '',
                '',
                ''
            ]
        ];
    }

    /**
     * @param string $input
     * @param string $prefix
     * @param string|false $expectation
     *
     * @dataProvider stripCamelCasePrefixDataProvider
     */
    public function testStripCamelCasePrefix($input, $prefix, $expectation)
    {
        $stripped = Strings::stripCamelCasePrefix($input, $prefix);
        Assert::assertEquals($expectation, $stripped);
    }
}
