<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Upcaster;

/**
 * Message Upcaster.
 *
 * This is the main Message Upcaster, it will take a Provider which will
 * return the Handlers that need to be used.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MessageUpcasterInterface
{
    public function upcast(array $data): array;
}
