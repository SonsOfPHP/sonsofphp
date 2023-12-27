<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventDispatcher;

use Psr\EventDispatcher\StoppableEventInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
abstract class AbstractStoppableEvent implements StoppableEventInterface
{
    use StoppableEventTrait;
}
