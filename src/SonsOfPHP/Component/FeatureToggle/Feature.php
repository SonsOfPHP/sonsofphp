<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle;

use SonsOfPHP\Contract\FeatureToggle\ContextInterface;
use SonsOfPHP\Contract\FeatureToggle\FeatureInterface;
use SonsOfPHP\Contract\FeatureToggle\ToggleInterface;

/**
 * Feature.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final readonly class Feature implements FeatureInterface
{
    public function __construct(private string $key, private ToggleInterface $toggle) {}

    public function getKey(): string
    {
        return $this->key;
    }

    public function isEnabled(?ContextInterface $context = null): bool
    {
        return $this->toggle->isEnabled($context);
    }
}
