Sons of PHP - Feature Toggle Bundle
===================================

## Installation

Make sure Composer is installed globally, as explained in the [installation chapter](https://getcomposer.org/doc/00-intro.md) of the Composer documentation.

### Applications that use Symfony Flex

Open a command console, enter your project directory and execute:

```sh
composer require sonsofphp/feature-toggle-bundle
```

### Applications that don't use Symfony Flex

#### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the following command to download the latest stable version of this bundle:

```sh
composer require sonsofphp/feature-toggle-bundle
```

#### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    SonsOfPHP\Bundle\FeatureToggleBundle\SonsOfPHPFeatureToggleBundle::class => ['all' => true],
];
```

## Configuration

```yaml
# config/packages/sons_of_php_feature_toggle.yaml
sons_of_php_feature_toggle:
  features:
    # You can create as many features as you want
    enabled_key:
      # Features can be enabled, disabled, or use a custom toggle
      toggle: enabled
    disabled_key:
      toggle: disabled
    custom_toggle_key:
      toggle: app.toggle.admin_users
```

## Debug Command

You can debug your features by running the `debug:features` command.

```sh
php bin/console debug:features
```

This will give you a list of features and the toggles they are using.

## Twig Templates

You can check to see if the feature is enabled in twig templates by using the `is_feature_enabled` function.

```twig
{% raw %}
{% if is_feature_enabled('enabled_key') %}
    Feature "enabled_key" is enabled
{% else %}
    Feature "enabled_key" is disabled
{% endif %}
{% endraw %}
```

## Services

```php
<?php

use SonsOfPHP\Contract\FeatureToggle\FeatureToggleProviderInterface;

class ExampleService
{
    public function __construct(
        private FeatureToggleProviderInterface $featureToggleProvider,
    ) {}

    public function doSomething()
    {
        if ($featureToggleProvider->get('enabled_key')->isEnabled()) {
            // "enabled_key" is enabled
        }
    }
}
```

## Learn More

* [Documentation][docs]
* [Contributing][contributing]
* [Report Issues][issues] and [Submit Pull Requests][pull-requests] in the [Mother Repository][mother-repo]
* Get Help & Support using [Discussions][discussions]

[discussions]: https://github.com/orgs/SonsOfPHP/discussions
[mother-repo]: https://github.com/SonsOfPHP/sonsofphp
[contributing]: https://docs.sonsofphp.com/contributing/
[docs]: https://docs.sonsofphp.com/
[issues]: https://github.com/SonsOfPHP/sonsofphp/issues?q=is%3Aopen+is%3Aissue+label%3AFeatureToggle
[pull-requests]: https://github.com/SonsOfPHP/sonsofphp/pulls?q=is%3Aopen+is%3Apr+label%3AFeatureToggle
