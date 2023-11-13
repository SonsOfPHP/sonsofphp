<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger;

use Psr\Log\LoggerInterface;
use Psr\Log\AbstractLogger;
use Psr\Log\InvalidArgumentException;
use SonsOfPHP\Contract\Logger\ContextInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class Record
{
    public function __construct(
        private string $channel,
        private Level $level,
        private string|\Stringable $message,
        private ContextInterface $context,
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
