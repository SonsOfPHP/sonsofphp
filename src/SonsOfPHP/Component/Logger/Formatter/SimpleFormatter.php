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
class SimpleFormatter implements FormatterInterface
{
    public function formatMessage(RecordInterface $record): string
    {
        $output  = "[%datetime%] %channel%.%level_name%: %message% %context%\n";
        $message = $record->getMessage();
        foreach ($record->getContext() as $key => $value) {
            if (!is_array($value) && (!is_object($value) || method_exists($value, '__toString'))) {
                $message = str_replace('{' . $key . '}', $value, $message);
            }
        }

        $output = str_replace('%datetime%', $record->getDatetime()->format('c'), $output);
        $output = str_replace('%channel%', $record->getChannel(), $output);
        $output = str_replace('%level_name%', $record->getLevel()->getName(), $output);
        $output = str_replace('%message%', $message, $output);
        $output = str_replace('%context%', json_encode($record->getContext()->all()), $output);

        return $output;
    }
}
