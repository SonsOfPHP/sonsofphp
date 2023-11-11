<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Cqrs;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface CommandMessageHandlerInterface extends MessageHandlerInterface
{
    //public function __invoke(CommandMessageInterface $message): void;
}
