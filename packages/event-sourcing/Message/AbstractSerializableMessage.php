<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message;

use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
abstract class AbstractSerializableMessage extends AbstractMessage implements SerializableMessageInterface
{
    /**
     * {@inheritdoc}
     */
    final public function serialize(): array
    {
        return [
            'payload' => $this->getPayload(),
            'metadata' => $this->getMetadata(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    final public static function deserialize(array $data): SerializableMessageInterface
    {
        if (!isset($data['payload']) || !isset($data['metadata'])) {
            throw new EventSourcingException('Serialized Data does not contain "payload" and/or "metadata"');
        }

        return static::new()->withPayload($data['payload'])->withMetadata($data['metadata']);
    }
}
