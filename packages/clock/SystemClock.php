<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

/**
 * System Clock.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class SystemClock implements ClockInterface
{
    private DateTimeZone $zone;

    public function __construct(?DateTimeZone $zone = null)
    {
        $this->zone = $zone ?? new DateTimeZone('UTC');
    }

    public function __toString(): string
    {
        return 'SystemClock['.$this->zone->getName().']';
    }

    /**
     * {@inheritdoc}
     */
    public function now(): DateTimeInterface
    {
        return new DateTimeImmutable('now', $this->zone);
    }

    public function getZone(): DateTimeZone
    {
        return $this->zone;
    }
}
