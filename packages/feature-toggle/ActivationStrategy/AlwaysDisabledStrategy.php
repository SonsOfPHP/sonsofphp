<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\ActivationStrategy;

/**
 * Always Disabled
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class AlwaysDisabledStrategy implements ActivationStrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public function enabled(): bool
    {
        return false;
    }
}
