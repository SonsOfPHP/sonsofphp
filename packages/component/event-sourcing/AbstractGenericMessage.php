<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing;

/**
 * Abstract Generic Domain Event Message
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
abstract class AbstractGenericMessage extends AbstractMessage implements SerializableMessageInterface
{
    private array $payload = [];

    /**
     */
    final public function withPayload(array $payload): MessageInterface
    {
        $that = clone $this;
        $that->payload = array_merge($this->payload, $payload);

        return $that;
    }

    /**
     */
    final public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     */
    final public function serialize(): array
    {
        return [
            'payload'  => $this->getPayload(),
            'metadata' => $this->getMetadata(),
        ];
    }

    /**
     */
    final public static function deserialize(array $data): SerializableMessageInterface
    {
        if (!isset($data['payload']) || !isset($data['metadata'])) {
            throw new EventSourcingException('Serialized Data does not contain "payload" and/or "metadata"');
        }

        return static::new()->withPayload($data['payload'])->withMetadata($data['metadata']);
    }
}
