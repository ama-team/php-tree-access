<?php

namespace AmaTeam\TreeAccess\Test\Suite\Unit;

use AmaTeam\TreeAccess\Paths;
use Codeception\Test\Unit;
use PHPUnit\Framework\Assert;

class PathsTest extends Unit
{
    public function toStringDataProvider()
    {
        return [
            [
                ['alpha', 'beta', 'gamma'],
                'alpha.beta.gamma'
            ],
            [
                [],
                ''
            ]
        ];
    }

    /**
     * @param string[] $input
     * @param string $expectation
     * @dataProvider toStringDataProvider
     */
    public function testToString(array $input, $expectation)
    {
        $encoded = Paths::toString($input);
        Assert::assertEquals($expectation, $encoded);
    }

    public function fromStringDataProvider()
    {
        return [
            [
                'alpha.beta.gamma',
                ['alpha', 'beta', 'gamma']
            ],
            [
                '',
                []
            ]
        ];
    }

    /**
     * @param string $input
     * @param string[] $expectation
     * @dataProvider fromStringDataProvider
     */
    public function testFromString($input, array $expectation)
    {
        $extracted = Paths::fromString($input);
        Assert::assertEquals($expectation, $extracted);
    }

    public function normalizationDataProvider()
    {
        return [
            [
                'alpha.beta.gamma',
                ['alpha', 'beta', 'gamma'],
            ],
            [
                ['alpha', 'beta', 'gamma'],
                ['alpha', 'beta', 'gamma'],
            ],
        ];
    }

    /**
     * @param string|string[] $input
     * @param string[] $expectation
     *
     * @dataProvider normalizationDataProvider
     */
    public function testNormalization($input, array $expectation)
    {
        $normalized = Paths::normalize($input);
        Assert::assertEquals($expectation, $normalized);
    }
}
