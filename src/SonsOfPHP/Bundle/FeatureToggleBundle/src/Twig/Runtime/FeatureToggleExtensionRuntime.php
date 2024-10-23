<?php

declare(strict_types=1);

namespace SonsOfPHP\Bundle\FeatureToggleBundle\Twig\Runtime;

use SonsOfPHP\Contract\FeatureToggle\FeatureToggleProviderInterface;
use Twig\Extension\RuntimeExtensionInterface;

final readonly class FeatureToggleExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private FeatureToggleProviderInterface $provider,
    ) {}

    public function isEnabled(string $key): bool
    {
        return $this->provider->get($key)->isEnabled();
    }
}
