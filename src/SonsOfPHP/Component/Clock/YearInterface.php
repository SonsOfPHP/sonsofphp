<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock;

/**
 * YYYY.
 *
 * Valid range = 0000 to 9999
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface YearInterface
{
    /**
     * Returns the Year as a string.
     */
    public function toString(): string;

    /**
     * Returns the Year as an integer.
     */
    public function toInt(): int;
}
