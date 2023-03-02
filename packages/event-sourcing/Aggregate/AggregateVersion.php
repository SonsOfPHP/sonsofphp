<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Aggregate;

use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;

/**
 * Aggregate Version.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class AggregateVersion implements AggregateVersionInterface
{
    private int $version;

    public function __construct(int $version = 0)
    {
        $this->version = $version;

        if (!$this->isValid()) {
            throw new EventSourcingException(sprintf('Version "%s" is invalid.', $this->version));
        }
    }

    /**
     * {@inheritDoc}
     */
    public static function fromInt(int $version): AggregateVersionInterface
    {
        return new static($version);
    }

    /**
     * {@inheritDoc}
     */
    public static function zero(): AggregateVersionInterface
    {
        return new static(0);
    }

    /**
     * {@inheritDoc}
     */
    public function next(): AggregateVersionInterface
    {
        return new static($this->version + 1);
    }

    /**
     * {@inheritDoc}
     */
    public function prev(): AggregateVersionInterface
    {
        return new static($this->version - 1);
    }

    /**
     * {@inheritDoc}
     */
    public function equals(AggregateVersionInterface $version): bool
    {
        return $this->version === $version->toInt();
    }

    /**
     * {@inheritDoc}
     */
    public function toInt(): int
    {
        return $this->version;
    }

    private function isValid(): bool
    {
        return $this->version >= 0;
    }
}
