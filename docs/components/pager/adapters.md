# Adapters

## Custom Adapters

Creating Custom Adapters is easy. You can take a look at the available adapters to see how easy it is.

Please see the [Pager Contract](../../contracts/pager.md) to learn more.

## Available Adapters

### ArrayAdapter

```php
<?php

use SonsOfPHP\Component\Pager\Adapter\ArrayAdapter;

$adapter = new ArrayAdapter($results);
```

### CallableAdabter

Will take any `callable` arguments.

```php
<?php

use SonsOfPHP\Component\Pager\Adapter\CallableAdapter;

$adapter = new CallableAdapter(
    count: function (): int {
        // ...
    },
    slice: function (int $offset, ?int $length): iterable {
        // ...
    },
);
```

### ArrayCollectionAdapter (doctrine/collections)

{% hint style="danger" %}
Requires `sonsofphp/pager-doctrine-collections`
{% endhint %}

```php
<?php

use Doctrine\Common\Collections\ArrayCollection;
use SonsOfPHP\Bridge\Doctrine\Collections\Pager\ArrayCollectionAdapter;

$collection = new ArrayCollection();

$adapter = new ArrayCollectionAdapter($collection);
```

### QueryBuilderAdapter (doctrine/dbal)

{% hint style="danger" %}
Requires `sonsofphp/pager-doctrine-dbal`
{% endhint %}

```php
<?php

use Doctrine\DBAL\Query\QueryBuilder;
use SonsOfPHP\Bridge\Doctrine\DBAL\Pager\QueryBuilderAdapter;

// ...

$adapter = new QueryBuilderAdapter($builder, function (QueryBuilder $builder): void {
    $builder->select('COUNT(e.id) as total');
});
```

### QueryBuilderAdapter (doctrine/orm)

{% hint style="danger" %}
Requires `sonsofphp/pager-doctrine-orm`
{% endhint %}

```php
<?php

use Doctrine\ORM\QueryBuilder;
use SonsOfPHP\Bridge\Doctrine\ORM\Pager\QueryBuilderAdapter;

$builder = $repository->createQueryBuilder('e');

$adapter = new QueryBuilderAdapter($builder);
```

