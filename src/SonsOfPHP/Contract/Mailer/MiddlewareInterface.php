<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Mailer;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MiddlewareInterface
{
    public function __invoke(MessageInterface $message, MiddlewareHandlerInterface $handler);
}
