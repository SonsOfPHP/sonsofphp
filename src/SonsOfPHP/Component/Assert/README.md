Sons of PHP - Assert
====================

## Installation

```shell
composer require sonsofphp/assert
```

## Usage

```php
<?php

use SonsOfPHP\Component\Assert\Assert;

Assert::string('test'); // returns true
Assert::string(false); // throws exception

Assert::disable();
Assert::string(1234); // returns false
```

## Learn More

* [Documentation][docs]
* [Contributing][contributing]
* [Report Issues][issues] and [Submit Pull Requests][pull-requests] in the [Mother Repository][mother-repo]
* Get Help & Support using [Discussions][discussions]

[discussions]: https://github.com/orgs/SonsOfPHP/discussions
[mother-repo]: https://github.com/SonsOfPHP/sonsofphp
[contributing]: https://docs.sonsofphp.com/contributing/
[docs]: https://docs.sonsofphp.com/components/assert/
[issues]: https://github.com/SonsOfPHP/sonsofphp/issues?q=is%3Aopen+is%3Aissue+label%3AAssert
[pull-requests]: https://github.com/SonsOfPHP/sonsofphp/pulls?q=is%3Aopen+is%3Apr+label%3AAssert
