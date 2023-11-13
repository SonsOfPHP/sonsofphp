<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Formatter;

use SonsOfPHP\Contract\Logger\FormatterInterface;
use SonsOfPHP\Contract\Logger\RecordInterface;

/**
 * Sends log messages to stderr/stdout
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class SimpleFormater implements FormatterInterface
{
    public function formatMessage(RecordInterface $record): string
    {
        $message = '[' . $record->getChannel() . ']' . '[' . $record->getLevel()->getName() . ']' . $record->getMessage();

        foreach ($record->getContext() as $key => $value) {
            if (!is_array($value) && (!is_object($value) || method_exists($value, '__toString'))) {
                $message = strtr($message, '{' . $key . '}', $value);
            }
        }

        return $message;
    }
}
