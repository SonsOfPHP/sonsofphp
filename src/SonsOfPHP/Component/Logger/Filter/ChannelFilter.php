<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Filter;

use SonsOfPHP\Contract\Logger\FilterInterface;
use SonsOfPHP\Contract\Logger\RecordInterface;

/**
 * Only allow logs based on the channel name. For example, if the channel is
 * api, we can exclude those log messages.
 *
 * Example:
 *   new ChannelFilter('api'); // Log if channel is "api"
 *   new ChannelFilter('api', false); // Log if channel is not "api"
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class ChannelFilter implements FilterInterface
{
    public function __construct(
        private string $channel,
        private bool $isLoggable = true,
    ) {}

    public function isLoggable(RecordInterface $record): bool
    {
        if ($this->isLoggable) {
            // Log ONLY if channels match
            return $this->channel === $record->getChannel();
        }

        // Log ONLY if channels DO NOT match
        return $this->channel !== $record->getChannel();
    }
}
