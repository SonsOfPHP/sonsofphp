---
title: Queries
---

# Queries

```php
<?php
use SonsOfPHP\Component\Cqrs\Query\QueryMessageInterface;

final class ExampleQuery implements QueryMessageInterface
{
    private $arg;

    public function __construct($arg)
    {
        $this->arg = $arg;
    }

    public function getArg()
    {
        return $this->arg;
    }
}
```
