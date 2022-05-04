<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock;

/**
 * YYYY-W00
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface YearWeekInterface
{
    /**
     * Returns Week in the format {Year}-W{Week}
     *
     * @return string
     */
    public function toString(): string;

    /**
     * Returns the Year
     *
     * @return int
     */
    public function getYear(): int;

    /**
     * Returns the Week
     *
     * @return int
     */
    public function getWeek(): int;
}
