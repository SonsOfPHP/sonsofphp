<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Filter;

use InvalidArgumentException;
use SonsOfPHP\Component\Logger\Level;
use SonsOfPHP\Contract\Logger\FilterInterface;
use SonsOfPHP\Contract\Logger\LevelInterface;
use SonsOfPHP\Contract\Logger\RecordInterface;

/**
 * Only log the message if it's $level or higher
 *
 * Setting this to Alert level only allow Alert and Emerengency level logs to
 * be logged
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class LogLevelFilter implements FilterInterface
{
    private readonly Level $level;

    public function __construct(string|LevelInterface $level = Level::Debug)
    {
        if (is_string($level) && !($level = Level::tryFromName($level)) instanceof LevelInterface) {
            throw new InvalidArgumentException(sprintf('The level "%s" is invalid', $level));
        }

        $this->level = $level;
    }

    public function isLoggable(RecordInterface $record): bool
    {
        return $this->level->includes($record->getLevel());
    }
}
