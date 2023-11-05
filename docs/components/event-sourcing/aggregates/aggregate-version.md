---
title: Aggregate Versions
---

# Aggregate Version

An Aggregate also has a version. Each event that is raised will increase the
version. You will generally not have to work much with versions as they are
mostly handled internally.

### Working with an Aggregate Version

```php
<?php
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersion;

$aggregateVersion = new AggregateVersion();

// Get the version
$version = $aggregateVersion->toInt();

// Comparing Versions
if ($aggregateVersion->equals($anotherAggregateVersion)) {
    // they are the same
}
```
