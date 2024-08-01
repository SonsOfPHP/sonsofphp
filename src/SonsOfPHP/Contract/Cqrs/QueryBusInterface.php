<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Cqrs;

use SonsOfPHP\Contract\Cqrs\Exception\CqrsExceptionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface QueryBusInterface
{
    /**
     * @throws \SonsOfPHP\Contract\Cqrs\Exception\NoHandlerExceptionInterface
     * @throws CqrsExceptionInterface
     */
    public function handle(QueryMessageInterface $query): mixed;
}
