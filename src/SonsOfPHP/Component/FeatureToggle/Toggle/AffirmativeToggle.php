<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Toggle;

use SonsOfPHP\Contract\FeatureToggle\ContextInterface;
use SonsOfPHP\Contract\FeatureToggle\ToggleInterface;

/**
 * Affirmative Toggle will take multiple toggles and will only be enabled if
 * all the toggles are enabled
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class AffirmativeToggle implements ToggleInterface
{
    public function __construct(
        private array $toggles,
    ) {}

    public function isEnabled(?ContextInterface $context = null): bool
    {
        foreach ($this->toggles as $toggle) {
            if (!$toggle->isEnabled($context)) {
                return false;
            }
        }

        return true;
    }
}
