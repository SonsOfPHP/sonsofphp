<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Cqrs\Query;

use SonsOfPHP\Contract\Cqrs\MessageHandlerInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface QueryMessageHandlerInterface extends MessageHandlerInterface
{
    //public function __invoke(QueryMessageInterface $message): mixed;
}
