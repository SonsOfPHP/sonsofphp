<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Mailer;

/**
 * Transport would be like SMTP, SendGrid, etc. Can also have special transports
 * like null or mock, round robin, fallback or failover, and others.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface TransportInterface
{
    /**
     * @throws MailerExceptionInterface
     */
    public function send(MessageInterface $message);
}
