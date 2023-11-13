<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Handler;

use SonsOfPHP\Contract\Logger\HandlerInterface;

/**
 * The handler that says, "fuck your message"
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class NullHandler implements HandlerInterface
{
    public function handle($logRecord)
    {
    }
}
