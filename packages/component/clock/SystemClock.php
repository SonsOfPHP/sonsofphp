<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

/**
 * System Clock
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class SystemClock implements ClockInterface
{
    private DateTimeZone $timezone;

    /**
     */
    public function __construct(?DateTimeZone $timezone = null)
    {
        $this->timezone = $timezone ?? new DateTimeZone('UTC');
    }

    /**
     * {@inheritdoc}
     */
    public function now(): DateTimeInterface
    {
        return new DateTimeImmutable('now', $this->timezone);
    }

    /**
     * @return DateTimeZone
     */
    public function timezone(): DateTimeZone
    {
        return $this->timezone;
    }
}
