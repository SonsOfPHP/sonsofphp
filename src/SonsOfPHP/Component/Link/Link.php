<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Link;

use InvalidArgumentException;
use Psr\Link\LinkInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class Link implements LinkInterface
{
    protected array $rels = [];

    public function __construct(
        protected string $href = '',
        array|string $rels = null,
        protected array $attributes = [],
    ) {
        if ('' === $href && !$this instanceof EvolvableLink) {
            throw new InvalidArgumentException('MUST pass in href');
        }

        if (null === $rels) {
            return;
        }

        if (is_array($rels)) {
            foreach ($rels as $rel) {
                $this->rels[$rel] = $rel;
            }
        }

        if (is_string($rels)) {
            $this->rels[$rels] = $rels;
        }
    }

    /**
     * Returns the target of the link.
     *
     * The target link must be one of:
     * - An absolute URI, as defined by RFC 5988.
     * - A relative URI, as defined by RFC 5988. The base of the relative link
     *     is assumed to be known based on context by the client.
     * - A URI template as defined by RFC 6570.
     *
     * If a URI template is returned, isTemplated() MUST return True.
     */
    public function getHref(): string
    {
        return $this->href;
    }

    /**
     * Returns whether or not this is a templated link.
     *
     * @return bool
     *   True if this link object is templated, False otherwise.
     */
    public function isTemplated(): bool
    {
        return str_contains($this->href, '{') && str_contains($this->href, '}');
    }

    /**
     * Returns the relationship type(s) of the link.
     *
     * This method returns 0 or more relationship types for a link, expressed
     * as an array of strings.
     *
     * @return string[]
     */
    public function getRels(): array
    {
        return array_values($this->rels);
    }

    /**
     * Returns a list of attributes that describe the target URI.
     *
     * @return array
     *   A key-value list of attributes, where the key is a string and the value
     *  is either a PHP primitive or an array of PHP strings. If no values are
     *  found an empty array MUST be returned.
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
