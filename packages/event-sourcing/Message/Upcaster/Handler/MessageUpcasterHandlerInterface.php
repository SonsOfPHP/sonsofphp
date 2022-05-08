<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Upcaster\Handler;

use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;

/**
 * Message Upcaster Handler Interface
 *
 * Handlers will do the actual data transformation and return the
 * upcasted data.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MessageUpcasterHandlerInterface
{
    /**
     * The Event Data will be passed in and the upcasted Event Data will
     * be output.
     *
     * @param array $eventData
     * @return array
     */
    public function upcast(array $eventData): array;
}
