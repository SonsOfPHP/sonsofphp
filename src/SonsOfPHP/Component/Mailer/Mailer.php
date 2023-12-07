<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Mailer;

use SonsOfPHP\Contract\Mailer\MailerInterface;
use SonsOfPHP\Contract\Mailer\TransportInterface;
use SonsOfPHP\Contract\Mailer\MessageInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Mailer implements MailerInterface
{
    public function __construct(private TransportInterface $transport) {}

    public function send(MessageInterface $message)
    {
        // message enrichers?
        // Before a message is sent, should there be some enrichers that will
        // be able to minipulate this before it's sent? Example could be extra
        // Headers

        $this->transport->send($message);
    }
}
