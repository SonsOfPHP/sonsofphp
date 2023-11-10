<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\FeatureToggle;

/**
 * Context is used to pass additional paramters to a toggle
 *
 * @todo Does it need all the extra interfaces? Doubtful
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface ContextInterface extends \ArrayAccess, \IteratorAggregate, \JsonSerializable
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
     *
     * @todo If this is a value object, it should be "with" instead of "set"
     */
    public function set(string $key, mixed $value): self;

    public function has(string $key): bool;
}
