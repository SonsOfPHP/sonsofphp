<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger;

use Psr\Log\LoggerInterface;
use Psr\Log\AbstractLogger;
use Psr\Log\InvalidArgumentException;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class Logger extends AbstractLogger implements LoggerInterface
{
    public function __construct(
        private string $channel,
        private array $handlers = [],
        private array $enrichers = [],
    ) {}

    public function pushHandler($handler): void
    {
        $this->handlers[] = $handler;
    }

    /**
     * {@inheritdoc}
     */
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        foreach ($this->handlers as $handler) {
            foreach ($this->enrichers as $enricher) {
                $enricher->enrich();
            }
            $handler->handle($level, $message, $context);
        }
    }
}
