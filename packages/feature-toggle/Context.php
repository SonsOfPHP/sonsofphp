<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Context implements ContextInterface
{
    private array $data = [];

    /**
     * {@inheritdoc}
     */
    public function get(string $key)
    {
        if ($this->has($key)) {
            return $this->data[$key];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, $value): ContextInterface
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }
}
