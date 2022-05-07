<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Aggregate;

use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;

/**
 * Aggregate Version
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class AggregateVersion implements AggregateVersionInterface
{
    private int $version;

    /**
     * @param int $version
     */
    private function __construct(int $version)
    {
        $this->version = $version;

        if (!$this->isValid()) {
            throw new EventSourcingException(sprintf('Version "%s" is invalid.', $this->version));
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function fromInt(int $version): AggregateVersionInterface
    {
        return new static($version);
    }

    /**
     * {@inheritdoc}
     */
    public static function zero(): AggregateVersionInterface
    {
        return new static(0);
    }

    /**
     * {@inheritdoc}
     */
    public function next(): AggregateVersionInterface
    {
        return new static($this->version + 1);
    }

    /**
     * {@inheritdoc}
     */
    public function prev(): AggregateVersionInterface
    {
        return new static($this->version - 1);
    }

    /**
     * {@inheritdoc}
     */
    public function equals(AggregateVersionInterface $version): bool
    {
        return $this->version === $version->toInt();
    }

    /**
     * {@inheritdoc}
     */
    public function toInt(): int
    {
        return $this->version;
    }

    /**
     * @return bool
     */
    private function isValid(): bool
    {
        return $this->version >= 0;
    }
}
