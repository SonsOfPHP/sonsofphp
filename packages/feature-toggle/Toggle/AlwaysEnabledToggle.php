<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Toggle;

use SonsOfPHP\Component\FeatureToggle\ContextInterface;
use SonsOfPHP\Component\FeatureToggle\ToggleInterface;

/**
 * Always enabled.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class AlwaysEnabledToggle implements ToggleInterface
{
    public function isEnabled(ContextInterface $context): bool
    {
        return true;
    }
}
