<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Date implements DateInterface
{
    private int $year;
    private int $month;
    private int $day;

    public function __construct(int $year, int $month, int $day)
    {
        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
    }

    /**
     * @see self::toString()
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
        return sprintf('%d-%02d-%02d', $this->year, $this->month, $this->day);
    }

    /**
     * {@inheritdoc}
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * {@inheritdoc}
     */
    public function getMonth(): int
    {
        return $this->month;
    }

    /**
     * {@inheritdoc}
     */
    public function getDay(): int
    {
        return $this->day;
    }
}
