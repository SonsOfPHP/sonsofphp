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

## Reporting Issues

Please report issues in the mother repository, [sonsofphp/sonsofphp][mother].

## Getting Help

You can get help by using the [Discussions][discussions] link on our
[site][homepage].

## Contributing

Just fork [SonsOfPHP/SonsOfPHP][mother] and create a PR with the updates you
want.

[mother]: <https://github.com/SonsOfPHP/sonsofphp> "Sons of PHP Mother Repository"
[discussions]: https://github.com/orgs/SonsOfPHP/discussions
[homepage]: https://github.com/SonsOfPHP
