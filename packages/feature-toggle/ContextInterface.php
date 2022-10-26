<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface ContextInterface
{
    /**
     * @return mixed
     */
    public function get(string $key);

    /**
     * @param mixed $value
     */
    public function set(string $key, $value): ContextInterface;

    public function has(string $key): bool;
}
