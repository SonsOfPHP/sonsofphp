<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Logger;

/**
 * Handlers will handle the log message and record the the information where it
 * needs to go.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface HandlerInterface
{
    /**
     * Handles the Log Record
     *
     * 1) If there is a filter, check to see if the log record is loggable, if
     *    not, it must not be logged
     * 2) If there is a formatter, the message will need to be formatted
     * 3) The log message can be written where it's required with the formatted
     *    message
     */
    public function handle(RecordInterface $record): void;

    /**
     * Will return the filter associated with this Handler. This
     * may return null.
     */
    public function getFilter(): ?FilterInterface;

    public function setFilter(FilterInterface $filter): void;

    public function getFormatter(): ?FormatterInterface;

    public function setFormatter(FormatterInterface $formatter): void;
}
