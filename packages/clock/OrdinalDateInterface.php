<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock;

/**
 * YYYY-DDD
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface OrdinalDateInterface
{
    /**
     * Returns YYYY-DDD
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
     * Returns the day with no leading zeros for the year
     *
     * @return int
     */
    public function getDay(): int;
}
