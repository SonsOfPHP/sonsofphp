<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Enricher;

use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;

/**
 * Message Enricher
 *
 * This will take a Message Enricher Provider and will enrich the message.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MessageEnricherInterface
{
    /**
     */
    public function enrich(MessageInterface $message): MessageInterface;
}