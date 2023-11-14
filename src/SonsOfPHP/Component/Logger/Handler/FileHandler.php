<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Handler;

use SonsOfPHP\Contract\Logger\HandlerInterface;
use SonsOfPHP\Contract\Logger\RecordInterface;

/**
 * Logs messages to a file
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class FileHandler extends AbstractHandler implements HandlerInterface
{
    public function handle(RecordInterface $record): void
    {
        throw new \RuntimeException('To Be Implemented');
    }
}
