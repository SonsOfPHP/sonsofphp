<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpMessage;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

/**
 * {@inheritdoc}
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class ServerRequest extends Request implements ServerRequestInterface
{
    private array $cookieParams  = [];
    private array $queryParams   = [];
    private array $uploadedFiles = [];
    private array $attributes    = [];
    private null|array|object $data = null;

    public function __construct(
        ?string $method = null,
        UriInterface|string $uri = null,
        private array $serverParams = []
    ) {
        parent::__construct($method, $uri);

        $this->serverParams = array_merge($_SERVER, $serverParams);
    }

    /**
     * {@inheritdoc}
     */
    public function getServerParams(): array
    {
        return $this->serverParams;
    }

    /**
     * {@inheritdoc}
     */
    public function getCookieParams(): array
    {
        return $this->cookieParams;
    }

    /**
     * {@inheritdoc}
     */
    public function withCookieParams(array $cookies): ServerRequestInterface
    {
        // @todo if values are the same, do not clone

        $that = clone $this;

        $that->cookieParams = $cookies;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    /**
     * {@inheritdoc}
     */
    public function withQueryParams(array $query): ServerRequestInterface
    {
        // @todo if values are the same, do not clone

        $that = clone $this;

        $that->queryParams = $query;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function getUploadedFiles(): array
    {
        return $this->uploadedFiles;
    }

    /**
     * {@inheritdoc}
     */
    public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface
    {
        // @todo if values are the same, do not clone

        $that = clone $this;

        $that->uploadedFiles = $uploadedFiles;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function getParsedBody(): null|array|object
    {
        return $this->data ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function withParsedBody($data): ServerRequestInterface
    {
        // @todo if values are the same, do not clone

        $that = clone $this;
        $that->data = $data;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute(string $name, $default = null)
    {
        if (array_key_exists(strtolower($name), $this->attributes)) {
            return $this->attributes[strtolower($name)];
        }

        return $default;
    }

    /**
     * {@inheritdoc}
     */
    public function withAttribute(string $name, $value): ServerRequestInterface
    {
        // @todo if values are the same, do not clone

        $that = clone $this;

        $that->attributes[strtolower($name)] = $value;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withoutAttribute(string $name): ServerRequestInterface
    {
        // @todo if values are the same, do not clone

        $that = clone $this;

        if (array_key_exists(strtolower($name), $this->attributes)) {
            unset($that->attributes[strtolower($name)]);
        }

        return $that;
    }
}
