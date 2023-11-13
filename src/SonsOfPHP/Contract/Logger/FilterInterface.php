<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Logger;

/**
 * A filter can be set on the Logger and Handler level. If the Record is
 * not loggabled, it will not be handled.
 *
 * If the filter is set on the logger level, and is un-loggable, no
 * enrichers or handlers will be called.
 *
 * If the filter is set on the handler level, and is un-loggable, it will
 * must not be handled
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface FilterInterface
{
    /**
     * Returns true if this record is loggable.
     */
    public function isLoggable(RecordInterface $record): bool;
}
