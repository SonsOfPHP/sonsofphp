<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Upcaster;

use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;

/**
 * Message Upcaster
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MessageUpcasterInterface
{
    /**
     */
    public function upcast(MessageInterface $message): MessageInterface;
}
