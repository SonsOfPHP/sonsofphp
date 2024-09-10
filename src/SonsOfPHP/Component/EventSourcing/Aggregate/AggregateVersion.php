<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Aggregate;

use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;

/**
 * Aggregate Version.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final readonly class AggregateVersion implements AggregateVersionInterface
{
    public function __construct(private int $version = 0)
    {
        if (!$this->isValid()) {
            throw new EventSourcingException(sprintf('Version "%s" is invalid.', $this->version));
        }
    }

    public static function fromInt(int $version): AggregateVersionInterface
    {
        return new self($version);
    }

    public static function zero(): AggregateVersionInterface
    {
        return new self(0);
    }

    public function next(): AggregateVersionInterface
    {
        return new self($this->version + 1);
    }

    public function prev(): AggregateVersionInterface
    {
        return new self($this->version - 1);
    }

    public function equals(AggregateVersionInterface $version): bool
    {
        return $this->version === $version->toInt();
    }

    public function toInt(): int
    {
        return $this->version;
    }

    private function isValid(): bool
    {
        return $this->version >= 0;
    }
}
