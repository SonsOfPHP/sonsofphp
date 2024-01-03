<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Mailer;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MiddlewareStackInterface
{
    /**
     * @throw MailerExceptionInterface
     */
    public function next(): MiddlewareInterface;
}
