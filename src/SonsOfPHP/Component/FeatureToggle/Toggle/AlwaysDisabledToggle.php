<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Toggle;

use SonsOfPHP\Contract\FeatureToggle\ContextInterface;
use SonsOfPHP\Contract\FeatureToggle\ToggleInterface;

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
