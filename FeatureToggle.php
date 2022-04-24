<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle;

use SonsOfPHP\Component\FeatureToggle\ActivationStrategy\ActivationStrategyInterface;

/**
 * Feature Toggle
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class FeatureToggle implements FeatureToggleInterface
{
    private ActivationStrategyInterface $strategy;

    public function __construct(ActivationStrategyInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled(): bool
    {
        return $this->strategy->enabled();
    }
}
