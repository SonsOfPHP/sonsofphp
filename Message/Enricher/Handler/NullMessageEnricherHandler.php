<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Enricher\Handler;

use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;

/**
 * Null Enricher.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class NullMessageEnricherHandler implements MessageEnricherHandlerInterface
{
    public function enrich(MessageInterface $message): MessageInterface
    {
        return $message;
    }
}
