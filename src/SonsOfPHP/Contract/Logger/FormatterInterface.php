<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Logger;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface FormatterInterface
{
    // returns formatted message
    public function formatMessage(RecordInterface $record): string;
}
