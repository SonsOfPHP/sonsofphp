<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Handler;

use RuntimeException;
use SonsOfPHP\Contract\Logger\RecordInterface;

/**
 * Logs messages to a file
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class FileHandler extends AbstractHandler
{
    private bool $isOpen = false;
    private $handle;

    public function __construct(
        private readonly string $filename,
    ) {}

    public function doHandle(RecordInterface $record, string $message): void
    {
        $this->open();

        $this->write($message);
    }

    private function write(string $message): void
    {
        if (false === fwrite($this->handle, $message)) {
            throw new RuntimeException(sprintf('"%s" could not be written to', $this->filename));
        }
    }

    private function open(): void
    {
        if ($this->isOpen) {
            return;
        }

        if (false === $this->handle = fopen($this->filename, 'a')) {
            throw new RuntimeException(sprintf('"%s" could not be opened', $this->filename));
        }
        $this->isOpen = true;
    }

    private function close(): void
    {
        if (!$this->isOpen) {
            return;
        }

        fclose($this->handle);
        $this->isOpen = false;
    }

    public function __destruct()
    {
        $this->close();
    }
}
