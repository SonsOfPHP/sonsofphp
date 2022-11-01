<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing;

use SonsOfPHP\Bridge\Symfony\EventSourcing\Message\Enricher\Handler\BlameableMessageEnricherHandler as BaseHandler;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Handler\MessageEnricherHandlerInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @deprecated
 */
class BlameableMessageEnricherHandler extends BaseHandler
{
}
