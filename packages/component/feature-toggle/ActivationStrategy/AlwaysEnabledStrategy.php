<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\ActivationStrategy;

/**
 * Always enabled
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class AlwaysEnabledStrategy implements ActivationStrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public function enabled(): bool
    {
        return true;
    }
}
