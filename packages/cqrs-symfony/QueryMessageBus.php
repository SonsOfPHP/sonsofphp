<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\Cqrs;

use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Query Bus that uses Symfony Messenger
 *
 * If using Symfony Framework, add to your services.yaml
 * <code>
 * SonsOfPHP\Bridge\Symfony\Cqrs\QueryMessageBus:
 *     arguments: ['@query.bus']
 * </code>
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 *
 * @see https://symfony.com/doc/current/messenger/multiple_buses.html
 * @see https://symfony.com/doc/current/messenger.html
 * @see https://symfony.com/doc/current/components/messenger.html
 */
class QueryMessageBus
{
    use HandleTrait {
        handle as handleQuery;
    }

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    /**
     * Handle the Query and return the results.
     *
     * @return mixed
     */
    public function handle(object $query)
    {
        return $this->handleQuery($query);
    }
}
