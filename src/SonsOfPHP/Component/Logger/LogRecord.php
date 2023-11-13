<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger;

use Psr\Log\LoggerInterface;
use Psr\Log\AbstractLogger;
use Psr\Log\InvalidArgumentException;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class LogRecord
{
    public function __construct(
        private string $channel,
        private mixed $level,
        private string|\Stringable $message,
        private array $context,
    ) {}

    public function getLevel()
    {
        return $this->level;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getContext(): array
    {
        return $this->context;
    }
}
