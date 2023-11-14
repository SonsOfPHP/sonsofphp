<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Handler;

use SonsOfPHP\Contract\Logger\HandlerInterface;
use SonsOfPHP\Contract\Logger\RecordInterface;

/**
 * Sends log messages to stderr/stdout
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class ConsoleHandler extends AbstractHandler implements HandlerInterface
{
    public function handle(RecordInterface $record): void
    {
        throw new \RuntimeException('To Be Implemented');
    }
}
