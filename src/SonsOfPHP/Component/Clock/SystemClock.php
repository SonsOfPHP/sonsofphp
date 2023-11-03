<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock;

use Psr\Clock\ClockInterface;

/**
 * System Clock.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class SystemClock implements ClockInterface
{
    public function __construct(private ?\DateTimeZone $zone = null)
    {
        $this->zone = $zone ?? new \DateTimeZone('UTC');
    }

    public function __toString(): string
    {
        return 'SystemClock[' . $this->zone->getName() . ']';
    }

    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('now', $this->zone);
    }

    public function getZone(): \DateTimeZone
    {
        return $this->zone;
    }
}
