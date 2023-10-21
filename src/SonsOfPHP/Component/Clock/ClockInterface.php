<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock;

use DateTimeInterface;

/**
 * Clock Interface.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface ClockInterface
{
    /**
     * Returns a DateTimeInterface.
     */
    public function now(): \DateTimeInterface;
}
