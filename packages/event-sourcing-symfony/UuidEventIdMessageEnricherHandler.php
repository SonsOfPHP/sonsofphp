<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing;

use SonsOfPHP\Component\EventSourcing\Message\Enricher\Handler\MessageEnricherHandlerInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use SonsOfPHP\Component\EventSourcing\Metadata;
use Symfony\Component\Uid\Uuid;

/**
 * Adds Event ID to metadata.
 *
 * This will set the Event ID to a generated Uuid
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class UuidEventIdMessageEnricherHandler implements MessageEnricherHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function enrich(MessageInterface $message): MessageInterface
    {
        return $message->withMetadata([
            Metadata::EVENT_ID => (string) Uuid::v6(),
        ]);
    }
}
