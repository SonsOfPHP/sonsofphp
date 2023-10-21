<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface ToggleInterface
{
    /**
     * Returns true if this strategy is enabled.
     */
    public function isEnabled(?ContextInterface $context = null): bool;
}
