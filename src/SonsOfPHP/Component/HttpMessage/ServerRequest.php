<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpMessage;

use Psr\Http\Message\ServerRequestInterface;

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

    /**
     * {@inheritdoc}
     */
    public function getServerParams(): array
    {
        return $_SERVER;
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
        $that = clone $this;

        $that->attributes[strtolower($name)] = $value;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withoutAttribute(string $name): ServerRequestInterface
    {
        $that = clone $this;

        if (array_key_exists(strtolower($name), $this->attributes)) {
            unset($that->attributes[strtolower($name)]);
        }

        return $that;
    }
}
