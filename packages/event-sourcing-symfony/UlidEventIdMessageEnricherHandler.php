<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing;

use SonsOfPHP\Bridge\Symfony\EventSourcing\Message\Enricher\Handler\UlidEventIdMessageEnricherHandler as BaseHandler;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Handler\MessageEnricherHandlerInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use SonsOfPHP\Component\EventSourcing\Metadata;
use Symfony\Component\Uid\Ulid;

/**
 * @deprecated
 */
class UlidEventIdMessageEnricherHandler extends BaseHandler
{
}
