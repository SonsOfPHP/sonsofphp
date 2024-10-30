<?php

declare(strict_types=1);

namespace SonsOfPHP\Bundle\FeatureToggleBundle\Twig\Runtime;

use SonsOfPHP\Component\FeatureToggle\Exception\FeatureNotFoundException;
use SonsOfPHP\Contract\FeatureToggle\FeatureToggleProviderInterface;
use Twig\Extension\RuntimeExtensionInterface;

final readonly class FeatureToggleExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private FeatureToggleProviderInterface $provider,
    ) {}

    public function isEnabled(string $key): bool
    {
        try {
            return $this->provider->get($key)->isEnabled();
        } catch (FeatureNotFoundException) {
        }

        return false;
    }
}
