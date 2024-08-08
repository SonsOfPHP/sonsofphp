<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Handler;

use SonsOfPHP\Contract\Logger\RecordInterface;

/**
 * The handler that says, "fuck your message"
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class NullHandler extends AbstractHandler
{
    public function doHandle(RecordInterface $record, string $message): void {}
}
