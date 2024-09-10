<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cookie;

use DateTimeImmutable;
use SonsOfPHP\Component\Cookie\Exception\CookieException;
use SonsOfPHP\Contract\Cookie\CookieInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Cookie implements CookieInterface
{
    public function __construct(
        private string $name,
        private string $value = '',
        private array $attributes = [],
    ) {}

    public function __toString(): string
    {
        return $this->getHeaderValue();
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaderValue(): string
    {
        $cookie = $this->name . '=' . $this->value;

        foreach ($this->attributes as $key => $val) {
            if (is_bool($val) && $val) {
                $cookie .= '; ' . $key;
            }

            if (!is_bool($val)) {
                $cookie .= '; ' . $key . '=' . $val;
            }
        }

        return $cookie;
    }

    /**
     * {@inheritdoc}
     */
    public function withName(string $name): static
    {
        if ($name === $this->name) {
            return $this;
        }

        $that = clone $this;
        $that->name = $name;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withValue(string $value): static
    {
        if ($value === $this->value) {
            return $this;
        }

        $that = clone $this;
        $that->value = $value;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withPath(string $path): static
    {
        if (array_key_exists('Path', $this->attributes) && $path === $this->attributes['Path']) {
            return $this;
        }

        $that = clone $this;
        $that->attributes['Path'] = $path;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withDomain(string $domain): static
    {
        if (array_key_exists('Domain', $this->attributes) && $domain === $this->attributes['Domain']) {
            return $this;
        }

        $that = clone $this;
        $that->attributes['Domain'] = $domain;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withSecure(bool $secure): static
    {
        if (array_key_exists('Secure', $this->attributes) && $secure === $this->attributes['Secure']) {
            return $this;
        }

        $that = clone $this;
        $that->attributes['Secure'] = $secure;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withHttpOnly(bool $httpOnly): static
    {
        if (array_key_exists('HttpOnly', $this->attributes) && $httpOnly === $this->attributes['HttpOnly']) {
            return $this;
        }

        $that = clone $this;
        $that->attributes['HttpOnly'] = $httpOnly;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withSameSite(string $sameSite): static
    {
        if (array_key_exists('SameSite', $this->attributes) && $sameSite === $this->attributes['SameSite']) {
            return $this;
        }

        if (!in_array(strtolower($sameSite), ['none', 'lax', 'strict'])) {
            throw new CookieException('Invalid value for $sameSite');
        }

        $that = clone $this;
        $that->attributes['SameSite'] = $sameSite;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withPartitioned(bool $partitioned): static
    {
        if (array_key_exists('Partitioned', $this->attributes) && $partitioned === $this->attributes['Partitioned']) {
            return $this;
        }

        $that = clone $this;
        $that->attributes['Partitioned'] = $partitioned;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withExpires(DateTimeImmutable $expires): static
    {
        $expires = $expires->format('r');

        if (array_key_exists('Expires', $this->attributes) && $expires === $this->attributes['Expires']) {
            return $this;
        }

        $that = clone $this;
        $that->attributes['Expires'] = $expires;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withMaxAge(int $maxAge): static
    {
        if (array_key_exists('Max-Age', $this->attributes) && $maxAge === $this->attributes['Max-Age']) {
            return $this;
        }

        $that = clone $this;
        $that->attributes['Max-Age'] = $maxAge;

        return $that;
    }
}
