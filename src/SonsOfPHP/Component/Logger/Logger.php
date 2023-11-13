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
        $this->handlers[] = $handler;
    }

    /**
     * {@inheritdoc}
     */
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $record = new Record(
            channel: $this->channel,
            level: Level::from($level),
            message: (string) $message,
            context: new Context($context),
        );

        foreach ($this->handlers as $handler) {
            foreach ($this->enrichers as $enricher) {
                $enricher->enrich($record);
            }

            $handler->handle($record);
        }
    }
}
