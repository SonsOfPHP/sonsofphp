<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Cqrs\Query;

use SonsOfPHP\Contract\Cqrs\Query\QueryMessageInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface QueryBusInterface
{
    /**
     * @throws \SonsOfPHP\Contract\Cqrs\Exception\NoHandlerExceptionInterface
     * @throws \SonsOfPHP\Contract\Cqrs\Exception\CqrsExceptionInterface
     */
    public function handle(QueryMessageInterface $query): mixed;
}
