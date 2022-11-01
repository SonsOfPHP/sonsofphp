---
title: CQRS
---

Command Query Responsibility Segregation is a simple concept. When you want
something to happen, you will use a Command. If you need results returned, you
will use a query.

An over simplified example of this would be if you need to write to a database,
you would use a Command. If you need results from a database, you would use a
Query.

# Installation

```shell
composer require sonsofphp/cqrs
```

!!! success "Symfony Bridge"
    Installing the Symfony Bridge will add additional functionality to the CQRS
    component. This can be done quickly and easily. The documentation will point
    out where this is required.

    ```shell
    composer require sonsofphp/cqrs-symfony
    ```

!!! tip
    This component pairs well with the `sonsofphp/event-sourcing` component.

    ```shell
    composer require sonsofphp/event-sourcing
    ```


[messenger]: https://symfony.com/doc/current/components/messenger.html
[options_resolver]: https://symfony.com/doc/current/components/options_resolver.html
