<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Filter;

use SonsOfPHP\Contract\Logger\FilterInterface;
use SonsOfPHP\Contract\Logger\RecordInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class MockFilter implements FilterInterface
{
    public function __construct(
        private readonly bool $isLoggable = true,
    ) {}

    public function isLoggable(RecordInterface $record): bool
    {
        return $this->isLoggable;
    }
}
