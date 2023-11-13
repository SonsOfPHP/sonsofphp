<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger;

use Psr\Log\LoggerInterface;
use Psr\Log\AbstractLogger;
use Psr\Log\InvalidArgumentException;
use SonsOfPHP\Contract\Logger\ContextInterface;
use SonsOfPHP\Contract\Logger\RecordInterface;
use SonsOfPHP\Contract\Logger\LevelInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Record implements RecordInterface
{
    public function __construct(
        private string $channel,
        private LevelInterface $level,
        private string|\Stringable $message,
        private ContextInterface $context,
        private \DateTimeImmutable $datetime = new \DateTimeImmutable(),
    ) {}

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function withChannel(string $channel): static
    {
        if ($this->channel === $channel) {
            return $this;
        }

        $that = clone $this;
        $that->channel = $channel;

        return $that;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function withLevel(LevelInterface $level): static
    {
        if ($this->level === $level) {
            return $this;
        }

        $that = clone $this;
        $that->level = $level;

        return $that;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function withMessage(string $message): static
    {
        if ($this->message === $message) {
            return $this;
        }

        $that = clone $this;
        $that->message = $message;

        return $that;
    }

    public function getContext(): ContextInterface
    {
        return $this->context;
    }

    public function withContext(array|ContextInterface $context): static
    {
        if (is_array($context)) {
            $context = new Context($context);
        }

        if ($this->context->all() === $context->all()) {
            return $this;
        }

        $that = clone $this;
        $that->context = $context;

        return $that;
    }

    public function getDatetime()
    {
        return $this->datetime;
    }

    public function withDatetime(\DateTimeImmutable $datetime): static
    {
        $that = clone $this;
        $that->datatime = $datatime;

        return $that;
    }
}
