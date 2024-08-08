<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Handler;

use RuntimeException;
use SonsOfPHP\Contract\Logger\RecordInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class StreamHandler extends AbstractHandler
{
    private bool $isOpen = false;

    public function __construct(private $stream) {}

    public function doHandle(RecordInterface $record, string $message): void
    {
        $this->write($message);
    }

    private function write(string $message): void
    {
        if (false === fwrite($this->stream, $message)) {
            throw new RuntimeException(sprintf('stream could not be written to'));
        }
    }
}
