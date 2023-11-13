<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Handler;

use SonsOfPHP\Contract\Logger\HandlerInterface;
use SonsOfPHP\Contract\Logger\RecordInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class SocketHandler extends AbstractHandler implements HandlerInterface
{
    public function handle(RecordInterface $record): void
    {
    }
}