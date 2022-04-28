<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Serializer;

use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;

/**
 * Message Serializer Interface
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MessageSerializerInterface
{
    /**
     */
    public function serialize(MessageInterface $message): array;

    /**
     */
    public function deserialize(array $data): MessageInterface;
}
