<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Cqrs;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MessageHandlerProviderInterface
{
    /**
     * @throws \SonsOfPHP\Contract\Cqrs\Exception\NoHandlerExceptionInterface
     * @throws \SonsOfPHP\Contract\Cqrs\Exception\CqrsExceptionInterface
     */
    public function getHandlerForMessage(MessageInterface $message): MessageHandlerInterface;
}
