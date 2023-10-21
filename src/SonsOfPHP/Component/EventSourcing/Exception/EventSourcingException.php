<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Exception;

use Exception;

/**
 * Event Sourcing Exception.
 *
 * This is a general Exception thrown by the library. There needs
 * to be some other specific exceptions that can be used as well.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class EventSourcingException extends \Exception {}
