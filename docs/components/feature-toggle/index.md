---
title: Feature Toggle - Overview
---

## Installation

```shell
composer require sonsofphp/feature-toggle
```

## Usage

```php
<?php

use SonsOfPHP\Component\FeatureToggle\Feature;
use SonsOfPHP\Component\FeatureToggle\Provider\InMemoryFeatureToggleProvider;
use SonsOfPHP\Component\FeatureToggle\Toggle\AlwaysEnabledToggle;

// Using a feature toggle provider
$provider = new InMemoryFeatureToggleProvider();
$provider->addFeature(new Feature('feature.example', new AlwaysEnabledToggle()));

$feature = $provider->getFeatureToggleByKey('feature.example');

// Checking if the feature is enabled
$isEnabled = $feature->isEnabled();
```

## Advanced Usage

```php
<?php

// ...
use SonsOfPHP\Component\FeatureToggle\Context;

// Different ways to build the context, the context
// is used by the Toggle
$context = new Context([
    'user' => $user,
]);
$context->set('user', $user);
$context['user'] = $user;

$isEnabled = $feature->isEnabled($context);
```

When you create your own toggles, you may need to introduce additional context
to the toggle to check if everything should be enabled or disabled. This is
where this comes into play at.

### Chain Toggle

The chain toggle allows you to use many toggles together. If ANY toggle returns
`true`, the feature is considered enabled.

```php
<?php

use SonsOfPHP\Component\FeatureToggle\Toggle\AlwaysDisabledToggle;
use SonsOfPHP\Component\FeatureToggle\Toggle\AlwaysEnabledToggle;
use SonsOfPHP\Component\FeatureToggle\Toggle\ChainToggle;

$toggle = new ChainToggle([
    new AlwaysEnabledToggle(),
    new AlwaysDisabledToggle(),
]);

// true because at least ONE is enabled
$isEnabled = $toggle->isEnabled();
```

### Affirmative Toggle

Similar to the chain toggle, this will only return `true` when ALL toggles are
`true`.

```php
<?php

use SonsOfPHP\Component\FeatureToggle\Toggle\AlwaysDisabledToggle;
use SonsOfPHP\Component\FeatureToggle\Toggle\AlwaysEnabledToggle;
use SonsOfPHP\Component\FeatureToggle\Toggle\ChainToggle;

$toggle = new ChainToggle([
    new AlwaysDisabledToggle(),
    new AlwaysEnabledToggle(),
]);

// false because at least ONE is disabled
$isEnabled = $toggle->isEnabled();
```

### Date Range Toggle

The date range toggle will return `true` if it's within a given time range.

```php
<?php

use SonsOfPHP\Component\FeatureToggle\Toggle\DateRangeToggle;

$toggle = new ChainToggle(
    start: new \DateTimeImmutable('2024-01-01'),
    end: new \DateTimeImmutable('2024-12-31'),
);

// ...
```

## Create your own Toggle

Take a look at how some of the other toggles are implemented. Creating your own
toggles are very easy. You just need to make sure they implement the interface
`ToggleInterface`.

```php
<?php

use SonsOfPHP\Contract\FeatureToggle\ContextInterface;
use SonsOfPHP\Contract\FeatureToggle\ToggleInterface;

class MyCustomToggle implements ToggleInterface
{
    public function isEnabled(?ContextInterface $context = null): bool
    {
        // ...
    }
}
```

Once you make your custom toggle, you can use it just like all the rest of the
toggles.
