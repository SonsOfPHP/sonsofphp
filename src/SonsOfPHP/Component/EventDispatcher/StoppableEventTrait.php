<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventDispatcher;

use Psr\EventDispatcher\StoppableEventInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
trait StoppableEventTrait
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
