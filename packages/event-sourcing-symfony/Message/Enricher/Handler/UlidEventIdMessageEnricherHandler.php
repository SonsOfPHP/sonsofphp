<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing\Message\Enricher\Handler;

use SonsOfPHP\Component\EventSourcing\Message\Enricher\Handler\MessageEnricherHandlerInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use SonsOfPHP\Component\EventSourcing\Metadata;
use Symfony\Component\Uid\Ulid;

/**
 * Set the Event ID to a ULID
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class UlidEventIdMessageEnricherHandler implements MessageEnricherHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function enrich(MessageInterface $message): MessageInterface
    {
        return $message->withMetadata([
            Metadata::EVENT_ID => (string) new Ulid(),
        ]);
    }
}
