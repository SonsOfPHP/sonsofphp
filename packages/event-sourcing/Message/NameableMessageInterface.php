<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message;

/**
 * Nameable Message.
 *
 * This can be used to give a message a name such as "product.created". This makes
 * it a little easier to manage messages.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface NameableMessageInterface
{
    /**
     * Returns the message name, this could be something like
     * "product.published" or something similar.
     */
    public function getMessageName(): string;
}
