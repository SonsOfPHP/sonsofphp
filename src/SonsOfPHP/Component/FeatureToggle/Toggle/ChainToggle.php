<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Toggle;

use SonsOfPHP\Contract\FeatureToggle\ContextInterface;
use SonsOfPHP\Contract\FeatureToggle\ToggleInterface;

/**
 * Chain Toggle will take multiple Toggles and if any are enabled,
 * it will be enabled.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class ChainToggle implements ToggleInterface
{
    public function __construct(
        private array $toggles,
    ) {}

    public function isEnabled(?ContextInterface $context = null): bool
    {
        foreach ($this->toggles as $toggle) {
            if ($toggle->isEnabled($context)) {
                return true;
            }
        }

        return false;
    }
}
