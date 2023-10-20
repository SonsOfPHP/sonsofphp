<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Toggle;

use SonsOfPHP\Component\FeatureToggle\ContextInterface;
use SonsOfPHP\Component\FeatureToggle\ToggleInterface;

/**
 * Always Disabled.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class AlwaysDisabledToggle implements ToggleInterface
{
    public function isEnabled(?ContextInterface $context = null): bool
    {
        return false;
    }
}
