<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cookie;

use SonsOfPHP\Contract\Cookie\CookieInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Cookie implements CookieInterface
{
    public function __construct(
        private string $name,
        private string $value = '',
        private array $options = ['expires' => 0, 'secure' => false, 'httponly' => false],
    ) {}

    public function __toString(): string
    {
        $cookie = $this->name . '=' . $this->value;

        foreach ($this->options as $key => $val) {
            $cookie .= '; ' . $key;
            if (!is_bool($val)) {
                $cookie .= '=' . $val;
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
        if (array_key_exists('path', $this->options) && $path === $this->options['path']) {
            return $this;
        }

        $that = clone $this;
        $that->options['path'] = $path;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withDomain(string $domain): static
    {
        if (array_key_exists('domain', $this->options) && $domain === $this->options['domain']) {
            return $this;
        }

        $that = clone $this;
        $that->options['domain'] = $domain;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withSecure(bool $secure): static
    {
        if (array_key_exists('secure', $this->options) && $secure === $this->options['secure']) {
            return $this;
        }

        $that = clone $this;
        $that->options['secure'] = $secure;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withHttpOnly(bool $httpOnly): static
    {
        if (array_key_exists('httponly', $this->options) && $httpOnly === $this->options['httponly']) {
            return $this;
        }

        $that = clone $this;
        $that->options['httponly'] = $httpOnly;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withSameSite(string $sameSite): static
    {
        if (array_key_exists('samesite', $this->options) && $sameSite === $this->options['samesite']) {
            return $this;
        }

        if (!in_array(strtolower($sameSite), ['none', 'lax', 'strict'])) {
            throw new \InvalidArgumentException('Invalid value for $sameSite');
        }

        $that = clone $this;
        $that->options['samesite'] = $sameSite;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withExpires(\DateTimeImmutable|int|string $expires): static
    {
        if (!is_numeric($expires)) {
            if (false === $expires = strtotime($expires)) {
                throw new \InvalidArgumentException('$expires is invalid');
            }
        } elseif ($expires instanceof \DateTimeImmutable) {
            $expires = $expires->format('U');
        }

        if (array_key_exists('expires', $this->options) && $expires === $this->options['expires']) {
            return $this;
        }

        $that = clone $this;
        $that->options['expires'] = $expires;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    //public function send(bool $raw = false): void
    //{
    //    if (true === $raw) {
    //        // raw dog those values
    //        setrawcookie($this->name, $this->value, $this->options);
    //        return;
    //    }

    //    setcookie($this->name, $this->value, $this->options);
    //}
}
