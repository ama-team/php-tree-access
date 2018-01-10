# WIP note

This is a work-in-progress project, it is not ready yet

# AmaTeam\TreeAccess

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
