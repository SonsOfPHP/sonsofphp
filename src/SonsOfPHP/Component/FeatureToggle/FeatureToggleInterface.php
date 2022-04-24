<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle;

/**
 * Feature Toggle Interface
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface FeatureToggleInterface
{
    /**
     * Returns true or false if the toggle is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool;
}
