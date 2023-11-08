<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cqrs\Command;

use SonsOfPHP\Contract\Cqrs\MessageHandlerInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
abstract class AbstractCommandMessageHandler implements CommandMessageHandlerInterface
{
    abstract public function __invoke(CommandMessageInterface $message): void;
}
