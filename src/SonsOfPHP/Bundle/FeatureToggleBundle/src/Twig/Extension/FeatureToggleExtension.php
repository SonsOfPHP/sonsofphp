<?php

declare(strict_types=1);

namespace SonsOfPHP\Bundle\FeatureToggleBundle\Twig\Extension;

use SonsOfPHP\Bundle\FeatureToggleBundle\Twig\Runtime\FeatureToggleExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class FeatureToggleExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_feature_enabled', [FeatureToggleExtensionRuntime::class, 'isEnabled']),
        ];
    }
}
