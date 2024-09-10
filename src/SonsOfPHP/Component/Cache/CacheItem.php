<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use Psr\Cache\CacheItemInterface;
use SonsOfPHP\Component\Cache\Exception\InvalidArgumentException;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class CacheItem implements CacheItemInterface
{
    protected mixed $value;

    protected int|float|null $expiry = null;

    public function __construct(
        protected string $key,
        protected bool $isHit = false,
    ) {
        self::validateKey($key);
    }

    public static function validateKey(string $key): void
    {
        if ('' === $key) {
            throw new InvalidArgumentException('$key is empty.');
        }

        if (1 === preg_match('/[\{\}\(\)\/\\\@\:]/', $key)) {
            throw new InvalidArgumentException(sprintf('The key "%s" contains reserved characters', $key));
        }

        if (1 === preg_match('/[^A-Za-z0-9_.]/', $key)) {
            throw new InvalidArgumentException(sprintf('The key "%s" is invalid. Only "A-Z", "a-z", "0-9", "_", and "." are allowed.', $key));
        }
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
    public function expiresAt(?DateTimeInterface $expiration): static
    {
        $this->expiry = $expiration instanceof DateTimeInterface ? (float) $expiration->format('U.u') : null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function expiresAfter(int|DateInterval|null $time): static
    {
        if (is_int($time)) {
            $this->expiry = $time + microtime(true);
        } elseif ($time instanceof DateInterval) {
            $this->expiry = microtime(true) + DateTimeImmutable::createFromFormat('U', '0')->add($time)->format('U.u');
        } elseif (null === $time) {
            $this->expiry = null;
        }

        return $this;
    }
}
