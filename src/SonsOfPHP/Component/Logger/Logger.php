<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger;

use SonsOfPHP\Contract\Logger\HandlerInterface;
use SonsOfPHP\Contract\Logger\EnricherInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\AbstractLogger;
use Psr\Log\InvalidArgumentException;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class Logger extends AbstractLogger implements LoggerInterface
{
    public function __construct(
        private string $channel = '',
        private array $handlers = [],
        private array $enrichers = [],
    ) {}

    public function pushHandler(HandlerInterface $handler): void
    {
        $this->handlers[] = $handler;
    }

    public function pushEnricher(EnricherInterface $enricher): void
    {
        $this->enrichers[] = $enricher;
    }

    /**
     * {@inheritdoc}
     */
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        if (null === Level::tryFrom($level)) {
            throw new InvalidArgumentException('level is invalid');
        }

        $record = new Record(
            channel: $this->channel,
            level: Level::from($level),
            message: (string) $message,
            context: new Context($context),
        );

        foreach ($this->enrichers as $enricher) {
            $record = $enricher($record);
        }

        foreach ($this->handlers as $handler) {
            $handler->handle($record);
        }
    }
}
