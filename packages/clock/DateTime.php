<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class DateTime implements DateTimeInterface
{
    private DateInterface $date;
    private TimeInterface $time;
    private ZoneInterface $zone;

    /**
     * @param DateInterface $date
     * @param TimeInterface $time
     * @param ZoneInterface $zone
     */
    public function __construct(DateInterface $date, TimeInterface $time, ZoneInterface $zone)
    {
        $this->date = $date;
        $this->time = $time;
        $this->zone = $zone;
    }

    /**
     * @see self::toString()
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return sprintf(
            '%sT%s%s',
            $this->getDate(),
            $this->getTime(),
            $this->getZone()->getOffset()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getDate(): DateInterface
    {
        return $this->date;
    }

    /**
     * {@inheritdoc}
     */
    public function getTime(): TimeInterface
    {
        return $this->time;
    }

    /**
     * {@inheritdoc}
     */
    public function getZone(): ZoneInterface
    {
        return $this->zone;
    }
}
