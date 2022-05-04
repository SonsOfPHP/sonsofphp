<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock;

//use DateTimeInterface as BaseDateTimeInterface;

/**
 * Date Time with Zone
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface DateTimeInterface// extends BaseDateTimeInterface
{
    /**
     * Returns the DateTime in ISO 8601 format
     *
     * YYYY-MM-DDTHH:MM:SS.SSS+00:00
     *
     * @return string
     */
    public function toString(): string;

    /**
     * Returns a Date object
     *
     * @return DateInterface
     */
    public function getDate(): DateInterface;

    /**
     * Returns a Time object
     *
     * @return TimeInterface
     */
    public function getTime(): TimeInterface;

    /**
     * Returns the Timezone
     *
     * @return ZoneInterface
     */
    public function getZone(): ZoneInterface;
}
