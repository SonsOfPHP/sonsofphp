<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class Context// implements \ArrayAccess
{
    public function __construct(
        private array $context = [],
    ) {}

    public function all(): array
    {
        return $this->context;
    }
}
