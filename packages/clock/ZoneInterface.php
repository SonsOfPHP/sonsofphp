<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock;

/**
 * Timezone
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface ZoneInterface
{
    /**
     * @return string
     */
    public function toString(): string;

    /**
     * Returns the Name of the TimeZone
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Returns the Zone Offset
     *
     * @return ZoneOffsetInterface
     */
    public function getOffset(): ZoneOffsetInterface;
}
