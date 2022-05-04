<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock;

/**
 * hh:mm:ss.sss
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface TimeInterface
{
    /**
     * Returns the time in the format {Hour}-{Minute}-{Second}.{Millisecond}
     * with leading zeros.
     *
     * @return string
     */
    public function toString(): string;

    /**
     * Returns the 24-Hour without a leading zero
     *
     * @return int
     */
    public function getHour(): int;

    /**
     * Returns the Minute without a leading zero
     *
     * @return int
     */
    public function getMinute(): int;

    /**
     * Returns the Second without a leading zero
     *
     * @return int
     */
    public function getSecond(): int;

    /**
     * Returns the Millisecond without a leading zero
     *
     * @return int
     */
    public function getMillisecond(): int;
}
