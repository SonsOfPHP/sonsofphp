<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cqrs\Query;

use SonsOfPHP\Contract\Cqrs\MessageHandlerInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
abstract class AbstractQueryMessageHandler implements QueryMessageHandlerInterface
{
    abstract public function __invoke(QueryMessageInterface $message): mixed;
}
