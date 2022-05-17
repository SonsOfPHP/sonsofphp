<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock;

/**
 * YYYY-DDD.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface OrdinalDateInterface
{
    /**
     * Returns YYYY-DDD.
     */
    public function toString(): string;

    /**
     * Returns the Year.
     */
    public function getYear(): int;

    /**
     * Returns the day with no leading zeros for the year.
     */
    public function getDay(): int;
}
