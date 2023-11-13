<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Logger;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface HandlerInterface
{
    /**
     * Handles the log record
     */
    public function handle(RecordInterface $record): void;
}
