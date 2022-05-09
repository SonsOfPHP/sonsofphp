<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Provider;

use SonsOfPHP\Component\FeatureToggle\Exception\FeatureToggleException;
use SonsOfPHP\Component\FeatureToggle\FeatureInterface;

/**
 * Feature Toggle Provider Interface
 *
 * The provider's resposibile is to maintain the feature toggles. These feature
 * toggles could be pulled from a database of a yaml file.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface FeatureToggleProviderInterface
{
    /**
     * @return FeatureInterface[]
     */
    public function getFeatures(): iterable;
}
