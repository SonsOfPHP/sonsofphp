<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock;

/**
 * YYYY
 *
 * Valid range = 0000 to 9999
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface YearInterface
{
    /**
     * Returns the Year as a string
     *
     * @return string
     */
    public function toString(): string;

    /**
     * Returns the Year as an integer
     *
     * @return int
     */
    public function toInt(): int;
}
