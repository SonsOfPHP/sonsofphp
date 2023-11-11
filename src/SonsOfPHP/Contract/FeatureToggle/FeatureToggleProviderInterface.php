<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\FeatureToggle;

/**
 * Feature Toggle Provider Interface.
 *
 * The provider's resposibile is to maintain the feature toggles. These feature
 * toggles could be pulled from a database of a yaml file.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface FeatureToggleProviderInterface
{
    public function getFeatureToggleByKey(string $key): ?FeatureInterface;
}
