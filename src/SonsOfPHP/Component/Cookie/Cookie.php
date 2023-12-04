<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cookie;

use SonsOfPHP\Contract\Cookie\CookieInterface;
use SonsOfPHP\Component\Cookie\Exception\CookieException;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Cookie implements CookieInterface
{
    public function __construct(
        private string $name,
        private string $value = '',
        private array $options = [],
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

        foreach ($this->options as $key => $val) {
            if (is_bool($val) && true === $val) {
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
        if (array_key_exists('Path', $this->options) && $path === $this->options['Path']) {
            return $this;
        }

        $that = clone $this;
        $that->options['Path'] = $path;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withDomain(string $domain): static
    {
        if (array_key_exists('Domain', $this->options) && $domain === $this->options['Domain']) {
            return $this;
        }

        $that = clone $this;
        $that->options['Domain'] = $domain;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withSecure(bool $secure): static
    {
        if (array_key_exists('Secure', $this->options) && $secure === $this->options['Secure']) {
            return $this;
        }

        $that = clone $this;
        $that->options['Secure'] = $secure;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withHttpOnly(bool $httpOnly): static
    {
        if (array_key_exists('HttpOnly', $this->options) && $httpOnly === $this->options['HttpOnly']) {
            return $this;
        }

        $that = clone $this;
        $that->options['HttpOnly'] = $httpOnly;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withSameSite(string $sameSite): static
    {
        if (array_key_exists('SameSite', $this->options) && $sameSite === $this->options['SameSite']) {
            return $this;
        }

        if (!in_array(strtolower($sameSite), ['none', 'lax', 'strict'])) {
            throw new CookieException('Invalid value for $sameSite');
        }

        $that = clone $this;
        $that->options['SameSite'] = $sameSite;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withPartitioned(bool $partitioned): static
    {
        if (array_key_exists('Partitioned', $this->options) && $partitioned === $this->options['Partitioned']) {
            return $this;
        }

        $that = clone $this;
        $that->options['Partitioned'] = $partitioned;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withExpires(\DateTimeImmutable $expires): static
    {
        $expires = $expires->format('r');

        if (array_key_exists('Expires', $this->options) && $expires === $this->options['Expires']) {
            return $this;
        }

        $that = clone $this;
        $that->options['Expires'] = $expires;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withMaxAge(int $maxAge): static
    {
        if (array_key_exists('Max-Age', $this->options) && $maxAge === $this->options['Max-Age']) {
            return $this;
        }

        $that = clone $this;
        $that->options['Max-Age'] = $maxAge;

        return $that;
    }
}
