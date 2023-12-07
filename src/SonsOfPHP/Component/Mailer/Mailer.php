<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Mailer;

use SonsOfPHP\Contract\Mailer\MailerInterface;
use SonsOfPHP\Contract\Mailer\TransportInterface;
use SonsOfPHP\Contract\Mailer\MessageInterface;
use SonsOfPHP\Contract\Mailer\MiddlewareInterface;
use SonsOfPHP\Contract\Mailer\MiddlewareHandlerInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Mailer implements MailerInterface
{
    public function __construct(
        private TransportInterface $transport,
        private MiddlewareHandlerInterface $handler = new MiddlewareHandler(),
    ) {}

    public function addMiddleware(MiddlewareInterface $middleware): void
    {
        $this->handler->getMiddlewareStack()->add($middleware);
    }

    public function send(MessageInterface $message)
    {
        $message = $this->handler->handle($message);

        $this->transport->send($message);
    }
}
