# AmaTeam\TreeAccess

[![Packagist](https://img.shields.io/packagist/v/ama-team/tree-access.svg?style=flat-square)](https://packagist.org/packages/ama-team/tree-access)
[![CircleCI/master](https://img.shields.io/circleci/project/github/ama-team/php-tree-access/master.svg?style=flat-square)](https://circleci.com/gh/ama-team/php-tree-access/tree/master)
[![Coveralls/master](https://img.shields.io/coveralls/github/ama-team/php-tree-access/master.svg?style=flat-square)](https://coveralls.io/github/ama-team/php-tree-access?branch=master)
[![Scrutinizer/master](https://img.shields.io/scrutinizer/g/ama-team/php-tree-access/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/ama-team/php-tree-access?branch=master)

This tiny project allows to read, enumerate and write properties / 
elements in a nested tree consisting of arrays and objects.

It is very close to symfony/property-access, but have two differences:

- This library treats array elements and object properties the same way,
providing a unified tree access. This may be both desirable and not, 
depending on real application, so property-access may be more useful for
you.
- This library allows to enumerate properties of a specific node. 

## Installation

```bash
composer require ama-team/tree-access
```

## Usage

```php
<?php

$object = new stdClass();
$object->values = ['apples' => 'green'];
$root = [$object];

$accessor = AmaTeam\TreeAccess\TreeAccess::createAccessor();

// 'green'
$color = $accessor->read($root, '0.values.apples');
// List of AmaTeam\TreeAccess\API\NodeInterface
$values = $accessor->enumerate($root, '0.values');
// NodeInterface that got updated
$accessor->write($root, '0.values.melon', 'yellow');
// false
$accessor->exists($root, '0.values.watermelon');
```

Setters / getters support is already bundled in, and accessing missing
nodes will throw an exception (except when setting leaf of existing
node).

## Performance

As of 0.1.x, possible performance optimizations are sacrificed for 
simplicity.

## Contributing

Feel free to send PR to **dev** branch

## Dev branch state

[![CircleCI/dev](https://img.shields.io/circleci/project/github/ama-team/php-tree-access/dev.svg?style=flat-square)](https://circleci.com/gh/ama-team/php-tree-access/tree/dev)
[![Coveralls/dev](https://img.shields.io/coveralls/github/ama-team/php-tree-access/dev.svg?style=flat-square)](https://coveralls.io/github/ama-team/php-tree-access?branch=dev)
[![Scrutinizer/dev](https://img.shields.io/scrutinizer/g/ama-team/php-tree-access/dev.svg?style=flat-square)](https://scrutinizer-ci.com/g/ama-team/php-tree-access?branch=dev)

## Licensing

MIT License / AMA Team, 2018
