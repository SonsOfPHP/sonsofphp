<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock;

/**
 * Timezone Offset
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface ZoneOffsetInterface
{
    /**
     * Returns the offset int the format of {+/-}{Hours}:{Minutes} with leading
     * zeros
     *
     * Example: -05:30
     *
     * @return string
     */
    public function toString(): string;

    /**
     * Returns the number of seconds, can be positive or negative
     *
     * @return int
     */
    public function getSeconds(): int;
}
