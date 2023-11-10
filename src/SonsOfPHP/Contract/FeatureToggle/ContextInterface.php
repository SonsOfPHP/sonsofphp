<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\FeatureToggle;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface ContextInterface extends \ArrayAccess, \IteratorAggregate, \JsonSerializable
{
    public function get(string $key);

    public function set(string $key, $value): self;

    public function has(string $key): bool;
}
