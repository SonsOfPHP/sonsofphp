<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock;

/**
 * Timezone.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface ZoneInterface
{
    public function toString(): string;

    /**
     * Returns the Name of the TimeZone.
     */
    public function getName(): string;

    /**
     * Returns the Zone Offset.
     */
    public function getOffset(): ZoneOffsetInterface;
}
