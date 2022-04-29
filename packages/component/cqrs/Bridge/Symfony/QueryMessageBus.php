<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cqrs\Bridge\Symfony;

use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class QueryMessageBus
{
    use HandleTrait {
        handle as handleQuery;
    }

    /**
     * @param MessageBusInterface $queryBus
     */
    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    /**
     * Handle the Query and return the results
     *
     * @param object $query
     *
     * @return mixed
     */
    public function handle(object $query)
    {
        return $this->handleQuery($query);
    }
}
