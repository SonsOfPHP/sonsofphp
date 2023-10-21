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

    public function __construct(array $features = [])
    {
        foreach ($features as $feature) {
            $this->addFeature($feature);
        }
    }

    public function addFeature(FeatureInterface $feature): void
    {
        $this->features[$feature->getKey()] = $feature;
    }

    public function getFeatureToggleByKey(string $key): ?FeatureInterface
    {
        if (isset($this->features[$key])) {
            return $this->features[$key];
        }

        return null;
    }
}
