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

## Create your own Toggle

Take a look at the `AlwaysEnabledToggle` and `AlwaysDisabledToggle`. This is how
more complex Toggles can be created. These two are as simple as you can get.
