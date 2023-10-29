<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpMessage;

use Psr\Http\Message\UriInterface;

/**
 * {@inheritdoc}
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class Uri implements UriInterface, \Stringable
{
    private string $scheme;
    private string $host;
    private ?string $path;
    private ?int $port;
    private ?string $user;
    private ?string $password;
    private ?string $query;
    private ?string $fragment;

    public function __construct(
        private string $uri = '',
    ) {
        if ('' !== $uri) {
            $parts = parse_url($uri);

            $this->scheme   = isset($parts['scheme']) ? strtolower($parts['scheme']) : '';
            $this->user     = $parts['user'] ?? null;
            $this->password = $parts['pass'] ?? null;
            $this->host     = isset($parts['host']) ? strtolower($parts['host']) : '';
            $this->port     = $parts['port'] ?? null;
            $this->path     = $parts['path'] ?? null;
            $this->query    = $parts['query'] ?? null;
            $this->fragment = $parts['fragment'] ?? null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthority(): string
    {
        $authority = $this->getHost();
        if ('' !== $userInfo = $this->getUserInfo()) {
            $authority = $this->getUserInfo() . '@' . $authority;
        }

        if (null !== $this->port) {
            $authority = $authority . ':' . $this->port;
        }

        return $authority;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserInfo(): string
    {
        if (null === $this->user && null === $this->password) {
            return '';
        }

        return trim($this->user . ':' . $this->password, ':');
    }

    /**
     * {@inheritdoc}
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * {@inheritdoc}
     */
    public function getPort(): ?int
    {
        if (null !== $this->port) {
            return $this->port;
        }

        return match($this->getScheme()) {
            'http' => 80,
            'https' => 443,
            default => null,
        };
    }

    /**
     * {@inheritdoc}
     */
    public function getPath(): string
    {
        return $this->path ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery(): string
    {
        return $this->query ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function getFragment(): string
    {
        return $this->fragment ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function withScheme(string $scheme): UriInterface
    {
        $scheme = strtolower($scheme);

        if ($scheme === $this->scheme) {
            return $this;
        }

        $that = clone $this;

        $that->scheme = $scheme;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withUserInfo(string $user, ?string $password = null): UriInterface
    {
        if ($user === $this->user && $password === $this->password) {
            return $this;
        }

        $that = clone $this;

        $that->user     = $user;
        $that->password = $password;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withHost(string $host): UriInterface
    {
        $host = strtolower($host);

        if ($host === $this->host) {
            return $this;
        }

        $that = clone $this;

        $that->host = $host;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withPort(?int $port): UriInterface
    {
        if ($port === $this->getPort()) {
            return $this;
        }

        $that = clone $this;

        $that->port = $port;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withPath(string $path): UriInterface
    {
        if ($path === $this->getPath()) {
            return $this;
        }

        $that = clone $this;

        $that->path = $path;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withQuery(string $query): UriInterface
    {
        if ($query === $this->getQuery()) {
            return $this;
        }

        $that = clone $this;

        $that->query = $query;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withFragment(string $fragment): UriInterface
    {
        $fragment = ltrim($fragment, '#');
        if ($fragment === $this->getFragment()) {
            return $this;
        }

        $that = clone $this;

        $that->fragment = $fragment;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return ($this->scheme ? $this->scheme . '://' : '') .
            ($this->getUserInfo() ? $this->getUserInfo() . '@' : '') .
            ($this->host) .
            ($this->port ? ':' . $this->port : '') .
            ($this->path ?? '') .
            ($this->query ? '?' . $this->query : '') .
            ($this->fragment ? '#' . $this->fragment : '')
        ;
    }
}
