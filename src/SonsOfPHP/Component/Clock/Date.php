<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock;

use Stringable;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final readonly class Date implements DateInterface, Stringable
{
    public function __construct(private int $year, private int $month, private int $day) {}

    /**
     * @see self::toString()
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return sprintf('%d-%02d-%02d', $this->year, $this->month, $this->day);
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getMonth(): int
    {
        return $this->month;
    }

    public function getDay(): int
    {
        return $this->day;
    }
}
