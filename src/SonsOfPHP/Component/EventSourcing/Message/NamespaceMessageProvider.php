<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message;

use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;

/**
 * Namespace Provider.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class NamespaceMessageProvider implements MessageProviderInterface
{
    public function __construct(private readonly string $namespace) {}

    public function getEventTypeForMessage($message): string
    {
        if (\is_object($message)) {
            $class = $message::class;
            if (!$message instanceof MessageInterface) {
                throw new EventSourcingException(sprintf('Message "%s" does not implement "%s"', $class, MessageInterface::class));
            }

            $message = $class;
        }

        $eventType = trim(substr($message, \strlen($this->namespace)), '\\');

        if (!str_starts_with($message, $this->namespace)) {
            throw new EventSourcingException(sprintf('Message "%s" is not in the Namespace "%s"', $message, $this->namespace));
        }

        return $eventType;
    }

    public function getMessageClassForEventType(string $eventType): string
    {
        $fqcn = $this->namespace . '\\' . $eventType;

        if (!class_exists($fqcn)) {
            throw new EventSourcingException(sprintf('Could not find "%s" for event "%s"', $fqcn, $eventType));
        }

        return $fqcn;
    }
}
