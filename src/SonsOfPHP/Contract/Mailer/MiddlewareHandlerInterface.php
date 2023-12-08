<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Mailer;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MiddlewareHandlerInterface
{
    public function handle(MessageInterface $message): MessageInterface;
}
