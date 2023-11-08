<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Cqrs;

use SonsOfPHP\Contract\Cqrs\Query\QueryMessageInterface;
use SonsOfPHP\Contract\Cqrs\Query\QueryMessageHandlerInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface QueryHandlerProviderInterface
{
    /**
     * @throws \SonsOfPHP\Contract\Cqrs\Exception\NoHandlerExceptionInterface
     * @throws \SonsOfPHP\Contract\Cqrs\Exception\CqrsExceptionInterface
     */
    public function getHandlerForQuery(QueryMessageInterface $command): QueryMessageHandlerInterface;
}
