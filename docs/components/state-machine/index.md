---
title: State Machine
---

## Installation

```shell
composer require sonsofphp/state-machine
```

## Usage

Basic Usage

```php
<?php

use SonsOfPHP\Component\StateMachine\StateMachine;

$sm = new StateMachine([
    'graph'         => 'order',
    'state_getter'  => 'getState',
    'state_setter'  => 'setState',
    'supports' => [
        OrderInterface::class,
    ],
    'transitions' => [
        'create' => [
            'from' => 'draft',
            'to' => 'new',
            'callbacks' => [
                'guard' => [
                    'guard-create' => [
                        'do' => function () { return true; },
                    ],
                ],
                'pre' => [
                    'pre-create' => [
                        'do' => function () { },
                    ],
                    'another-pre-create' => [
                        'do' => function () {},
                    ],
                ],
                'post' => [
                    'post-create' => [
                        'do' => function () {},
                    ],
                    'another-post-create' => [
                        'do' => function () {},
                    ],
                ],
            ],
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
