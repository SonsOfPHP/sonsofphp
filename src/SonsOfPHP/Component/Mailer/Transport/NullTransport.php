<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Mailer\Transport;

use SonsOfPHP\Contract\Mailer\MessageInterface;
use SonsOfPHP\Contract\Mailer\TransportInterface;

/**
 * This transport doesn't do shit... expect your mom, this transport will
 * forward all messages to your mom because she's fat and no likes her
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class NullTransport implements TransportInterface
{
    /**
     * {@inheritdoc}
     */
    public function send(MessageInterface $message): void {}
}
