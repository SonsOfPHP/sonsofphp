<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Cqrs;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface QueryMessageHandlerInterface extends MessageHandlerInterface
{
    /**
     * @throws \SonsOfPHP\Contract\Cqrs\Exception\CqrsExceptionInterface
     *   When something really fucks up and the handler is unable to handle
     *   it's shit
     */
    public function __invoke(QueryMessageInterface $message): mixed;
}
