<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Serializer;

use SonsOfPHP\Component\EventSourcing\Message\SerializableMessageInterface;

/**
 * Message Serializer Interface
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MessageSerializerInterface
{
    /**
     * Returns an array
     *
     * Array Returned will be in the format:
     * [
     *   'payload' => [...],
     *   'metadata' => [...],
     * ]
     *
     * @return array
     */
    public function serialize(SerializableMessageInterface $message): array;

    /**
     */
    public function deserialize(array $data): SerializableMessageInterface;
}
