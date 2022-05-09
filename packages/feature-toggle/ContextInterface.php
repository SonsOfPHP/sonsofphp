<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface ContextInterface
{
    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key);

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return self
     */
    public function set(string $key, $value): ContextInterface;

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool;
}
