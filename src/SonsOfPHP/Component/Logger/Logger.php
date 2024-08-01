<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger;

use Psr\Log\AbstractLogger;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use SonsOfPHP\Contract\Logger\EnricherInterface;
use SonsOfPHP\Contract\Logger\FilterInterface;
use SonsOfPHP\Contract\Logger\HandlerInterface;
use SonsOfPHP\Contract\Logger\LevelInterface;
use Stringable;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class Logger extends AbstractLogger implements LoggerInterface
{
    public function __construct(
        private readonly string $channel = '',
        private array $handlers = [],
        private array $enrichers = [],
        private readonly ?FilterInterface $filter = null,
    ) {}

    public function addHandler(HandlerInterface $handler): void
    {
        $this->handlers[] = $handler;
    }

    public function addEnricher(EnricherInterface $enricher): void
    {
        $this->enrichers[] = $enricher;
    }

    /**
     * {@inheritdoc}
     */
    public function log($level, string|Stringable $message, array $context = []): void
    {
        if (!Level::tryFromName((string) $level) instanceof LevelInterface) {
            throw new InvalidArgumentException('level is invalid');
        }

        $record = new Record(
            channel: $this->channel,
            level: Level::fromName($level),
            message: (string) $message,
            context: new Context($context),
        );

        if ($this->filter instanceof FilterInterface && false === $this->filter->isLoggable($record)) {
            return;
        }

        foreach ($this->enrichers as $enricher) {
            $record = $enricher($record);
        }

        foreach ($this->handlers as $handler) {
            $handler->handle($record);
        }
    }
}
