<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Cqrs;

use SonsOfPHP\Contract\Cqrs\Command\CommandMessageInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface CommandBusInterface
{
    /**
     * A command will never return any data. It can be handled syncrhonously or
     * asyncrhonously.
     *
     * @throws \SonsOfPHP\Contract\Cqrs\Exception\NoHandlerExceptionInterface
     *   Exception is thrown if there is no handler for the given command
     *
     * @throws \SonsOfPHP\Contract\Cqrs\Exception\CqrsExceptionInterface
     *   If something fucks up, this will be thrown
     */
    public function dispatch(CommandMessageInterface $command): void;
}
