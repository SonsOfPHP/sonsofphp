<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing;

use SonsOfPHP\Bridge\Symfony\EventSourcing\Message\Enricher\Handler\HttpRequestMessageEnricherHandler as BaseHandler;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Handler\MessageEnricherHandlerInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @deprecated
 */
class HttpRequestMessageEnricherHandler extends BaseHandler
{
}
