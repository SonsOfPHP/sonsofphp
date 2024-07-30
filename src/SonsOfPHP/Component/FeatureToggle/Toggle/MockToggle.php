<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Toggle;

use SonsOfPHP\Contract\FeatureToggle\ContextInterface;
use SonsOfPHP\Contract\FeatureToggle\ToggleInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class MockToggle implements ToggleInterface
{
    public function __construct(
        private readonly bool $enabled = true,
    ) {}

    public function isEnabled(?ContextInterface $context = null): bool
    {
        return $this->enabled;
    }
}
