<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Provider;

use SonsOfPHP\Component\FeatureToggle\FeatureInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class InMemoryFeatureToggleProvider implements FeatureToggleProviderInterface
{
    private array $features = [];

    public function __construct($features = [])
    {
        foreach ($features as $feature) {
            $this->addFeature($feature);
        }
    }

    public function addFeature(FeatureInterface $feature): void
    {
        $this->features[] = $feature;
    }

    /**
     * {@inheritdoc}
     */
    public function getFeatures(): iterable
    {
        yield from $this->features;
    }
}
