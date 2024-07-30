<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock;

use Stringable;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class DateTime implements DateTimeInterface, Stringable
{
    public function __construct(private readonly DateInterface $date, private readonly TimeInterface $time, private readonly ZoneInterface $zone) {}
    /**
     * @see self::toString()
     */
    public function __toString(): string
    {
        return $this->toString();
    }
    public function toString(): string
    {
        return sprintf(
            '%sT%s%s',
            $this->getDate(),
            $this->getTime(),
            $this->getZone()->getOffset()
        );
    }
    public function getDate(): DateInterface
    {
        return $this->date;
    }
    public function getTime(): TimeInterface
    {
        return $this->time;
    }
    public function getZone(): ZoneInterface
    {
        return $this->zone;
    }
}
