---
title: Queries
---

# Queries

```php
<?php

use SonsOfPHP\Component\Cqrs\Query\QueryMessageInterface;

final class ExampleQuery implements QueryMessageInterface
{
    public function __construct(public readonly string $userId)
    {
    }
}
```
