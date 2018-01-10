<?php

namespace AmaTeam\TreeAccess\Test\Support\TreeAccess;

class PublicPropertiesObject
{
    const ALPHA = 12;
    const BETA = 13;
    const VALUES = [
        'alpha' => self::ALPHA,
        'beta' => self::BETA,
    ];

    public $alpha = self::ALPHA;
    public $beta = self::BETA;
}
