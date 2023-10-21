<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Serializer;

use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;
use SonsOfPHP\Component\EventSourcing\Message\SerializableMessageInterface;

/**
 * Message Serializer Interface.
 *
 * Message Serializer is used on a message before it is saved to storage. It
 * is also used when the message data is pulled out of storage.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MessageSerializerInterface
{
    /**
     * Returns an array.
     *
     * Array Returned will be in the format:
     * [
     *   'payload' => [...],
     *   'metadata' => [...],
     * ]
     *
     * This happens before a Message is stored
     *
     * @throws EventSourcingException
     */
    public function serialize(SerializableMessageInterface $message): array;

    /**
     * Deserialize data pulled from storage and created a Message.
     *
     * @throws EventSourcingException
     */
    public function deserialize(array $data): SerializableMessageInterface;
}
