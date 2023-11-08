<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Cqrs;

use SonsOfPHP\Contract\Cqrs\Command\CommandMessageInterface;
use SonsOfPHP\Contract\Cqrs\Command\CommandMessageHandlerInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface CommandHandlerProviderInterface
{
    /**
     * @throws \SonsOfPHP\Contract\Cqrs\Exception\NoHandlerExceptionInterface
     * @throws \SonsOfPHP\Contract\Cqrs\Exception\CqrsExceptionInterface
     */
    public function getHandlerForCommand(CommandMessageInterface $command): CommandMessageHandlerInterface;
}
