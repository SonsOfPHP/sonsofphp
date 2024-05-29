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

Take a look at the `AlwaysEnabledToggle` and `AlwaysDisabledToggle`. This is how
more complex Toggles can be created. These two are as simple as you can get.
