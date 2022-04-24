Sons of PHP - Feature Toggle Component
======================================

PHP Library that helps devs put code behind feature toggles.

## Installation

```shell
composer require sonsofphp/feature-toggle
```

## Usage

```php
use SonsOfPHP\Component\FeatureToggle\ActivatationStrategy\AlwaysEnabledStrategy;
use SonsOfPHP\Component\FeatureToggle\FeatureToggle;

// Create a new feature toggle and give it a strategy
$toggle = new FeatureToggle(new AlwaysEnabledStrategy());

// Check if the feature toggle is enabled
$enabled = $toggle->isEnabled(); // @var bool $enabled
```
