<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Mailer\Transport;

use SonsOfPHP\Contract\Mailer\MessageInterface;
use SonsOfPHP\Contract\Mailer\TransportInterface;

/**
 * Bullshit Transport that uses `mail`. Don't wanna use this in production, but
 * I'm sure you will anyway.
 *
 * @see https://www.php.net/mail
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class NativeMailTransport implements TransportInterface
{
    /**
     * {@inheritdoc}
     */
    public function send(MessageInterface $message): void
    {
        mail($message->getHeader('to'), $message->getHeader('subject'), $message->getBody(), $message->getHeaders());
    }
}
