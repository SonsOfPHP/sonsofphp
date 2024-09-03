Sons of PHP - State Machine
===========================

```php
<?php

use SonsOfPHP\Component\StateMachine\StateMachine;

$sm = new StateMachine([
    'graph' => 'order',
    'supports' => [
        OrderInterface::class,
    ],
    'transitions' => [
        'create' => [
            'from' => 'draft',
            'to' => 'new',
        ],
        'fulfill' => [
            'from' => 'new',
            'to' => 'fulfilled',
        ],
        'cancel' => [
            'from' => ['draft', 'new', 'fulfilled'],
            'to' => 'fulfilled',
        ],
    ],
]);

// Check if state can change
$sm->can($order, 'create');

// Apply transition
$sm->apply($order, 'fulfil');

// Get Current State
$sm->getState($order);
```

## Learn More

* [Documentation][docs]
* [Contributing][contributing]
* [Report Issues][issues] and [Submit Pull Requests][pull-requests] in the [Mother Repository][mother-repo]
* Get Help & Support using [Discussions][discussions]

[discussions]: https://github.com/orgs/SonsOfPHP/discussions
[mother-repo]: https://github.com/SonsOfPHP/sonsofphp
[contributing]: https://docs.sonsofphp.com/contributing/
[docs]: https://docs.sonsofphp.com/components/state-machine/
[issues]: https://github.com/SonsOfPHP/sonsofphp/issues?q=is%3Aopen+is%3Aissue+label%3AStateMachine
[pull-requests]: https://github.com/SonsOfPHP/sonsofphp/pulls?q=is%3Aopen+is%3Apr+label%3AStateMachine
