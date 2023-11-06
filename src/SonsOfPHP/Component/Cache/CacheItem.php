<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache;

use Psr\Cache\CacheItemInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class CacheItem implements CacheItemInterface
{
    private mixed $value;
    private int|float|null $expiry = null;

    public function __construct(
        private string $key,
        private bool $isHit = false,
    ) {
        // @todo throw exception for invalid key
    }

    /**
     * {@inheritdoc}
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * {@inheritdoc}
     */
    public function get(): mixed
    {
        return $this->value ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function isHit(): bool
    {
        return $this->isHit;
    }

    /**
     * {@inheritdoc}
     */
    public function set(mixed $value): static
    {
        $this->value = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function expiresAt(?\DateTimeInterface $expiration): static
    {
        $this->expiry = null !== $expiration ? (float) $expiration->format('U.u') : null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function expiresAfter(int|\DateInterval|null $time): static
    {
        if (is_int($time)) {
            $this->expiry = $time + microtime(true);
        } elseif ($time instanceof \DateInterval) {
            $this->expiry = microtime(true) + \DateTimeImmutable::createFromFormat('U', 0)->add($time)->format('U.u');
        } elseif (null === $time) {
            $this->expiry = null;
        }

        return $this;
    }
}
