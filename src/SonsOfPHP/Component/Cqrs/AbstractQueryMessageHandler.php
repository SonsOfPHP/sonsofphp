<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cqrs;

use SonsOfPHP\Contract\Cqrs\QueryMessageHandlerInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
abstract class AbstractQueryMessageHandler implements QueryMessageHandlerInterface
{
    abstract public function __invoke(QueryMessageInterface $message): mixed;
}
