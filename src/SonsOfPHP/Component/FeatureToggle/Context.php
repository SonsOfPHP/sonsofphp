<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle;

use SonsOfPHP\Contract\FeatureToggle\ContextInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Context implements ContextInterface
{
    public function __construct(private array $data = []) {}

    public function get(?string $key = null, mixed $default = null): mixed
    {
        if (null === $key) {
            return $this->data;
        }

        if (!$this->has($key)) {
            return $default;
        }

        return $this->data[$key];
    }

    public function set(string $key, mixed $value): ContextInterface
    {
        $this->data[$key] = $value;

        return $this;
    }

    public function has(string $key): bool
    {
        return \array_key_exists($key, $this->data);
    }
}
