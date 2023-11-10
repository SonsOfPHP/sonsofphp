<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\FeatureToggle;

/**
 * Context is used to pass additional paramters to a toggle
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface ContextInterface// extends \ArrayAccess, \IteratorAggregate, \JsonSerializable
{
    /**
     * Get Context Parameters
     *
     * If the key does not exist, the $default should be returned
     *
     * @throws \InvalidArgumentException if key or default is invalid
     */
    public function get(string $key, mixed $default = null);

    /**
     * @throws \InvalidArgumentException if key or value is invalid
     */
    public function set(string $key, mixed $value): self;

    /**
     * If Context is a value object, with should be used instead.
     *
     * If key and value are the same, no need to clone, just return the same
     * object as nothing has changed
     *
     * @throws \InvalidArgumentException if key or value is invalid
     */
    //public function with(array|string $key, mixed $value = null): static;

    /**
     */
    public function has(string $key): bool;
}
