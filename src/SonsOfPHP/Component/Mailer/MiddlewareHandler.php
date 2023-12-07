<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Mailer;

use SonsOfPHP\Contract\Mailer\MailerInterface;
use SonsOfPHP\Contract\Mailer\TransportInterface;
use SonsOfPHP\Contract\Mailer\MessageInterface;
use SonsOfPHP\Contract\Mailer\MiddlewareInterface;
use SonsOfPHP\Contract\Mailer\MiddlewareHandlerInterface;
use SonsOfPHP\Contract\Mailer\MiddlewareStackInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class MiddlewareHandler implements MiddlewareHandlerInterface
{
    public function __construct(
        private MiddlewareStackInterface $stack = new MiddlewareStack()
    ) {}

    public function getMiddlewareStack(): MiddlewareStackInterface
    {
        return $this->stack;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(MessageInterface $message): MessageInterface
    {
        if (0 === count($this->stack)) {
            return $message;
        }

        $next = $this->stack->next();

        return $next($message, $this);
    }
}
