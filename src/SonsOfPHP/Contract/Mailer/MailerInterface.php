<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Mailer;

/**
 * This is the main api developers will interact with.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MailerInterface
{
    public function send(MessageInterface $message);
}
