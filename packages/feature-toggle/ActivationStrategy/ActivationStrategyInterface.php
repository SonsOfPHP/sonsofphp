<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\ActivationStrategy;

/**
 * Activation Strategy Interface
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface ActivationStrategyInterface
{
    /**
     * Returns true if this strategy is enabled
     *
     * @return bool
     */
    public function enabled(): bool;
}
