<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock;

/**
 * YYYY-MM-DD
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface DateInterface
{
    /**
     * Returns the date formated in {Year}-{Month}-{Day}. The date
     * will contain leading zeros for month and day
     *
     * @return string
     */
    public function toString(): string;

    /**
     * Returns the Year
     *
     * ie 2022
     *
     * @return int
     */
    public function getYear(): int;

    /**
     * Returns the Month. It does not have a leading zero
     *
     * ie 8
     *
     * @returns int
     */
    public function getMonth(): int;

    /**
     * Returns the Day. It does not have a leading zero
     *
     * ie 25
     *
     * @return int
     */
    public function getDay(): int;
}
