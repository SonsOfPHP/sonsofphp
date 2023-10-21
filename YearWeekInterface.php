<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock;

/**
 * YYYY-W00.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface YearWeekInterface
{
    /**
     * Returns Week in the format {Year}-W{Week}.
     */
    public function toString(): string;

    /**
     * Returns the Year.
     */
    public function getYear(): int;

    /**
     * Returns the Week.
     */
    public function getWeek(): int;
}
