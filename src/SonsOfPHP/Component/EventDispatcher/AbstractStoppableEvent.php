<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventDispatcher;

use Psr\EventDispatcher\StoppableEventInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
abstract class AbstractStoppableEvent implements StoppableEventInterface
{
    private bool $isStopped = false;

    public function isPropagationStopped(): bool
    {
        return $this->isStopped;
    }

    /**
     * Makes `isPropagationStopped` return true
     */
    public function stopPropagation(): void
    {
        $this->isStopped = true;
    }
}
