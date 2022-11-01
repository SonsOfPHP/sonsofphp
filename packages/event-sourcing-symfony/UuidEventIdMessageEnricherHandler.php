<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing;

use SonsOfPHP\Bridge\Symfony\EventSourcing\Message\Enricher\Handler\UuidEventIdMessageEnricherHandler as BaseHandler;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Handler\MessageEnricherHandlerInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use SonsOfPHP\Component\EventSourcing\Metadata;
use Symfony\Component\Uid\Uuid;

/**
 * @deprecated
 */
class UuidEventIdMessageEnricherHandler extends BaseHandler
{
}
