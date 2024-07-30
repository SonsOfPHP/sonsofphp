<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock;

use Stringable;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Zone implements ZoneInterface, Stringable
{
    public function __construct(
        private readonly string $name,
        private readonly ZoneOffsetInterface $offset,
    ) {}
    public function __toString(): string
    {
        return $this->toString();
    }
    public function toString(): string
    {
        return $this->name;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getOffset(): ZoneOffsetInterface
    {
        return $this->offset;
    }
}
