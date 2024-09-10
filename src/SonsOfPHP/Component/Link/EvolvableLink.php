<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Link;

use Psr\Link\EvolvableLinkInterface;
use Stringable;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class EvolvableLink extends Link implements EvolvableLinkInterface
{
    /**
     * Returns an instance with the specified href.
     *
     * @param string|Stringable $href
     *   The href value to include.  It must be one of:
     *     - An absolute URI, as defined by RFC 5988.
     *     - A relative URI, as defined by RFC 5988. The base of the relative link
     *       is assumed to be known based on context by the client.
     *     - A URI template as defined by RFC 6570.
     *     - An object implementing __toString() that produces one of the above
     *       values.
     *
     * An implementing library SHOULD evaluate a passed object to a string
     * immediately rather than waiting for it to be returned later.
     */
    public function withHref(string|Stringable $href): static
    {
        if ($href instanceof Stringable) {
            $href = (string) $href;
        }

        $that = clone $this;
        $that->href = $href;

        return $that;
    }

    /**
     * Returns an instance with the specified relationship included.
     *
     * If the specified rel is already present, this method MUST return
     * normally without errors, but without adding the rel a second time.
     *
     * @param string $rel
     *   The relationship value to add.
     */
    public function withRel(string $rel): static
    {
        if (array_key_exists($rel, $this->rels)) {
            return $this;
        }

        $that = clone $this;
        $that->rels[$rel] = $rel;

        return $that;
    }

    /**
     * Returns an instance with the specified relationship excluded.
     *
     * If the specified rel is already not present, this method MUST return
     * normally without errors.
     *
     * @param string $rel
     *   The relationship value to exclude.
     */
    public function withoutRel(string $rel): static
    {
        if (!array_key_exists($rel, $this->rels)) {
            return $this;
        }

        $that = clone $this;
        unset($that->rels[$rel]);

        return $that;
    }

    /**
     * Returns an instance with the specified attribute added.
     *
     * If the specified attribute is already present, it will be overwritten
     * with the new value.
     *
     * @param string $attribute
     *   The attribute to include.
     * @param string|Stringable|int|float|bool|array $value
     *   The value of the attribute to set.
     */
    public function withAttribute(string $attribute, string|Stringable|int|float|bool|array $value): static
    {
        $that = clone $this;
        $that->attributes[$attribute] = $value;

        return $that;
    }

    /**
     * Returns an instance with the specified attribute excluded.
     *
     * If the specified attribute is not present, this method MUST return
     * normally without errors.
     *
     * @param string $attribute
     *   The attribute to remove.
     */
    public function withoutAttribute(string $attribute): static
    {
        if (!array_key_exists($attribute, $this->attributes)) {
            return $this;
        }

        $that = clone $this;
        unset($that->attributes[$attribute]);

        return $that;
    }
}
